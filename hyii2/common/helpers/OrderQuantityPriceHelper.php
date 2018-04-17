<?php
/**
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 * @Author esion wong
 * @Date 2016/3/15 16:37
 */

namespace Anlewo\Common\helpers;

use Anlewo\Common\Anlewo\Account\AccountingPrice;
use Anlewo\Common\Anlewo\Account\CeramicTileAccount;
use Anlewo\Common\Anlewo\Account\DoorAccount;
use Anlewo\Common\Anlewo\Account\FloorTileAccount;
use Anlewo\Common\Anlewo\Account\FootLineAccount;
use Anlewo\Common\Anlewo\Account\IntegratedCeilingAccount;
use Anlewo\Common\Anlewo\Account\ShowerRoomAccount;
use Anlewo\Common\Anlewo\Account\SingleAccount;
use Anlewo\Common\Anlewo\Account\WoodFloorAccount;
use Anlewo\Common\Anlewo\Constant;
use Anlewo\Common\Anlewo\QuantityCheck\CeilingQuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\CeramicTileQuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\CupboardQuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\DoorQuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\GroupQuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\QuantityCheck;
use Anlewo\Common\Anlewo\QuantityCheck\QuantityCheckManager;
use Anlewo\Common\Anlewo\QuantityCheck\ShowerRoomQuantityCheck;
use Anlewo\Common\Models\Orders;
use Anlewo\SDK\Bridge\GoodsClassTable;
use Anlewo\Common\Models\OrderRejected;
use Yii;

/**
 * 核量核价Helper类
 * Class OrderQuantityPriceHepler
 * @package Anlewo\Common\helpers
 */
class OrderQuantityPriceHelper {
    public $class = [
        Constant::CUSTOM_CLASS_GROUP => ['name' => '地面','class' => 'Group'],
        Constant::CUSTOM_CLASS_CeramicTile => ['name' => '墙面','class' => 'CeramicTile'],
        Constant::CUSTOM_CLASS_Door => ['name' => '门','class' => 'Door'],
        Constant::CUSTOM_CLASS_Cupboard => ['name' => '橱柜','class' => 'Cupboard'],
        Constant::CUSTOM_CLASS_Ceiling => ['name' => '集成吊顶','class' => 'Ceiling'],
        Constant::CUSTOM_CLASS_SHOWER_ROOM => ['name' => '淋浴房','class' => 'ShowerRoom'],

    ];  //分类

    public $type = [
        Constant::GOODS_CLASS_BRICK => ['name' => '地砖', 'class' => 'FloorTile'],
        Constant::GOODS_CLASS_ANCHOR => ['name' => '地脚线', 'class' => 'FootLine'],
        Constant::GOODS_CLASS_FLOOR => ['name' => '木地板', 'class' => 'WoodFloor'],
        Constant::GOODS_CLASS_CERAMICS => ['name' => '瓷片', 'class' => 'CeramicTile'],
        Constant::GOODS_CLASS_ROOM_DOOR => ['name' => '房门', 'class' => 'Door'],
        Constant::GOODS_CLASS_TOILET_DOOR => ['name' => '卫生间门', 'class' => 'Door'],
        Constant::GOODS_CLASS_KITCHEN_DOOR => ['name' => '厨房门', 'class' => 'Door'],
        Constant::GOODS_CLASS_SHOWER_ROOM => ['name' => '淋浴房', 'class' => 'ShowerRoom'],
//        Constant::GOODS_CLASS_CLOSESTOOL => ['name' => '马桶', 'class' => 'Closestool'],
//        Constant::GOODS_CLASS_SQUAT => ['name' => '蹲厕', 'class' => 'Squat'],
//        Constant::GOODS_CLASS_BATHROOM_ARK => ['name' => '浴室柜', 'class' => 'BathroomCabinet'],
//        Constant::GOODS_CLASS_SPRINKLER => ['name' => '花洒', 'class' => 'Shower'],
        Constant::GOODS_CLASS_CONDOLE_TOP => ['name' => '集成吊顶', 'class' => 'IntegratedCeiling'],
    ]; //类型

