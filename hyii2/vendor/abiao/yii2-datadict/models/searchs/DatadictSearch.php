<?php

namespace abiao\datadict\models\searchs;

use abiao\datadict\models\Datadict;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DatadictSearch represents the model behind the search form about `abiao\datadict\models\Datadict`.
 */
class DatadictSearch extends Model
{

    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Datadict::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
