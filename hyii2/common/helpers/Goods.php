<?php
/**
 * Goods
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 * @Author linmengfeng@anlewo.com
 * @Date 2016/5/9
 */
namespace Anlewo\Common\helpers;

use Anlewo\Common\Anlewo\Constant;
use Anlewo\Common\Models\BaseSubOrdersGoods;
use Anlewo\SDK\Bridge\GoodsClassTable;
use Anlewo\SDK\Bridge\GoodsTable;
use Anlewo\SDK\Bridge\SpecValueTable;

/**
 * 商品操作类
 * @package Anlewo\Common\helpers
 */
class Goods
{

    /**
     * 解析商品属性
     *
     */
    public static function getGoodsAttr(&$info, $goodsAttr, $goodsCustomAttr)
    {
        $i = 0;
        $goods_attr = unserialize($goodsAttr);
        if (is_array($goods_attr) && !empty($goods_attr)) {
            foreach ($goods_attr as $key => $val) {
                $val = array_values($val);
                $info['goods_attrs'][$i]['name'] = isset($val[0]) ? $val[0] : '';
                $info['goods_attrs'][$i]['value'] = isset($val[1]) ? $val[1] : '';
                $i++;
            }
        }

        $goods_custom = unserialize($goodsCustomAttr);
        if (is_array($goods_custom) && !empty($goods_custom)) {
            foreach ($goods_custom as $key => $val) {
                $info['goods_attrs'][$i]['name'] = $val['name'];
                $info['goods_attrs'][$i]['value'] = $val['value'];
                $i++;
            }
        }
    }

    /**
     * 组装商品属性用于SKU切换
     */
    public static function getGoodsSpecList(&$info)
    {
        //欧罗拉商品多规格冗余ID
        $ignore = 567;
        $spec_array = GoodsTable::find()->andWhere(['goods_commonid' => $info['goods_commonid']])
            ->select('goods_spec,goods_id,is_sample')
            ->asArray()
            ->all();
        $spec_list = array();        // 各规格商品地址，js使用
        $spec_list_mobile = array(); // 各规格商品地址，js使用
        foreach ($spec_array as $key => $value) {
            $s_array = unserialize($value['goods_spec']);
            $tmp_array = array();
            if (!empty($s_array) && is_array($s_array)) {
                $s_array_key = array_keys($s_array);
                if (in_array($ignore, $s_array_key)) {
                    unset($info['goods_spec'][$ignore]);
                    unset($s_array[$ignore]);
                }
                foreach ($s_array as $k => $v) {
                    $tmp_array[] = $k;
                }
            }
            //sort($tmp_array);
            $spec_sign = implode('|', $tmp_array);
            $tpl_spec = array();
            $tpl_spec['sign'] = $spec_sign;
            $tpl_spec['goods_id'] = $value['goods_id'];
            $tpl_spec['is_sample'] = $value['is_sample']; // 增加样品值，选样下单模板里面使用判断不是样品的则不显示
            $spec_list[] = $tpl_spec;
            $spec_list_mobile[$spec_sign] = $value['goods_id'];
        }

        $info['spec_list'] = $spec_list;
    }