    /**
     * 核量
     * @param $data
     * @return array
     */
    public function quantityCheck($data) {
        if (empty($data) || !is_array($data)) return ['state'=>false];
        $all_area =  $data['all_area'];
        unset($data['all_area']);
        $addToilets = 0;
        if(isset($data['addToilets'])){
            $addToilets = $data['addToilets'];
            unset($data['addToilets']);
        }

        $order_data = $this->_dataHandle($data); //数组格式转换
        $result = [];
        foreach($order_data as $key=>$value) {
            $value['all_area'] = $all_area;
            $value['addToilets'] = $addToilets;
            if (isset($this->class[$key]['class'])) {
                $quantity = new QuantityCheck($this->_class($this->class[$key]['class']));
                $result[$key] = $quantity->quantityCheck($value);
            } else {
                $result[$key] = ['state'=>false];
            }
        }
        return $result;
    }

    /**
     * 计算用量
     * @param $data
     * @return array
     */
    public function userAmount($data) {
        if (empty($data) || !is_array($data)) return false;
        $result = [];
        foreach($data as $key=>$value) {
            if (isset($this->class[$key]['class'])) {
                $quantity = new QuantityCheck($this->_class($this->class[$key]['class']));
                $result[$key] = $quantity->userAmount($value);
            } else {
                $result[$key] = false;
            }
        }
        return $result;
    }

    /**
     * 计价
     * [
     *  '地砖id' => [
     *      ['商品数据', '用量', 'options'],
     *      ['商品数据', '用量', 'options'],
     *  ]
     * ]
     * @param $data
     * @return array
     */
    public function amount($data) {
        if (empty($data) || !is_array($data)) return false;
        $result = [];
        $all_area=0;
        if(isset($data['all_area'])) {
            $all_area = $data['all_area'];
            unset($data['all_area']);
        }
        $reduceData = [];
        if(isset($data['reduceData'])) {
            $reduceData = $data['reduceData'];
            unset($data['reduceData']);
        }

        $reduceCheckArea = $this->_reduceToAreaData($reduceData);

        //对应类型调用计价
        foreach ($data as $key=>$value) {
            if (isset($this->type[$key]['class'])) {
                $class = $this->_accountClass($this->type[$key]['class']);
                if($class === false){
                    continue;
                }
                $account = new AccountingPrice($class);
                foreach($value as $k => $val) {
                    $arr_in = array_merge($val['goods'],$val['goodsPrice']);
                    $arr_in['all_area']=$all_area;
                    $checkArea = $arr_in['checkArea'];
                    if(isset($reduceCheckArea[$checkArea]) &&  $reduceCheckArea[$checkArea] == 1){
                        $arr_in['isReduce'] = 1; //是否已减项
                        $val['quantity'] += $arr_in['count']; //把当前用量也放到超量区里面算补货价
                    }
                    else{
                        $arr_in['isReduce'] = 0;
                    }

                    $res = $account->amount($arr_in,$val['quantity'],$val['options']);
                    if ($res) {
                        $result[$key][$arr_in['subOrderId']] = $res;
                    } else {
                        $result[$key][$arr_in['subOrderId']] = 0;
                    }
                }
            } else {//不用计价的数据
                if(is_array($value))
                foreach($value as $k => $val) {
                    $c = new SingleAccount();
                    $res = $c->amount(array_merge($val['goods'],$val['goodsPrice']),$val['quantity'],$val['options']);
                    if ($res) {
                        $result[$key][$val['goods']['subOrderId']] = $res;
                    } else {
                        $result[$key][$val['goods']['subOrderId']] = 0;
                    }
                }
            }
        }
        return $result;
    }


    /**
     * 门店计价
     * [
     *  '地砖id' => [
     *      ['商品数据', '用量', 'options'],
     *      ['商品数据', '用量', 'options'],
     *  ]
     * ]
     * @param $data
     * @return array
     */
    public function amountStore($data) {
        if (empty($data) || !is_array($data)) return false;
        $result = [];
        $all_area=0;
        if(isset($data['all_area'])){
            $all_area = $data['all_area'];
            unset($data['all_area']);
        }

        //对应类型调用计价
        foreach ($data as $key=>$value) {
            if (isset($this->type[$key]['class'])) {
                $class = $this->_accountClass($this->type[$key]['class']);
                if($class === false){
                    continue;
                }
                $account = new AccountingPrice($class);
                foreach($value as $k => $val) {
                    $arr_in = array_merge($val['goods'],$val['goodsPrice']);
                    $arr_in['all_area']=$all_area;
                    $res = $account->amountStore($arr_in,$val['quantity'],$val['options']);
                    if ($res) {
                        $result[$k] = $res;
                    } else {
                        $result[$k] = 0;
                    }
                }
            } else {//不用计价的数据
                if(is_array($value))
                    foreach($value as $k => $val) {
                        $c = new SingleAccount();
                        $res = $c->amountStore(array_merge($val['goods'],$val['goodsPrice']),$val['quantity'],$val['options']);
                        if ($res) {
                            $result[$k] = $res;
                        } else {
                            $result[$k] = 0;
                        }
                    }
            }
        }
        return $result;
    }


