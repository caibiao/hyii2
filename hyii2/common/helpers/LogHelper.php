<?php
/**
 * 日志
 * Created by PhpStorm.
 * User: liu
 * Date: 2016/7/15
 * Time: 9:07
 */
namespace Anlewo\Common\helpers;

use Anlewo\Common\Models\BaseOrders;

class LogHelper
{
    /**
     * 遍历判断属性数据有没有变化,必须在保存之前使用
     * usage LogHelper::getRemark($obj, '自定义文字', 0);
     * @param object $obj 对象
     * @param string $str 文字描述
     * @param integer $type 是否开启添加 0否 1是
     * @return string
     */
    public static function getInsteadRemark($obj, $str = '更改', $type = 0,$orderModel=null)
    {
        $remark = '';

        if (count($obj->oldAttributes) <= count($obj->Attributes)) {
            foreach ($obj->Attributes as $key => $val) {
                if (empty($obj->oldAttributes[$key]) || !self::getFilter($key) || !self::F2Bigone($key,$orderModel)) {continue;}

                if ($obj->oldAttributes[$key] != $val) { //判断值是否有变化

                    $usedVal = empty($obj->oldAttributes[$key]) ? '空' : $obj->oldAttributes[$key]; //旧数据
                    $newVal = empty($val) ? '空' : $val;                                            //新数据
                    $Txt = self::getCustomText($key, $obj);                                          //文字描述
                    $newVal = self::getStateMsg($key, $newVal, $obj);
                    $usedVal = self::getStateMsg($key, $usedVal, $obj);

                    if (strpos($Txt, '时间') && !empty($val) && !empty($obj->oldAttributes[$key]) && is_numeric($val)) {
                        $usedVal = date('Y-m-d H:i:s', $obj->oldAttributes[$key]);
                        $newVal = date('Y-m-d H:i:s', $val);
                    }

                    if ($key == 'deliveryAddress') {
                        $usedVal = self::getAddress($obj->oldAttributes[$key]);
                        $newVal = self::getAddress($val);
                    }

                    //户型结构
                    if ($key == 'structInfo') {
                        $usedVal = self::getStructure($obj->oldAttributes[$key]);
                        $newVal = self::getStructure($val);
                    }

                    if (!self::is_serialize($val) && !is_array($val) && $val !== null) { //判断值是否为序列化和json
                        $remark .= $str . $Txt . '"' . $newVal . '",&nbsp;&nbsp;';
                        //                        $remark .= $type == 0 ? '&nbsp;(' . $Txt . '由' . $usedVal . $str . $newVal . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '(' . $str . $Txt . $newVal . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                    }
                }

                //是否开启添加
                if ($type == 1 && !empty($val)) {

                    $newVal = empty($val) ? '空' : $val;   //新数据
                    $Txt = self::getCustomText($key, $obj); //文字描述
                    $newVal = self::getStateMsg($key, $newVal, $obj);

                    if (strpos($Txt, '时间') && !empty($val) && is_numeric($val)) {
                        $newVal = date('Y-m-d H:i:s', $val);
                    }

                    if ($key == 'deliveryAddress') {
                        $newVal = self::getAddress($val);
                    }

                    //户型结构
                    if ($key == 'structInfo') {
                        $newVal = self::getStructure($val);
                    }

                    if (!self::is_serialize($val) && !is_array($val)) { //判断值是否为序列化和数组
                        $remark .= $str . $Txt . '"' . $newVal . '",&nbsp;&nbsp;';
                    } else if (is_array($val)) {
                        $newVal = json_encode($val, JSON_UNESCAPED_UNICODE);
                        $remark .= $str . $Txt . '"' . $newVal . '",&nbsp;&nbsp;';
                    }
                }
            }
        } else {
            foreach ($obj->oldAttributes as $key => $val) {
                if (empty($obj->oldAttributes[$key]) || !self::getFilter($key) || !self::F2Bigone($key,$orderModel)) {continue;}

                $usedVal = empty($obj->Attributes[$key]) ? '空' : $obj->Attributes[$key]; //旧数据
                $newVal = empty($val) ? '空' : $val;                                      //新数据
                $Txt = self::getCustomText($key, $obj);                                    //文字描述
                $newVal = self::getStateMsg($key, $newVal, $obj);
                $usedVal = self::getStateMsg($key, $usedVal, $obj);

                if ($obj->Attributes[$key] != $val) { //判断值是否有变化

                    if (strpos($Txt, '时间') && !empty($val) && !empty($obj->oldAttributes[$key]) && is_numeric($val)) {
                        $usedVal = date('Y-m-d H:i:s', $obj->oldAttributes[$key]);
                        $newVal = date('Y-m-d H:i:s', $val);
                    }

                    if ($key == 'deliveryAddress') {
                        $usedVal = self::getAddress($obj->oldAttributes[$key]);
                        $newVal = self::getAddress($val);
                    }

                    //户型结构
                    if ($key == 'structInfo') {
                        $usedVal = self::getStructure($obj->oldAttributes[$key]);
                        $newVal = self::getStructure($val);
                    }

                    if (!self::is_serialize($val) && !self::is_json($val) && !is_array($val)) { //判断值是否为序列化和json
                        $remark .= $str . $Txt . '"' . $newVal . '",&nbsp;&nbsp;';
                        // $remark .= $type == 0 ? '&nbsp;(' . $Txt . '由' . $usedVal . $str . $newVal . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '(' . $str . $Txt . $newVal . ')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
            }
        }
        return $remark;
    }

    /**
     * 获取收货地址
     * @param $val
     * @return string
     */
    public static function getAddress($val)
    {
        if (is_array($val)) {
            $address = $val;
        } else {
            $address = json_decode($val, true);
        }

        if (isset($address) || $val == null) {
            return '';
        } else {
            if (empty($address['areaInfo'])) {
                return '';
            } else {
                return $address['areaInfo'] . $address['address'] . ' 联系电话：' . $address['phone'] . ' ';
            }
        }
    }

    /**
     * 处理户型结构
     * @param $val
     * @return bool|string
     */
    public static function getStructure($val)
    {
        if (empty($val)) {return '';}
        $structure = explode(',', $val);
        $structureStr = $structure[0] . '房 ';
        $structureStr .= $structure[1] . '厅 ';
        $structureStr .= $structure[2] . '卫 ';
        $structureStr .= $structure[3] . '厨 ';
        $structureStr .= $structure[4] . '阳台 ';
        return $structureStr;
    }

    /**
     * F2B订单过滤日志不输出
     * @param $key
     */
    public static function F2Bigone($key,$orderModel){
        if(isset($orderModel->orderType) && $orderModel->orderType==BaseOrders::ORDER_TYPE_F2B_IPAD){
            if(!in_array($key,['num','superQuantity','superAmount'])){
                return true;
            }else{
                return false;
            }
        }
        return true;

    }
    /**
     * 过滤字段不输出
     * @param $key
     * @return bool
     */
    public static function getFilter($key)
    {
        if (in_array($key,
            [
                'buyerName', 'buyerMobile', 'measureInfo', 'structInfo', 'earnest', 'structPic', 'houseCardPic', 'goodsAreaName',
                'goodsName', 'cate', 'brand', 'model', 'num', 'remark', 'payTime', 'confirmTime', 'procureState', 'orderGoodsType',
                'superQuantity', 'superAmount', 'orderAmount', 'discountAmount', 'earnestState', 'customDiscountAmount',
                'expectedTime', 'procureState', 'purchaseGatherNum', 'purchaseScatterNum', 'paymentId', 'payedAmount',
            ])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 重置文字
     * @param $key
     * @param $obj
     * @return string
     */
    public static function getCustomText($key, $obj)
    {
        $txt = '';
        switch ($key) {
            case 'payedType':
                $txt = '付款类型';
                break;
            case 'payedStatus':
                $txt = '状态';
                break;
            case 'isInvoice':
                $txt = '是否已开发票';
                break;
            case 'earnestState':
                $txt = '定金状态';
                break;
            case 'orderFrom':
                $txt = '来源';
                break;
            case 'procureState':
                $txt = '采购状态';
                break;
            case 'measureInfo':
                $txt = '房屋面积';
                break;
            default:
                $txt = $obj->getAttributeLabel($key);
                break;
        }
        return $txt;
    }

    /**
     * 过滤字段
     * @param $key
     * @param $val
     * @param $obj
     * @return string
     */
    public static function getStateMsg($key, $val, $obj)
    {

        if (empty($val)) {return '';}
        $txt = '';
        switch ($key) {
            case 'orderType':
                $txt = '主材包';
                break;
            case 'orderState':
                $txt = '客已付';
                break;
            case 'payedStatus':
                $txt = '待支付';
                break;
            case 'floadType':
                switch ($val) {
                    case 1:
                        $txt = '红标';
                        break;
                    case 2:
                        $txt = '绿标';
                        break;
                    case 9:
                        $txt = '蓝标';
                        break;
                    case 0:
                        $txt = '橙标';
                        break;
                }
                break;
            case 'payedType':
                $txt = $obj->typeMsg;
                break;
            case 'earnestState':
                $txt = $obj->orderStateMsg;
                break;
            case 'orderFrom':
                $txt = $obj->orderFromMsg;
                break;
            case 'procureState':
                $txt = '未采购';
                break;
            case 'orderGoodsType':
                $txt = $val == 1 ? '标准品' : '非标准';
                break;
            default:
                $txt = $val;
                break;
        }
        return $txt;
    }

    /**
     *
     * @param $string
     * @return bool
     */
    public static function is_json($string)
    {
        $jsonStr = json_decode($string, true);
        if ($jsonStr) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 判断值是否为序列化
     * @param $string
     * @return bool|mixed
     */
    public static function is_serialize($string)
    {
        $unStr = @unserialize($string);
        if ($unStr) {
            return true;
        } else {
            return false;
        }
    }

}