    /**
     * 根据属性ID获取属性值
     */
    public static function getSpecName($specName, $specValue, $goodsSpec, $index)
    {
        $result = '';
        if (!empty($specName) && !empty($specValue) && !empty($goodsSpec)) {
            if (isset($specValue[$index]) && is_array($specValue[$index])) {
                $specValueIds = array_keys($specValue[$index]);
                foreach ($goodsSpec as $key => $val) {
                    if (in_array($key, $specValueIds)) {
                        return $val;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 根据自定义名称获取值
     */
    public static function getAttrValue($goodsAttr, $name)
    {
        $result = '';
        if (!empty($goodsAttr)) {
            foreach ($goodsAttr as $key => $val) {
                if ($val['name'] == $name) {
                    $val = array_values($val);
                    return isset($val[1]) ? $val[1] : '';
                }
            }
        }
        return $result;
    }

    /**
     * 找出颜色
     * @param $gcId
     * @param $goodsSpec
     * @return string
     */
    public static function findGoodsColor($gcId, $goodsSpec)
    {
        $key = '颜色';
        $return = '';
        if (!empty($goodsSpec)) {
            $list = SpecValueTable::getList($key);
            foreach ($list as $item) {
                if (!empty($gcId) && $gcId != $item['gc_id']) {
                    continue;
                }
                $id = $item['sp_value_id'];
                if (isset($goodsSpec[$id])) {
                    return $goodsSpec[$id];
                }
            }

            foreach ($goodsSpec as $k => $val) {
                $ipos = stripos($k, $key);
                if ($ipos !== false) {
                    return $val;
                }
            }
        }

        return $return;
    }

    /**
     * @param        $key
     * @param        $gcId  已经没用
     * @param        $goodsSpec
     * @param string $specName
     * @param string $specValue
     *
     * @return string
     */
    public static function findGoodsSpecValue($key, $gcId, $goodsSpec, $specName = "", $specValue = "")
    {
        if (empty($key)) {
            return '';
        }

        if (!empty($specName) && !empty($specValue)) {
            //根据商品库的$specName ，$specValue 进行搜索
            foreach ($specName as $id => $item) {
                $ipos = stripos($item, $key);
                if ($ipos !== false) {
                    if (!empty($specValue[$id]) && is_array($specValue[$id])) {
                        foreach ($specValue[$id] as $curk => $val) {
                            if (isset($goodsSpec[$curk])) {
                                return $goodsSpec[$curk];
                            }
                        }
                    }
                }
            }
        }

        return '';
    }

    /**
     * 获取规格转化数据
     * @param  integer $cateId 商品分类
     * @param  string $specStr 商品规格
     * @return float
     */
    public static function getPackageNum($cateId, $specStr)
    {
        $package = 1;
        if (in_array($cateId, Constant::$goodsNeedCount)) {
            // 木地板配件的转角不需要参与计算
            if (strpos($specStr, 'cm')) {
                return $package;
            }
            $specStr = preg_split("/[*xX×]/", $specStr, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($specStr)) {
                $package = 1;
            } else {
                if (in_array($cateId, [GoodsClassTable::GOODS_CLASS_ANCHOR, Constant::GOODS_CLASS_FLOOR_PARTS])) {
                    $package = count($specStr) > 1 ? (floatval($specStr[0]) / 1000) : 1;
                } else {
                    $package = (intval($specStr[0]) / 1000) * (intval($specStr[1]) / 1000);
                }
            }
        }
        return $package;
    }

    /**
     * 判断品牌是否属于大自然
     * @param  Int $brandId 品牌ID
     * @return boolean
     */
    public static function isNature($brandId)
    {
        if (in_array($brandId,
            [
                Constant::BRAND_NATURE,
                Constant::BRAND_NATURE_1,
                Constant::BRAND_NATURE_2,
                Constant::BRAND_NATURE_3,
            ]
        )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用于显示的批号/色号
     */
    public static function getReadBatch($batch)
    {
        return $batch != '' && $batch != null ? $batch : '<span class="no-batch-color">未设置</span>';
    }

    /**
     * 通过分类区分商品是否定制品
     * @param  integer $cateId 商品分类id
     * @return boolean
     */
    public static function isCustomGoods($cateId)
    {
        if (in_array($cateId, BaseSubOrdersGoods::getIgnoreClass())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取包装规格显示信息
     * @param float $num 包装数量
     * @param string $unit 包装单位
     * @return string
     */
    public static function getPackage($num, $unit)
    {
        if ($num != 1) {
            return floatval($num) . $unit;
        } else {
            return '---';
        }
    }

    /**
     * 获取客户订购量
     * @param  float $num 数量
     * @param  string $unit 单位
     * @return string
     */
    public static function getCustomOrderNum($num, $unit)
    {
        return floatval($num) . $unit;
    }

    /**
     * 门店订购量显示方法(散片和箱数)
     * @param  integer $cateId 分类ID
     * @param  string $spec 规格
     * @param  array $qtyData 数量信息
     *               $qtyData['num'] 散片数量
     *               $qtyData['unit'] 散片单位
     *               $qtyData['boxNum'] 整箱数量
     *               $qtyData['boxUnit'] 整箱单位
     *               $qtyData['package'] 包装规格
     * @return string
     *  - 包装规格等于1的显示散片数量加单位 例如 1套
     *  - 木地板、吊顶、木地板地脚线、木地板配件显示片数 例如 100㎡(10箱1000片)
     *  - 其他的显示散片+箱数 例如 100片(10箱)
     */
    public static function getOrderNum($cateId, $spec, $qtyData)
    {
        // 因导出时会存在问题(floatval(8.13624))，所以需要通过BC库转换
        $numShow = bcmul(floatval($qtyData['num']), 1, 6) . $qtyData['unit'];
        // 包装规格为1的不显示箱
        if ($qtyData['package'] != 1) {
            $fullInfo = self::getGoodsFullNum($qtyData['num'], $cateId, $spec);
            $bracket = $qtyData['boxNum'] . $qtyData['boxUnit'];
            if (!empty($fullInfo)) {
                $bracket .= '合' . $fullInfo;
            }
            return $numShow . '(' . $bracket . ')';
        } else {
            return $numShow;
        }
    }

    /**
     * 门店订购量显示方法(散片和箱数)
     * @param  integer $cateId 分类ID
     * @param  string $spec 规格
     * @param  array $qtyData 数量信息
     *               $qtyData['num'] integer 整片数量
     *               $qtyData['unit'] string 散片单位
     * @return string
     *  - 包装规格等于1的显示散片数量加单位 例如 1套
     *  - 木地板、吊顶、木地板地脚线、木地板配件显示片数 例如 100㎡(10箱1000片)
     *  - 其他的显示散片+箱数 例如 100片(10箱)
     */
    public static function getOrderFullNumShow($cateId, $spec, $qtyData)
    {
        $qtyData['num'] = self::getSquareNum($qtyData['num'], $cateId, $spec);
        return self::getSingleOrderNum($cateId, $spec, $qtyData);
    }

    /**
     * 门店订购量显示方法(散片数量)
     * @param  integer $cateId 分类ID
     * @param  string $spec 规格
     * @param  array $qtyData 数量信息
     *               $qtyData['num'] 散片数量
     *               $qtyData['unit'] 散片单位
     * @return string
     *  - 木地板、吊顶、木地板地脚线、木地板配件显示片数 例如 100㎡(1000片)
     *  - 其他的显示散片 例如 100片
     */
    public static function getSingleOrderNum($cateId, $spec, $qtyData)
    {
        $numShow = floatval($qtyData['num']) . $qtyData['unit'];
        $fullInfo = self::getGoodsFullNum($qtyData['num'], $cateId, $spec);
        $bracket = '';
        if (!empty($fullInfo)) {
            $bracket .= '(' . $fullInfo . ')';
        }
        return $numShow . $bracket;

    }

    /**
     * 返回片数(散片数量)
     * @param integer $cateId 分类ID
     * @param string $spec 规格
     * @param array $qtyData 数量信息
     *               $qtyData['num'] 散片数量
     *               $qtyData['unit'] 散片单位
     * @return string
     *  - 木地板、吊顶、木地板地脚线、木地板配件显示片数 例如 1000片
     *  - 其他的显示散片 例如 100片
     */
    public static function getSingleOrderSlice($cateId, $spec, $qtyData)
    {
        if (in_array($cateId, Constant::$goodsNeedCount)) {
            $slice = self::getGoodsFullNum($qtyData['num'], $cateId, $spec);
        } else {
            $slice = floatval($qtyData['num']) . $qtyData['unit'];
        }

        return $slice;
    }

    /**
     * 获取木地板、吊顶、地脚线、木地板配件整片数量
     * @param  float $num 数量
     * @param  integer $cateId 分类ID
     * @param  string $spec 属性
     * @return integer
     */
    public static function getGoodsFullNum($num, $cateId, $spec, $returnUnit = true)
    {
        if (in_array($cateId, Constant::$goodsNeedCount)) {
            $single = self::getPackageNum($cateId, $spec);
            $fullNum = intval(bcdiv($num, $single));
            $unit = isset(Constant::$goodsUnitTrans[$cateId]) ? Constant::$goodsUnitTrans[$cateId] : '';
            return $returnUnit ? $fullNum . $unit : $fullNum;
        }
        return '';
    }

    /**
     * 判断是否存在子商品
     * @param  integer  $cateId  分类ID
     * @param  integer  $brandId 品牌ID
     * @return boolean
     */
    public static function hasSubGoods($cateId, $brandId)
    {
        if ($cateId == Constant::GOODS_CLASS_BATHROOM_ARK && $brandId == Constant::BRAND_KELE) {
            return true;
        } elseif (in_array($cateId, Constant::$madeCateValue)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否科勒卡丽浴室柜
     * @param  integer  $cateId  分类ID
     * @param  integer  $brandId 品牌ID
     * @return boolean
     */
    public static function isKeleKali($cateId, $brandId)
    {
        return $cateId == Constant::GOODS_CLASS_BATHROOM_ARK && $brandId == Constant::BRAND_KELE;

    }

    /**
     * 判断是否非科勒卡丽浴室柜
     * @param  integer  $cateId  分类ID
     * @param  integer  $brandId 品牌ID
     * @return boolean
     */
    public static function isNotKeleKali($cateId, $brandId)
    {
        return $cateId == Constant::GOODS_CLASS_BATHROOM_ARK && $brandId != Constant::BRAND_KELE;

    }

    /**
     * 获取木地板、吊顶、地脚线、木地板配件平方数量
     * @param  integer $num 数量
     * @param  integer $cateId 分类ID
     * @param  string $spec 属性
     * @return integer
     */
    public static function getSquareNum($num, $cateId, $spec)
    {
        if (in_array($cateId, Constant::$goodsNeedCount)) {
            $single = self::getPackageNum($cateId, $spec);
            return $num * $single;
        }
        return $num;
    }
}
