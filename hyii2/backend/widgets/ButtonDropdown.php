<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/15 16:21
 */

namespace abiao\Order\Widgets;


use yii\helpers\ArrayHelper;
use abiao\order\components\rbac\Helper;

class ButtonDropdown extends \yii\bootstrap\ButtonDropdown
{
    public function init()
    {
        $this->checkRoute();
        parent::init();
    }

    public static function widget($config = [])
    {
        $config = ArrayHelper::merge([
            'label' => '查看',
            'options' => ['class'=>'btn-default']
        ], $config);
        try {
            return parent::widget($config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 检测路由权限
     */
    public function checkRoute()
    {
        if (!empty($this->dropdown)) {
            foreach ($this->dropdown['items'] as $key => $value) {
                if (!Helper::checkRoute($value['url'][0])) {
                    unset($this->dropdown['items'][$key]);
                }
            }
        }
    }
}