<?php
/**
 * 货车管理搜索模型
 */
namespace backend\Modules\Vehicles\models;
use common\Models\Vehicles;
use yii\data\ActiveDataProvider;

class Search extends Vehicles
{

    const PAGESIZE = 10;

    public $plateNum;     // 车牌号
    public $ton;          // 吨位
    public $userName;     // 司机名称
    public $mobile;       // 联系电话
    public $idCard;       // 身份证号

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plateNum', 'userName', 'mobile', 'idCard'], 'string'],
            [['ton'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
              'plateNum'     => '车牌号',
              'ton'          => '吨位',
              'userName'     => '司机名称',
              'mobile'       => '联系电话',
              'idCard'       => '身份证号',
         ];
    }

    public function search()
    {

        $query = Vehicles::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => self::PAGESIZE,
            ],
        ]);  

        if(!$this->validate()){
          return $dataProvider;
        }
        
        $order = '{{vehicles}}.created DESC';
        $query->orderBy($order)
              ->andFilterWhere(['isDel'   => 0])
              ->andFilterWhere(['like', 'plateNum', trim($this->plateNum)])
              ->andFilterWhere(['>=', 'ton', trim($this->ton)])
              ->andFilterWhere(['like', 'userName', trim($this->userName)])
              ->andFilterWhere(['like', 'mobile', trim($this->mobile)])
              ->andFilterWhere(['like', 'idCard', trim($this->idCard)]);

        return $dataProvider;
    }
    

}
