<?php

namespace abiao\datadict\models\searchs;

use abiao\datadict\models\DatadictDetail;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DatadictDetailSearch represents the model behind the search form about `abiao\datadict\models\DatadictDetail`.
 */
class DatadictDetailSearch extends DatadictDetail
{

    public $name;
    public $fid;
    public $pid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'fid'], 'integer'],
            [['pid'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DatadictDetail::find()->with('father');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->orderBy('fid asc, orders asc');

        // grid filtering conditions
        $query->andWhere([
            'pid' => $this->pid,
        ]);

        return $dataProvider;
    }
}
