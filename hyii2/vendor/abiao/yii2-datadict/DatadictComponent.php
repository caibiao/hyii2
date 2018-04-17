<?php

namespace abiao\datadict;

use abiao\datadict\models\Datadict;
use abiao\datadict\models\DatadictDetail;
use yii\base\Component;
use yii\caching\Cache;
use yii\db\Connection;
use yii\di\Instance;

class DatadictComponent extends Component
{

    public $cache;
    public $db = 'db';
    public $cacheKey = 'abiao_datadict_cache';

    protected $data;

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }

    /**
     * 获取数据字典数据
     * @param  string $id
     * @return array
     * [
     *      '26' => [
     *          [
     *              'id' => 10,
     *              'name' => '主材包',
     *              'orders' => '',
     *          ],
     *      ],
     *  ]
     */
    public function getDatadict($id)
    {
        $data = $this->getAll();
        return $data[$id];
    }

    /**
     * 获取数据字典数据
     * @param  string $id
     * @return array
     * [
     *      '26' => [
     *          [
     *              'id' => 10,
     *              'name' => '主材包',
     *              'orders' => '',
     *          ],
     *      ],
     *  ]
     */
    public function getSingleData($id)
    {
        //找出父级字段各启用取值
        $detail = DatadictDetail::find()
            ->where(['id' => $id])
            ->one();
        return $detail;
    }

    /**
     * 获取父级字段的各取值
     * @param  string $id
     * @return array
     * [
     *     ['11' => '线下广告'],
     *     ['12' => '合作资源'],
     *          ......
     * ]
     */
    public function getFatherDatadict($id)
    {
        //找出父级字段各启用取值
        $detail = DatadictDetail::find()
            ->where(['pid' => $id, 'flag' => 0, 'fid' => null])
            ->orderBy('orders asc')
            ->all();
        $data = [];
        foreach ($detail as $key => $baseValue) {
            $data[$baseValue->id] = $baseValue->name;
        }
        return $data;
    }

    /**
     * 获取来源渠道多选项各取值
     * @param  string $id
     * @return array
     * [
     * "来源渠道"=>
     *     [
     *         "026"=>"客户来源渠道",
     *         "027"=>"客户来源渠道",
     *         "028"=>"客户来源渠道",
     *         "029"=>"客户来源渠道",
     *     ]
     * ]
     */
    public function getSourceDatadict($id)
    {
        //找出所有父级字段
        $datas = [];
        //找出父级字段各取值
        $detail = DatadictDetail::find()
            ->where(['pid' => $id, 'flag' => 0, 'fid' => null])
            ->orderBy('orders asc')
            ->all();
        foreach ($detail as $key => $value) {
            $data[$value->name] = [];
            $fid = $value->id;
            //找出父级字段各取值
            $detail = DatadictDetail::find()
                ->where(['fid' => $fid, 'flag' => 0])
                ->orderBy('orders asc')
                ->all();
            foreach ($detail as $key => $baseValue) {
                $data[$value->name][$baseValue->id] = $baseValue->name;
            }
        }
        return $this->data = $data;
    }

    /**
     * 获取来源渠道多选项各取值
     * @param  string $id
     * @return array
     * [
     * "来源渠道"=>
     *     [
     *         "026"=>"客户来源渠道",
     *         "027"=>"客户来源渠道",
     *         "028"=>"客户来源渠道",
     *         "029"=>"客户来源渠道",
     *     ]
     * ]
     */
    public function showSourceDatadict($id)
    {
        //找出所有父级字段
        $datas = [];
        //找出父级字段各取值
        $detail = DatadictDetail::find()
            ->where(['pid' => $id, 'fid' => null])
            ->orderBy('orders asc')
            ->all();
        foreach ($detail as $key => $value) {
            $data[$value->name] = [];
            $fid = $value->id;
            //找出父级字段各取值
            $detail = DatadictDetail::find()
                ->where(['fid' => $fid])
                ->orderBy('orders asc')
                ->all();
            foreach ($detail as $key => $baseValue) {
                $data[$value->name][$baseValue->id] = $baseValue->name;
            }
        }
        return $this->data = $data;
    }

    /**
     * 获取所有字典数据
     * @return array
     */
    public function getAll()
    {
        $this->loadFromCache();
        $this->loadFormDB();
        return $this->data;
    }

    /**
     * 从缓存中获取所有字典数据
     * @return void
     */
    protected function loadFromCache()
    {
        if ($this->data !== null || !$this->cache instanceof Cache) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        if (is_array($data)) {
            $this->data = $data;
            return;
        }

        $this->data = $this->loadFormDB();
        $this->cache->set($this->data);
    }

    /**
     * 从数据库中获取所有字典数据
     * @return array
     */
    protected function loadFormDB()
    {
        if ($this->data !== null) {
            return;
        }
        //找出所有父级字段
        $data = [];
        $datas = [];
        $list = Datadict::find()
            ->orderBy('orders asc')
            ->all();
        foreach ($list as $key => $value) {
            //找出所有字段
            $detail = DatadictDetail::find()
                ->where(['pid' => $value->id, 'flag' => 0])
                ->orderBy('orders asc')
                ->all();
            foreach ($detail as $key => $baseValue) {
                $data[$baseValue->id] =$baseValue->name ;
            }
            $datas[$value->id]=$data;
        }
        return $this->data = $datas;
    }

    /**
     * 刷新缓存
     * @return void
     */
    public function refresh()
    {
        if ($this->cache !== null) {
            $this->cache->delete(self::CACHE_KEY);
            $this->loadFromCache();
        }
    }
}
