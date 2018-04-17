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
                'pathMap' => ['@backend/views' => '@backend/themes/hyii2/views'],
                'baseUrl' => '@web/themes/hyii2',
            ],
        ],
    ],
];
return $config;