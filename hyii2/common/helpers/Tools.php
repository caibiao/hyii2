<?php

namespace Anlewo\Common\helpers;

use Yii;

/**
 * 工具函数类
 */
class Tools
{
    /**
     * 返回格式化的时间
     * @param  int $date 时间戳
     * @param  int $format 需要格式(1：格式化到时间的分钟数，2：格式化到日期，3：格式化到月份)
     * @return string
     */
    public static function dateFormat($date, $format = 1)
    {
        if (!empty($date) && is_int($date)) {
            switch ($format) {
                case 1:
                    $format = 'Y/m/d H:i';
                    break;
                case 2:
                    $format = 'Y/m/d';
                    break;
                case 3:
                    $format = 'Y/m';
                    break;
                default:
                    $format = 'Y/m/d';
                    break;
            }
            return date($format, $date);
        } else {
            return '---';
        }
    }

    /**
     * 样式
     * @param null $type
     * @param null $placeholder
     * @return array
     */
    public static function style($type = null, $placeholder = null)
    {
        switch ($type) {
            case 'readonly':
                return $options = [
                    'template' => '{label}<div class="col-xs-6">{input}</div>{hint}{error}',
                    'errorOptions' => ['tag' => 'span', 'class' => 'col-xs-4 help-block'],
                    'labelOptions' => ['class' => 'col-xs-2 control-label text-right'],
                    'inputOptions' => ['class' => 'form-control', 'readonly' => 'true', 'placeholder' => $placeholder],
                    'options' => ['class' => "form-group col-lg-12"],
                ];
                break;
            default:
                return $options = [
                    'template' => '{label}<div class="col-xs-6">{input}</div>{hint}{error}',
                    'errorOptions' => ['tag' => 'span', 'class' => 'col-xs-4 help-block'],
                    'labelOptions' => ['class' => 'col-xs-2 control-label text-right'],
                    'inputOptions' => ['class' => 'form-control', 'placeholder' => $placeholder],
                    'options' => ['class' => "form-group col-lg-12"],
                ];
                break;
        }
    }

    /**
     * 返回一个数组在另一个数数组的键值
     * @param $needle  搜索数组
     * @param $haystack 被搜索数组
     */
    public static function multi_search($haystack, $needle)
    {
        if (empty($needle) || empty($haystack)) {
            return false;
        }

        foreach ($haystack as $key => $value) {
            $exists = true;
            foreach ($needle as $skey => $svalue) {
                $exists = ($exists && isset($haystack[$key][$skey]) && $haystack[$key][$skey] == $svalue);
            }
            if ($exists) {return $key;}
        }

        return false;
    }

    /**
     * 获取ipad发布版本号
     * @return array 状态码,版本号
     */
    public static function getVersionByJson()
    {
        $arrVer = [];
        $url = Yii::$app->params['versionUrl'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不做输出，做变量存储
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $output = curl_exec($ch);
        $resCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //响应状态吗200成功
        curl_close($ch);
        if ($output == false) {
            $arrVer['code'] = $resCode;
            $arrVer['version'] = '';
            return $arrVer;
        }
        $arrVer['code'] = $resCode;
        $arrVer['version'] = trim($output);
        return $arrVer;
    }

    /**
     * 是否提示版本更新
     * @return bool
     */
    public static function updateRemind()
    {
        $sysVersion = '';
        $version = self::getVersionByJson();
        if ($version['code'] == 200) {
            $sysVersion = substr($version['version'], 0, 5);
        }
        $sysVersion = substr($sysVersion, 0, 5);
        $user = Yii::$app->user->identity;
        //是否点击过 "不再提示"
        if (substr(strtoupper($user->version), 0, 5) == strtoupper($sysVersion)) {
            return false;
        }
        $sessionKey = 'versionNotRemind' . $user->id;
        //是否点击过 "知道了"
        if (Yii::$app->session->get($sessionKey)) {
            return false;
        }
        //提示更新
        return true;
    }

    /**
     *数字金额转换成中文大写金额的函数
     *String Int $num 要转换的小写数字或小写字符串
     *return 大写字母
     *小数位为两位
     **/
    public static function num_to_rmb($num)
    {
        $num = str_replace(",", "", $num);
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $arr = explode('.', $num);
            $num = $arr[0];
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        if (empty($c)) {
            return "零元";
        } else {
            return $c;
        }
    }

    /**
     * 设置数组默认值
     * @param  array $array   数组
     * @param  string $key     键值
     * @param  string $default 缺省值
     * @return string
     */
    public static function setDefalutValue($array, $default = '空值')
    {
        if (!empty($array) && is_array($array)) {
            foreach ($array as $key => $value) {
                if (!is_array($value)) {
                    $array[$key] = empty($value) ? $default : $value;
                }
            }
        }
        return $array;
    }
    /**
     * 订单编号只显示“下单日期”+“-”+“3位该天订单序号”，比如“170619-002”
     */
    public static function splitOrderSn($orderSn)
    {
        $shortOrderSn = '';
        if (!empty($orderSn)) {
            $shortOrderSn = substr($orderSn, strlen($orderSn) - 9, 9);
        }
        return $shortOrderSn;
    }
}
