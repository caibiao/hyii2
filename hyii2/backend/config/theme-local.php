<?php
/**
 * Created by PhpStorm.
 * User: hzhuangxianan
 * Date: 2016/11/23
 * Time: 10:56
 */
$config = [
    'components' => [
        //主题模板配置
        'view' => [
            'theme' => [
                'pathMap' => ['@backend/views' => '@backend/themes/ace/views'],
                'baseUrl' => '@web/themes/ace',
            ],
        ],
    ],
];
return $config;