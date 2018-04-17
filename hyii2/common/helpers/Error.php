<?php

namespace Common\helpers;

use yii\helpers\Url;

class Error extends Url
{
    
    public static function toStr($modelError)
    {
        $errStr = '包含以下错误：';
        foreach ($modelError as $name => $err) {
            $errStr .= implode("\n\t", $err);
            $errStr .= "\n\n";
        }
        return $errStr;
    }

}