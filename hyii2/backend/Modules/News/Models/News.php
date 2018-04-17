<?php
namespace backend\Modules\News\Models;
use backend\Modules\News\Models\NewsDetail;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "datadict".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $len
 * @property string $name
 * @property string $position
 * @property string $featurePos
 * @property string $remark
 * @property integer $orders
 * @property string $operator
 * @property string $datetime
 */

class News extends ActiveRecord
{
    protected static $_typeList;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['datetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['datetime'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['name'], 'unique'],
            [['name'], 'required'],
            [['name', 'remark', 'operator'], 'string', 'max' => 200],
            [['orders'], 'integer', 'max' => 11],
            [['name', 'remark'], 'trim'],
            [['name'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'remark' => '描述',
            'orders' => '排序',
            'operator' => '开发人员',
            'datetime' => '创建时间',
        ];
    }

    /**
     * 获取字段子级别明细
     * @return \yii\db\ActiveQuery
     */
    public function getNewsDetail()
    {
        return $this->hasMany(datadictDetail::className(), ['pid' => 'id'])->andWhere(['flag' => '0']);
    }

    /**
     * @inheritdoc获取所有字段名称
     *
     */
    public function getColumnList()
    {
        $list = $this->find()
            ->where(1)
            ->select(['id', 'name'])
            ->asArray()
            ->all();
        return $list;
    }
}