    /**
     * 添加超量区域
     * @param $data
     * @return array
     * TODO: 以后价格统一调用Account
     */
    public function addBeyondZone($data) {
        $cur = $data['cur'];
        unset($data['cur']);

        $curId    = $cur['id'];
        $curZone  = $cur['zone'];
        $curType  = $cur['type'];
        $curClass = $cur['checkArea'];
        $outArr   = []; //返回的数组

        //beyondNum 大于0不需要计算超量
        if (isset($cur['beyondNum']) && $cur['beyondNum'] > 0) {
            if(isset($cur['goodsPrice'])){
                $outArr['beyond_amount'] = floatval($cur['beyondNum']) * floatval($cur['goodsPrice']['customReplenishmentPrice']);   //超量价格
            }
            else{
                $outArr['beyond_amount'] = 0;
            }
            $outArr['beyond_num']    = $cur['beyondNum'];  //超量数量
            $outArr['spec']          = '';
            $outArr['unit']          = '';
        } else {
            $quantityCheck = $this->quantityCheck($data);
            //获取规格
            $spec  = '';
            $count = 1;
            foreach($data[$curZone][$curClass] as $value) {
                if ($value['type'] == $curType && $value['id'] == $curId && $value['ceramicsColor'] == $cur['ceramicsColor']) {
                    if($curType != Constant::GOODS_CLASS_FLOOR && $curType != Constant::GOODS_CLASS_CONDOLE_TOP){ //木地板没有规格
                        $spec = QuantityCheckManager::changeSpec($value,$curType);
                    }
                    $count = $value['count'];
                    break;
                }
            }

            $addCount = 0;
            $beyond_num = 0;
            $unit = '块';
            $spec_data = [];
            //如果是瓷片或者地砖就有规格，木地板没有
            if(!empty($spec)) {
                //规格转面积
                $spec_data = explode('*',$spec);
                if(count($spec_data) >= 2){
                    $one       = ($spec_data[0] * $spec_data[1])/1000000;
                    if($one > 0){
                        $beyond_num = ceil(round($quantityCheck[$curClass]['quantity']/$one,2));
                        if($beyond_num > $count){
                            $beyond_num = $count;
                        }
                        $addCount = $one * $beyond_num;
                    }
                }
            }
            else{
                //判断超量的和区域的用量的哪个大
                if($quantityCheck[$curClass]['quantity'] > $count){
                    $beyond_num = $count;
                }
                else{
                    $beyond_num = $quantityCheck[$curClass]['quantity'];
                }
                $addCount = $beyond_num;
                $unit = '平方米';
            }

            $outArr = $quantityCheck[$curClass];
            $outArr['cur'] = $quantityCheck[$curClass]['cur']-$addCount;
            $outArr['beyond_num'] = $beyond_num;
            if($quantityCheck[$curClass]['quantity'] > $addCount){
                $outArr['state']      = true;
            }
            else{
                $outArr['state']      = false;
            }
            $outArr['spec']=$spec;
            $outArr['unit']=$unit;
            if(isset($cur['goodsPrice'])){
                $outArr['beyond_amount'] = floatval($outArr['beyond_num']) * floatval($cur['goodsPrice']['customReplenishmentPrice']);   //超量价格
            }
            else{
                $outArr['beyond_amount'] = 0;
            }
        }

        $outArr['class']=$curClass;
        $outArr['checkArea']     = $curClass;

        //去掉超量区也加收30元/平方的显示
        //if($outArr['beyond_amount'] != 0){
        //    //判断如果是地砖类型的，则需要加收30元/平方
        //    if($curClass == Constant::CUSTOM_CLASS_CeramicTile && $curType == Constant::GOODS_CLASS_BRICK && count($spec_data) >= 2){
        //        $minSpecArr = \Yii::$app->params['CeramicTileToBrickMinSpec'];
        //        if($spec_data[0] >= $minSpecArr[0] && $spec_data[1] >= $minSpecArr[1]){
        //            $area = 0;
        //            foreach($data[$curZone][$curClass] as $value) {
        //                if ($value['type'] == $curType) {
        //                    $ceramicsColor = 0;
        //                    if(isset($cur['ceramicsColor']))
        //                    {
        //                        $ceramicsColor = $cur['ceramicsColor'];
        //                    }
        //                    $h = $value['height'];
        //                    //下色=1，上色=2
        //                    if($ceramicsColor == 1){  //下色选地砖，那么下色需加价=0.9m × 周长 × 30元/㎡
        //                        $h = \Yii::$app->params['ceramicsColorHeight'];
        //                    }
        //                    else if($ceramicsColor == 2){//上色选地砖，那么上色需加价=（墙高-0.9m）×周长 × 30元/㎡
        //                        $h -= \Yii::$app->params['ceramicsColorHeight'];
        //                    }
        //
        //                    $area = $value['perimeter'] * $h / 1000000;
        //                }
        //            }
        //
        //            $outArr['beyond_amount'] += \Yii::$app->params['CeramicTileToBrick'] * $area;
        //        }
        //    }
        //}

        return $outArr;
    }

