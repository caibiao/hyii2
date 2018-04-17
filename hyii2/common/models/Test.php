<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
/**
 * This is the model class for table "test".
 *
 * @property integer $id
 * @property string $price
 * @property string $is_active
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test';
    }

    public function behaviors()
    {
      return [
          'typecast' => [
              'class' => AttributeTypecastBehavior::className(),
              'attributeTypes' => [
                  'price' => AttributeTypecastBehavior::TYPE_FLOAT,
                  'is_active' => AttributeTypecastBehavior::TYPE_BOOLEAN,
              ],
              'typecastAfterValidate' => true,
              'typecastBeforeSave' => false,
              'typecastAfterFind' => false,
          ],
      ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'is_active' => 'Is Active',
        ];
    }
}
