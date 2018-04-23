<?php

namespace Service\DataOp\Models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Service\DataOp\Models\Dept;

/**
 * Search represents the model behind the search form about `Service\DataOp\Models\Dept`.
 */
class DeptSearch extends Dept
{
    const PAGESIZE = 50;
    public $maxCoins;
    public $minCoins;

    public $query;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no', 'name'], 'string'],
            [['coins'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'maxCoins' => '可用网币',
            'minCoins' => '可用网币',
        ];
    }

    /**
     * 搜索查询
     * @return ActiveDataProvider
     */
    public function search()
    {
        $this->getCondition();

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => self::PAGESIZE,
            ],
        ]);
        return $dataProvider;
    }


    /**
     * 搜索查询
     * @return object
     */
    private function getCondition()
    {
        $this->query = Dept::find()
            ->where(['{{dept}}.isDel' => 0]);


        $this->query->andFilterWhere([
            'no' => $this->no,
            'name' => $this->name,

        ]);
        $minCoins=$this->minCoins;
        $maxCoins=$this->maxCoins;

        $this->query->andFilterWhere(['like', '{{%dept}}.no', $this->no])
            ->andFilterWhere(['like', '{{dept}}.name', $this->name])
            ->andFilterWhere(['between', '{{dept}}.coins', $minCoins, $maxCoins]);

        return $this->query;
    }
}
