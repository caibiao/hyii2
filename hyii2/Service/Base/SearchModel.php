<?php
/**
 * 搜索模型基类
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

use yii\data\ActiveDataProvider;

abstract class SearchModel extends Model implements SearchInterface
{
    /**
     * @var \yii\db\ActiveQuery
     */
    public $query;
    public $orderBy;
    public $pageSize;
    public static $filterList;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function init()
    {
        $this->pageSize = ['pageSize' => self::PAGESIZE];
    }

    /**
     * 排序
     * @usage http://www.yiiframework.com/doc-2.0/yii-db-querytrait.html#orderBy()-detail
     * @param  $columns
     * @return $this the query object itself
     */
    public function orderBy($columns = [])
    {
        if (isset($this->query)) {
            $this->query->orderBy($columns);
            return $this;
        }
    }

    /**
     * 单页数据数量
     * @param  $limit
     * @return $this
     */
    public function pageSize($limit = '')
    {
        if (isset($this->pageSize)) {
            if ($limit === false) {
                $this->pageSize = false;
            } else {
                $limit = !empty($limit) && is_numeric($limit) ? $limit : self::PAGESIZE;
                $this->pageSize = ['pageSize' => $limit];
            }
            return $this;
        }
    }

    /**
     * @inheritdoc
     */
    public function search()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'sort' => false,
            'pagination' => $this->pageSize,
        ]);
        return $dataProvider;
    }

    // abstract public function search();
    abstract public function statusList();

    public function getFilterValues()
    {
        return [];
    }

    /**
     * 根据搜索字段获取搜索条件对应的数组
     * @param $filterKey
     * @return array
     */
    public function getFilterValue($filterKey)
    {
        if (empty(static::$filterList)) {
            static::$filterList = static::getFilterValues();
        }
        return isset(static::$filterList[$filterKey]) ? static::$filterList[$filterKey] : [];
    }
}
