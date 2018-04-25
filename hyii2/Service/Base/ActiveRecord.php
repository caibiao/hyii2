<?php
/**
 *
 * @link http://www.anlewo.com/
 * @copyright Copyright (c) 2015 Anlewo Ltd
 * @license 广东安乐窝网络科技有限公司
 * @author liangmingjia@anlewo.com
 * @date 2016-1-30
 */

namespace Service\Base;

use Service\Exception\CanNotDeleteException;
use Service\Traits\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends \yii\db\ActiveRecord
{
    use BaseActiveRecord;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
                ],
            ],
            'blameable' => [
                'class' => UnameBehavior::className(),
                'attributes' => [
                    static::EVENT_BEFORE_INSERT => ['creater', 'modifier'],
                    static::EVENT_BEFORE_UPDATE => ['modifier'],
                ],
            ],
        ];
    }

    /**
     * 格式化错误信息
     * @return string
     */
    public function getErrorStr()
    {
        $errors = [];
        foreach ($this->getErrors() as $error) {
            $errors[] = implode(' ', $error);
        }
        return implode(' ', $errors);
    }

    /**
     * @param $condition
     * @return static
     * @throws Exception
     */
    public static function findOneOrFailed($condition)
    {
        $model = static::findOne($condition);
        if (null === $model) {
            throw new Exception(Exception::ERROR_MSG_NOT_EXIST, Exception::ERROR_CODE_NOT_EXIST);
        }
        return $model;
    }

    /**
     * 软删除
     * @throws Exception
     */
    public function softDelete()
    {
        if (!array_key_exists('isDel', $this->attributes)) {
            throw new  CanNotDeleteException('软删除失败,该表不存在[isDel]字段,请先添加字段');
        }
        $this->isDel = 1;
        $this->save();
    }

    /**
     * 批量插入
     *
     * @param array $columns
     * @param array $rows
     * @return int
     * @throws Exception
     */
    public static function batchInsert($columns, $rows)
    {
        try {
            if (empty($columns) || empty($rows)) {
                return 0;
            }
            return static::getDb()->createCommand()
                ->batchInsert(static::tableName(), $columns, $rows)
                ->execute();
        } catch (\Exception $exception) {
            throw new Exception(Exception::ERROR_MSG_SAVE, Exception::ERROR_CODE_SAVE);
        }
    }
}