    /**
     * 区域计算
     * @param $area
     * @return array
     */
    public function areaCalculation($area,$orderBusinessType=0) {
        $area = floatval($area);
        $data = [
            'room'    => 0,
            'hall'    => 0,
            'toilet'  => 0,
            'kitchen' => 0,
            'balcony' => 0,
        ];
        //卫生间标配计算
        if ($area <= 118) {
            if($orderBusinessType==Orders::ORDER_398_BUSINESS){
                $data['toilet'] = 1;
            }else{
                $data['toilet'] = 2;
            }
        }else if ($area > 118 && $area <= 159) {
            $data['toilet'] = 2;
        }else if ($area > 159 && $area <= 259) {
            $data['toilet'] = 3;
        }else if ($area > 259 && $area <= 359) {
            $data['toilet'] = 4;
        }else if ($area > 359 && $area <= 500) {
            $data['toilet'] = 5;
        }else {
            $data['toilet'] = 5;
        }
        //卫生间增加的价格
        $data['first_price'] = Yii::$app->params['toilet_first_price'];    //超量第一个卫生间的价格
        $data['second_price'] = Yii::$app->params['toilet_second_price'];  //超量第二个卫生间的价格

        //橱柜类
        $cupboard = new CupboardQuantityCheck();

        //地柜
        $floor_cabinet = $cupboard->getSpec($area, 0);
        $data['floor_cabinet']['width']  = $floor_cabinet[0];    //地柜宽度
        $data['floor_cabinet']['height'] = $floor_cabinet[1];    //地柜高度
        $data['floor_cabinet']['depth']  = $floor_cabinet[2];    //地柜深度
        //吊柜
        $wall_cupboard = $cupboard->getSpec($area, 1);
        $data['wall_cupboard']['width']  = $wall_cupboard[0];      //吊柜宽度
        $data['wall_cupboard']['height'] = $wall_cupboard[1];      //吊柜高度
        $data['wall_cupboard']['depth']  = $wall_cupboard[2];      //吊柜深度
        //台面
        $countertop = $cupboard->getSpec($area, 2);
        $data['countertop']['width']  = $countertop[0];      //台面宽度
        $data['countertop']['height'] = $countertop[1];      //台面高度
        $data['countertop']['depth']  = $countertop[2];      //台面深度

        $DoorConfig = Yii::$app->params['DoorConfig'];
        //房门
        $data['room_door']['height']      = $DoorConfig[0][0];      //房门高度
        $data['room_door']['width']       = $DoorConfig[0][1];      //房门宽度
        $data['room_door']['depth']       = $DoorConfig[0][2];      //房门厚度

        //卫生间门
        $data['bathroom_door']['height']      = $DoorConfig[1][0];      //卫生间门高度
        $data['bathroom_door']['width']       = $DoorConfig[1][1];      //卫生间门宽度
        $data['bathroom_door']['depth']       = $DoorConfig[1][2];      //卫生间门厚度

        return $data;
    }

