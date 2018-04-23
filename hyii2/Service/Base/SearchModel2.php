<?php
/**
 * 列表搜索模型
 *
 * @copyright   本软件和相关文档仅限安乐窝和/或其附属公司开发团队内部交流使用，
 *              并受知识产权法的保护。除非公司以适用法律明确授权， 否则不得以任
 *              何形式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、
 *              分发、展示、执行、发布或显示本软件和相关 文档的任何部分。
 * @link        http://www.anlewo.com/
 * @package     package name
 * @author      梁铭佳 liangmingjia@anlewo.com
 * @since       0.1.0
 * @date        2017-10-23
 */
namespace Service\Base;

abstract class SearchModel2 extends \yii\base\Model implements SearchInterface
{
    public function formName()
    {
        return '';
    }

    public function getFilterValues()
    {
        return [];
    }

    abstract public function search();
    abstract public function statusList();

}
