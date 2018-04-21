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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'datetime', 'sms_nums', 'award_flag', 'currency_id', 'flag', 'has_coins'], 'integer'],
            [['no', 'name', 'name_en', 'name_ru', 'name_big', 'parent_no', 'country', 'area', 'inner_no', 'role', 'bank', 'account', 'account_name', 'mobile', 'tel', 'address', 'postalcode', 'email', 'user_no', 'intro_no'], 'safe'],
            [['service_proportion', 'consume_proportion', 'coins_web', 'coins', 'stock', 'order_credit', 'goods_credit', 'discount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Dept::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'datetime' => $this->datetime,
            'service_proportion' => $this->service_proportion,
            'consume_proportion' => $this->consume_proportion,
            'coins_web' => $this->coins_web,
            'coins' => $this->coins,
            'sms_nums' => $this->sms_nums,
            'stock' => $this->stock,
            'order_credit' => $this->order_credit,
            'goods_credit' => $this->goods_credit,
            'award_flag' => $this->award_flag,
            'discount' => $this->discount,
            'currency_id' => $this->currency_id,
            'flag' => $this->flag,
            'has_coins' => $this->has_coins,
        ]);

        $query->andFilterWhere(['like', 'no', $this->no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_big', $this->name_big])
            ->andFilterWhere(['like', 'parent_no', $this->parent_no])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'inner_no', $this->inner_no])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'postalcode', $this->postalcode])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'user_no', $this->user_no])
            ->andFilterWhere(['like', 'intro_no', $this->intro_no]);

        return $dataProvider;
    }
}
