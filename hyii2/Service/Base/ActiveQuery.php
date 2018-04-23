<?php
/**
 * ActiveQuery基类
 *
 * @copyright   本软件和相关文档仅限安乐窝和/或其附属公司开发团队内部交流使用，
 *              并受知识产权法的保护。除非公司以适用法律明确授权， 否则不得以任
 *              何形式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、
 *              分发、展示、执行、发布或显示本软件和相关 文档的任何部分。
 * @link        http://www.anlewo.com/
 * @package     Anlewo\Service\Bill
 * @author      林猛锋 linmengfeng@anlewo.com
 * @since       0.1.0
 */

namespace Service\Base;

use yii\db\ActiveQuery as YiiActiveQuery;

class ActiveQuery extends YiiActiveQuery
{
    public $tableName;

    public function prepare($builder)
    {
        $this->andWhere(["$this->tableName.isDel" => 0]);
        return parent::prepare($builder);
    }
}
