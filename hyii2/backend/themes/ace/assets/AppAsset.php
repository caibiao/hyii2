<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\themes\ace\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/ace';
    public $css = [
        'css/font-awesome-4.4.0/css/font-awesome.css',
        'css/font-awesome.min.css',     
        'css/ace.min.css', 
        'css/ace-rtl.min.css',
        'css/ace-skins.min.css',
        'css/site.css',
    ];
    
    public $js = [
        'js/anlewo-utils.js',
        'js/jquery.pin.js',
        'js/jquery.spinner.js',
        'js/operation.js',
        'js/fixeHead.js',
        'js/anlewo-modal.js',
        'js/ace-extra.min.js',
        'js/typeahead-bs2.min.js',
        'js/ace-elements.min.js',
        'js/ace.min.js',
        'js/site.js'
    ];
      
    public $depends = [
       'yii\web\YiiAsset',
       'yii\bootstrap\BootstrapAsset',
    ];

}
