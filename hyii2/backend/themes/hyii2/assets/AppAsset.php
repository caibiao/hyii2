<?php

namespace backend\themes\hyii2\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/hyii2';
    public $css = [
        'css/font-awesome-4.4.0/css/font-awesome.css',
        'css/quirk.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/toggles.js',
        'js/quirk.js',
        'js/site.js',
        'js/table_base.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