    /**
     * 减项
     * @param $data
     *
     * @return array
     */
    public function deductionPrice(&$data){
        if(empty($data)){
            return [];
        }

        $all_area = $data['all_area'];
        $addToilets = empty($data['addToilets']) ? 0 : $data['addToilets'];
        $layoutData = $this->areaCalculation($all_area);
        $toilet = $layoutData['toilet']; //卫生间数量

        $arrfloor = [Constant::GOODS_CLASS_BRICK,Constant::GOODS_CLASS_FLOOR,Constant::GOODS_CLASS_ANCHOR];
        $configArr = Constant::$cateToDeductionPrice;
        $reduceArr = []; //用来控制整类减去的。如果第二次再过来不做操作
        foreach ($data as &$item) {
            if(!is_array($item)) {
                continue;
            }

            if(empty($item['checkArea'])){
                continue;
            }

            $type = $item['type'];
            if(empty($configArr[$type])) {
                continue;
            }

            $checkArea = $item['checkArea'];

            if($checkArea == Constant::CUSTOM_CLASS_GROUP) { //地面类
                if(!empty($reduceArr[$checkArea])){
                    $item['reducePrice'] = 0;
                    continue;
                }
                $type                  = Constant::GOODS_CLASS_BRICK;
                $unitPrice             = $configArr[$type];
                $useArea               = Yii::$app->params['maxGround'] * $all_area;
                //当前用量和最大用量比较，用最小的做减项
                $curUse = $data['groupArea'];
                if($curUse > $useArea)
                {
                    $curUse = $useArea;
                }

                $item['reducePrice']   = round($curUse * $unitPrice ,2);
                $reduceArr[$checkArea] = true;
            }
            else if($checkArea == Constant::CUSTOM_CLASS_CeramicTile){ //墙面
                if(!empty($reduceArr[$checkArea])){
                    $item['reducePrice'] = 0;
                    continue;
                }
                $type                  = Constant::GOODS_CLASS_CERAMICS;
                $unitPrice             = $configArr[$type];
                $c                     = new CeramicTileQuantityCheck();
                $maxUse                = $c->getMaxQuantity($all_area,$toilet,$addToilets);

                //当前用量和最大用量比较，用最小的做减项
                $curUse = $data['wallArea'];
                if($curUse > $maxUse)
                {
                    $curUse = $maxUse;
                }
                $item['reducePrice']   = round($curUse * $unitPrice, 2);
                $reduceArr[$checkArea] = true;
            }
            else if($checkArea == Constant::CUSTOM_CLASS_Ceiling){ //吊顶
                if(!empty($reduceArr[$checkArea])){
                    $item['reducePrice'] = 0;
                    continue;
                }
                $type                  = Constant::GOODS_CLASS_CONDOLE_TOP;
                $unitPrice             = $configArr[$type];
                $c                     = new CeilingQuantityCheck();
                $maxUse                = $c->getMaxQuantity($all_area,$toilet + $addToilets);

                //当前用量和最大用量比较，用最小的做减项
                $curUse = $data['condoleArea'];
                if($curUse > $maxUse)
                {
                    $curUse = $maxUse;
                }
                $item['reducePrice']   = round($curUse * $unitPrice, 2);
                $reduceArr[$checkArea] = true;
            }
            else if($checkArea == Constant::CUSTOM_CLASS_Cupboard) { //橱柜
                $item['reducePrice'] = 0;
                if(!empty($reduceArr[$checkArea])){
                    continue;
                }
                $c = new CupboardQuantityCheck();
                $type      = Constant::GOODS_CLASS_GROUND_ARK;
                $unitPrice = $configArr[$type];

                //地柜+台面
                $spec = $c->getSpec($all_area,0);
                $len  = $spec[0] * 0.001;
                $item['reducePrice'] += round($len * $unitPrice[0], 2);
                $reduceArr[$checkArea] = true;

                //吊柜
                $spec = $c->getSpec($all_area,1);
                $len  = $spec[0] * 0.001;
                $item['reducePrice'] += round($len * $unitPrice[1], 2);
            }
            else {
                $count = 1;
                if(!empty($item['count'])){
                    $count = $item['count'];
                }
                $unitPrice = $configArr[$type];
                $item['reducePrice'] = round($count * $unitPrice, 2);
            }
        }
    }


    /**
     * new class
     * @param $data
     * @return GroupQuantityCheck|DoorQuantityCheck|CeilingQuantityCheck|CupboardQuantityCheck|bool
     */
    private function _class($data) {
        switch($data) {
            case 'Group':
                return new GroupQuantityCheck(); //地面
                break;
            case 'CeramicTile':
                return new CeramicTileQuantityCheck(); //瓷砖
                break;
            case 'Door':
                return new DoorQuantityCheck(); //门
                break;
            case 'Ceiling':
                return new CeilingQuantityCheck(); //吊顶
                break;
            case 'Cupboard':
                return new CupboardQuantityCheck(); //橱柜
                break;
            case 'ShowerRoom':
                return new ShowerRoomQuantityCheck();//淋浴房
                break;
            default:
                return false;
        }

    }

