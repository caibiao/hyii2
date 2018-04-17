<?php

namespace abiao\datadict\models;

use abiao\datadict\models\Datadict;
use Yii;

/**
 * This is the model class for table "manage_column_detail".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property string $fid
 * @property string $remark
 * @property integer $orders
 * @property string $code
 * @property integer $flag
 * @property string $datetime
 * @property string $operator
 */
class DatadictDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datadict_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'orders', 'flag', 'fid'], 'integer'],
            [['datetime'], 'safe'],
            [['name', 'remark', 'operator'], 'string', 'max' => 200],
            [['name', 'pid', 'remark'], 'filter', 'filter' => 'trim'],
            ['name', 'unique', 'targetAttribute' => ['name', 'pid'], 'comboNotUnique' => '{value}已经存在'],
            [['pid', 'name'], 'required', 'message' => '{attribute}不能为空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '字段',
            'name' => '名称',
            'fid' => '父级',
            'remark' => '描述',
            'orders' => '排序',
            'flag' => '启用标记',
            'datetime' => '添加时间',
            'operator' => '操作人',
        ];
    }

    public function getcolumn()
    {
        //找出父记录中的字段归属
        return $this->hasOne(Datadict::className(), ['id' => 'pid']);
    }

    //找出父级记录
    public static function getfatherRecord($fid)
    {
        return static::find()->Where(['id' => $fid])->one();
    }

    public function getFather()
    {
        return $this->hasOne(self::className(), ['id' => 'fid']);
    }

    /**
     * 更新字段状态
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function editStatus($data)
    {
        if (empty($data)) {
            throw new \Exception('参数为空');
        }
        $model = self::findOne($data['id']);
        if (empty($model)) {
            throw new \Exception('字段不存在');
        }

        if (!$model->load(['DatadictDetail' => $data]) || !$model->save()) {
            throw new \Exception($model->getErrors());
        }

        return true;
    }

}
