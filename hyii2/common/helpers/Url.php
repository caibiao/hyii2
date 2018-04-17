<?php

namespace Anlewo\Common\helpers;

use Yii;
use yii\helpers\Url as YiiUrl;

class Url extends YiiUrl
{
    /**
     * 商品图片路径
     * @param $name
     * @param $storeId
     *
     * @return string
     */
    public static function toGoodsImage($name, $storeId)
    {
        $baseUrl = Yii::$app->params['GoodsImageUrl'];

        return $baseUrl . $storeId . '/' . $name . '@!product-240';
    }

    /**
     * 付款凭证路径
     * @param $name
     * @param $ext
     *
     * @return string
     */
    public static function toPaymentImage($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['privateOpen']) {
            return PrivateOss::signUrl('store' . ltrim($name, '.'));
        } else {
            return $name;
        }
    }

    /**
     * 提货单凭证路径
     * @param $name
     * @param $ext
     *
     * @return string
     */
    public static function toGoodsPickImage($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['privateOpen']) {
            return PrivateOss::signUrl('order' . ltrim($name, '.'));
        } else {
            return $name;
        }
    }

    /**
     * 提货单凭证路径
     * @param $name
     *
     * @return string
     */
    public static function toApprovalImg($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['privateOpen']) {
            return PrivateOss::signUrl('order' . ltrim($name, '.'));
        } else {
            return $name;
        }
    }

    /**
     * 转换用户头像路径
     * @param $name // 头像路径
     */
    public static function toAvatar($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['open']) {
            return Yii::$app->params['imgUrl'] . Yii::$app->id . $name;
        } else {
            return $name;
        }
    }

    /**
     * 提货单凭证路径
     * @param $name
     * @param $ext
     *
     * @return string
     */
    public static function toConfigImage($name, $id = 'store')
    {
        // 判断OSS是否开启
        if (Yii::$app->params['open']) {
            $arr = explode('/', $name);
            isset($arr[5]) && $arr[5] = urlencode($arr[5]);
            return Yii::$app->params['imgUrl'] . $id . implode('/', $arr) . '?' . time();
        } else {
            return $name;
        }
    }

    /**
     * 出库明细对账单附件
     * @param $file
     * @return string
     */
    public static function toOutSubImage($file)
    {
        $attachment = json_decode($file, true);
        if (empty($attachment)) {
            return '';
        }

        if (Yii::$app->params['open']) {
            $name = Yii::$app->params['imgUrl'] . Yii::$app->id . $attachment['path'];
        } else {
            return Yii::getAlias('@Anlewo/Order') . '/web' . $attachment['path'];
        }

        return $name;
    }

    /**
     * 根据字符串匹配截取内容
     * @param $url 路由
     * @param $str 匹配字符串
     * @return string
     */
    public static function toQueryFilter($url, $str)
    {
        $strTrue = strpos($url, $str);
        $resUrl = '';
        if ($strTrue !== false) {
            $resUrl = substr($url, 0, $strTrue - 1);
        }

        return $resUrl;
    }

    /**
     * 退款单附件
     * @param $name
     * @return string
     */
    public static function toRefundFileRoute($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['open']) {
            return Yii::$app->params['imgUrl'] . Yii::$app->id . $name;
        } else {
            return $name;
        }

    }

    /**
     * 退款单附件
     * @param $name
     * @return string
     */
    public static function toDeliveryGoodsFileRoute($name)
    {
        // 判断OSS是否开启
        if (Yii::$app->params['open']) {
            return Yii::$app->params['imgUrl'] . Yii::$app->id . $name;
        } else {
            return $name;
        }

    }
}