    /**
     * new class
     * @param $data
     * @return DoorAccount|IntegratedCeilingAccount|KitchenCabinetAccount|bool
     */
    private function _accountClass($data) {
        switch($data) {
            case 'FloorTile':
                return new FloorTileAccount(); //地砖
                break;
            case 'FootLine':
                return new FootLineAccount(); //地脚线
                break;
            case 'WoodFloor':
                return new WoodFloorAccount(); //木地板
                break;
            case 'CeramicTile':
                return new CeramicTileAccount(); //瓷片
                break;
            case 'Door':
                return new DoorAccount(); //门
                break;
            case 'KitchenCabinet':
                return new KitchenCabinetAccount(); //橱柜
                break;
            case 'IntegratedCeiling':
                return new IntegratedCeilingAccount(); //集成吊顶
                break;
            case 'ShowerRoom':
                return new ShowerRoomAccount();//淋浴房
                break;
            default:
                return false;
        }
    }

    /**
     * 数组处理
     * @param $data
     * @return array
     */
    private function _dataHandle($data) {
        $result = [];
        foreach($data as $zone=>$value) {
            foreach($value as $key=>$val) {
                foreach($val as $v) {
                    $v['zone'] = $zone;
                    $result[$key][] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * 减项数据返回区域是否被减项
     * @param $reduceData
     */
    private function _reduceToAreaData($reduceData){
        $reduceCheckArea = [
            Constant::CUSTOM_CLASS_GROUP       => 0,
            Constant::CUSTOM_CLASS_CeramicTile => 0,
            Constant::CUSTOM_CLASS_Cupboard    => 0,
            Constant::CUSTOM_CLASS_Ceiling     => 0,
        ];

        foreach ($reduceData as $item) {
            $checkArea = $item['checkArea'];
            if(isset($reduceCheckArea[$checkArea]))
            {
                $reduceCheckArea[$checkArea] = 1;//1是
            }
        }

        return $reduceCheckArea;
    }


    //======================================================================
    /**
     * 计算驳回的超量
     * @param $old  旧订单数据
     * @param $new  新订单数据
     *
     * @return float|int
     */
    public function getRebutQuantity($old,$new){
        //判断是否超量存在，如果不存在，就不计算了
        if(!isset($old['superQuantity']) || floatval($old['superQuantity']) <= 0){
            return 0;
        }

        $cateId = $old['cateId'];
        $superQuantity = $old['superQuantity'];
        $area = 0;
        if(in_array($cateId, [GoodsClassTable::GOODS_CLASS_FLOOR, GoodsClassTable::GOODS_CLASS_CONDOLE_TOP])){
            $area = $superQuantity;
        }
        else{
            $arr['specStr'] = $old['spec'];
            $spec = QuantityCheckManager::changeSpec($arr,$old['cateId']);
            $arrSpec = explode('*',$spec);
            if(count($arrSpec) < 2){
                return 0;
            }
            $area = $superQuantity * $arrSpec[0] * $arrSpec[1];
        }

        if($new['cateId'] == Constant::GOODS_CLASS_FLOOR){
            return $area;
        }
        $arr['specStr'] = $new['spec'];
        $newSpec = QuantityCheckManager::changeSpec($arr,$new['cateId']);
        $arrSpec = explode('*',$newSpec);
        if(count($arrSpec) < 2){
            return 0;
        }

        return ceil($area / ($arrSpec[0] * $arrSpec[1]));
    }

    /**
     * 重新计算驳回的总数量
     * @param $old  旧订单数据
     * @param $new  新订单数据
     *
     * @return float|int
     */
    public function getRebutNum($old,$new){
        $dosageList = [
            Constant::GOODS_CLASS_BRICK,
            Constant::GOODS_CLASS_CERAMICS,
        ];
        $otherData = json_decode($new['otherData'], true);
        if(!in_array($old['cateId'], $dosageList)) {
            return $old['num'];
        }
        if($new['cateId'] == Constant::GOODS_CLASS_FLOOR) {
            return $otherData['area'];
        }
        if($old['cateId'] == Constant::GOODS_CLASS_FLOOR) {
            $area = $otherData['area'];
        } else {
            $arr['specStr'] = $old['spec'];
            $spec = QuantityCheckManager::changeSpec($arr,$old['cateId']);
            $arrSpec = explode('*', $spec);
            if(count($arrSpec) < 2) {
                return $old['num'];
            }
            $area = $old['num'] * $arrSpec[0] * $arrSpec[1];
        }

        $arr['specStr'] = $new['spec'];
        $newSpec = QuantityCheckManager::changeSpec($arr,$new['cateId']);
        $arrSpec = explode('*', $newSpec);
        if(count($arrSpec) < 2){
            return $old['num'];
        }
        return ceil($area / ($arrSpec[0] * $arrSpec[1]));
    }

    /**
     * 计算驳回的地脚线的周长
     * @param $old  旧订单数据
     * @param $new  新订单数据
     *
     * @return float|int
     */
    public function getRebutAnchor($sub)
    {
        $data = json_decode($sub['otherData'], true);
        $arr['specStr'] = $sub['spec'];
        $spec = QuantityCheckManager::changeSpec($arr,$sub['cateId']);
        $arrSpec = explode('*', $spec);
        if(count($arrSpec) < 2) {
            return $data['perimeter'];
        }
        //获取规格里面最大的长宽
        $max = intval($arrSpec[0]);
        $min = intval($arrSpec[1]);
        if($arrSpec[0] < $arrSpec[1]) {
            $max = intval($arrSpec[1]);
            $min = intval($arrSpec[0]);
        }
        if($max <= 0 ){
            return $data['perimeter'];
        }
        if ($data['style'] == '-1') { //一开八
            return $sub['num'] * 8 * $max;
        }
        else if(empty($data['style'])){
            if($max <= 0){
                return $data['perimeter'];
            }
            return $sub['num'] * $max;
        }
        else {
            $mm = floatval($data['style']*0.1);
            $block = floor($min/10/($mm + Yii::$app->params['FootLineValue']));
            if($block <= 0){
                return $data['perimeter'];
            }
            return $sub['num'] * $block * $max;;
        }
    }

    /**
     * 「算价」驳回重新选材后需要计算差价
     * @param $data
     *
     * @return bool
     */
    public function rebutAmount(&$data, $order){
        if(!is_array($data)){
            return false;
        }

        $subOrders = [];
        // 补差价其他价格砖上墙、大自然
        $otherAmount = [
            'brickAmount' => 0,
            'natureFee'   => 0,
        ];
        foreach ($data as $areaData) {
            if(!is_array($areaData)){
                continue;
            }

            foreach ($areaData as $one) {
                if(empty($one['id'])){
                    continue;
                }

                $subOrders[$one['id']] = $one;
            }
        }

        $natureFloorArea = 0; // 大自然地板用量
        //计算
        foreach ($data as &$areaData) {
            if(!is_array($areaData)){
                continue;
            }

            foreach ($areaData as &$one){
                if(!is_array($one)) {
                    continue;
                }
                if(empty($one['id'])){
                    continue;
                }
                if($one['state'] != -2 && $one['brandId'] == Constant::BRAND_NATURE && $one['cateId'] == Constant::GOODS_CLASS_FLOOR) {
                    $natureFloorArea += $one['num'];
                }

                if($one['state'] == -2
                    && $one['checkArea'] == Constant::CUSTOM_CLASS_CeramicTile
                    && $one['cateId'] == GoodsClassTable::GOODS_CLASS_BRICK)
                {
                    // 砖上墙计算价格的最小尺寸
                    $minSpecArr = Yii::$app->params['CeramicTileToBrickMinSpec'];
                    // 旧单砖上墙价格
                    $arr['specStr'] = $one['spec'];
                    $oldSpec = QuantityCheckManager::changeSpec($arr,$one['cateId']);
                    $arrSpec = explode('*', $oldSpec);
                    // 计算墙上砖加价
                    if(count($arrSpec) >= 2
                        && $arrSpec[0] >= $minSpecArr[0] && $arrSpec[1] >= $minSpecArr[1])
                    {

                        $area = $one['otherData']['perimeter'] * $one['otherData']['height'] * 0.000001;
                        $otherAmount['brickAmount'] -= $area *  Yii::$app->params['CeramicTileToBrick'];
                    }
                }
                

                if($one['payState'] == 1) continue;
                //判断如果驳回前的订单ID存在，则计算价格
                if(!isset($one['rejectedId']) || $one['rejectedId'] == 0)
                {
                    continue;
                }

                //没找到
                if(!is_array($subOrders[$one['rejectedId']])){
                    continue;
                }

                //旧的子订单数据
                $oldSubOrders = $subOrders[$one['rejectedId']];

                $goods = $one;

                // 总价
                $totalPrice = 0;
                // 得到旧订单的总价
                $oldTotalPrice = $oldSubOrders['amount'] + $oldSubOrders['superAmount'];
                // // 砖上墙计算价格的最小尺寸
                // $minSpecArr = \Yii::$app->params['CeramicTileToBrickMinSpec'];
                // // 旧单砖上墙价格
                // $oldBrickAmount = 0;
                // $arr['specStr'] = $oldSubOrders['spec'];
                // $oldSpec = QuantityCheckManager::changeSpec($arr,$oldSubOrders['cateId']);
                // $arrSpec = explode('*', $oldSpec);
                // // 计算墙上砖加价
                // if(count($arrSpec) >= 2
                //     && $arrSpec[0] >= $minSpecArr[0] && $arrSpec[1] >= $minSpecArr[1]
                //     && $oldSubOrders['checkArea'] == Constant::CUSTOM_CLASS_CeramicTile
                //     && $oldSubOrders['cateId'] == GoodsClassTable::GOODS_CLASS_BRICK)
                // {

                //     $area = $oldSubOrders['otherData']['perimeter'] * $oldSubOrders['otherData']['height'] * 0.000001;
                //     $oldBrickAmount = $area *  \yii::$app->params['CeramicTileToBrick'];
                // }
                //标类价
                $totalPrice = $goods['amount'] + $goods['superAmount'];
                // 计算新选材砖上墙加价
                $arr['specStr'] = $goods['spec'];
                $newSpec = QuantityCheckManager::changeSpec($arr,$goods['cateId']);
                $arrSpec = explode('*', $newSpec);
                if(count($arrSpec) >= 2
                    && $arrSpec[0] >= $minSpecArr[0] && $arrSpec[1] >= $minSpecArr[1]
                    && $goods['checkArea'] == Constant::CUSTOM_CLASS_CeramicTile
                    && $goods['cateId'] == GoodsClassTable::GOODS_CLASS_BRICK)
                {
                    $area = $goods['otherData']['perimeter'] * $goods['otherData']['height'] * 0.000001;
                    $newBrickAmount = $area *  Yii::$app->params['CeramicTileToBrick'];
                    $otherAmount['brickAmount'] += $newBrickAmount;
                }
                // } elseif($oldBrickAmount > 0){
                //     $otherAmount['brickAmount'] -= $oldBrickAmount;
                // }
                $one['diffMoney'] = $totalPrice - $oldTotalPrice;

                if(!is_array($one['otherData']) || is_array($one['goodsPrice'])){
                    continue;
                }

                $otherData  = $goods['otherData'];
                $goodsPrice = $goods['goodsPrice'];

                // 计价
                $superQuantity = $goods['superQuantity'];
                $goodsArea     = $goods['goodsArea'];
                $cateId        = $goods['cateId'];


                $goodsInfo     = array_merge($otherData, [
                    'subOrderId'=> $goods['id'],
                    'type'      => $goods['cateId'],
                    'cateId'    => $goods['cateId'],
                    'brandId'   => $goods['brandId'],
                    'checkArea' => $goods['checkArea'],
                    'cate'      => $goods['cate'],
                    'brand'     => $goods['brand'],
                    'area'      => empty($otherData['area']) ? 0 : $otherData['area'],
                    'goodsArea' => $goodsArea,
                    'specStr'   => $goods['spec'],
                ]);
                $superAmount = 0;

                //计算超量价和其他价
                $class = $this->_accountClass($this->type[$cateId]['class']);
                if($class !== false){
                    $account = new AccountingPrice($class);
                    $res     = $account->amount($goodsInfo,$superQuantity);
                    if(is_array($res)){
                        foreach ($res as $key =>$price) {
                            $superAmount += $price;
                        }
                    }
                }
                $totalPrice += $superAmount;
                $one['diffMoney'] = $totalPrice - $oldTotalPrice;
            }
        }
        // 大自然木地板不足25方需要加收费用
        $allAmount    = json_decode($order->orderCommon->allAmount, true);
        $oldNatureFee = isset($allAmount['natureFee']) ? $allAmount['natureFee'] : 0;
        $rejectedOrders = OrderRejected::find()->where(['orderId' => $order->orderId])->all();
        if($rejectedOrders != null) {
            foreach ($rejectedOrders as $key => $value) {
                $amount = json_decode($value->otherAmount, true);
                $oldNatureFee += isset($amount['natureFee']) ? $amount['natureFee'] : 0;
            }
        }
        if($natureFloorArea > 0 && $natureFloorArea < Yii::$app->params['NatureWoodMin']) {
            $otherAmount['natureFee'] = Yii::$app->params['NatureWoodAdd'];
        }
        $otherAmount['natureFee'] = $otherAmount['natureFee'] - $oldNatureFee;
        return $otherAmount;
    }

} 