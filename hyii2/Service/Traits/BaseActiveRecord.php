<?php
/**
 * 数据库操作函数
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/1/31 14:41
 */

namespace Service\Traits;


use Service\Exception\NotFoundException;
use Service\Exception\OptimisticLockException;
use Service\Exception\ParamsErrorException;
use Service\Exception\SaveException;
use yii\db\StaleObjectException;
use Yii;

trait BaseActiveRecord
{
    /**
     * @param $condition
     * @return static
     * @throws NotFoundException
     */
    public static function findOneOrFailed($condition)
    {
        $model = static::findOne($condition);
        if (null === $model) {
            throw new NotFoundException();
        }
        return $model;
    }

    /**
     * @param       $condition
     * @param array $append
     * @return static
     * @throws ParamsErrorException
     * @throws SaveException
     * @throws \Exception
     */
    public static function findOrCreate($condition, array $append = [])
    {
        $model = static::findOne($condition) ? static::findOne($condition) : static::createData(array_merge($condition, $append));
        return $model;
    }

    /**
     * @param       $condition
     * @param array $append
     * @return static
     * @throws ParamsErrorException
     */
    public static function findOrNew($condition, array $append = [])
    {
        $model = static::findOne($condition);
        if (null === $model) {
            $params = array_merge($condition ? $condition : [], $append);
            /* @var BaseActiveRecord */
            $model = new static();
            array_key_exists('create', $model->scenarios()) && $model->setScenario('create');
            if (!$model->load($params, '') || !$model->validate()) {
                throw new ParamsErrorException(json_encode($model->getFirstErrors()));
            }
        }
        return $model;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws SaveException
     */
    public function saveOrFailed($runValidation = true, $attributeNames = null)
    {
        $model = static::save($runValidation, $attributeNames);
        if (!$model) {
            throw new SaveException();
        }
        return $model;
    }

    /**
     * @param $params
     * @return static
     * @throws ParamsErrorException
     * @throws SaveException
     * @throws \Exception
     */
    public static function createData($params)
    {
        /* @var BaseActiveRecord */
        $model = new static();
        array_key_exists('create', $model->scenarios()) && $model->setScenario('create');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->load($params, '') || !$model->validate()) {
                throw new ParamsErrorException(json_encode($model->getFirstErrors()));
            }
            $model->saveOrFailed();
            $transaction->commit();
            return $model;
        } catch (ParamsErrorException $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new SaveException($e->getMessage());
        }
    }

    /**
     * @param $search
     * @param $params
     * @return static
     * @throws NotFoundException
     * @throws ParamsErrorException
     * @throws SaveException
     * @throws \Exception
     */
    public static function editData($search, $params)
    {
        /* @var BaseActiveRecord */
        $model = static::findOneOrFailed($search);
        array_key_exists('update', $model->scenarios()) && $model->setScenario('update');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->load($params, '') || !$model->validate()) {
                throw new ParamsErrorException(json_encode($model->getFirstErrors()));
            }
            $model->saveOrFailed();
            $transaction->commit();
            return $model;
        } catch (ParamsErrorException $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new SaveException($e->getMessage());
        }
    }
    /**
     * @param      $params
     * @param bool $soft
     * @throws OptimisticLockException
     * @throws SaveException
     * @throws NotFoundException
     */
    public static function removeData($params, $soft = false)
    {
        try {
            $model = static::findOneOrFailed($params);
            if ($soft) {
                $model->softDelete();
            } else {
                $model->delete();
            }
        } catch (StaleObjectException $e) {
            throw new OptimisticLockException();
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) { // 有可能有外键关联, 删除不了
            throw new SaveException();
        }
    }

    /**
     * 批量插入, 尽量只用在关联表的操作
     *
     * @param array $columns
     * @param array $rows
     * @return int
     * @throws SaveException
     */
    public static function batchInsert(array $columns, array $rows)
    {
        try {
            if (empty($columns) || empty($rows)) {
                return 0;
            }
            return static::getDb()->createCommand()
                ->batchInsert(static::tableName(), $columns, $rows)
                ->execute();
        } catch (\Exception $exception) {
            throw new SaveException();
        }
    }

    /**
     * @throws SaveException
     */
    public function softDelete()
    {
        if (!array_key_exists('isDel', $this->attributes)) {
            throw new SaveException('不能软删除');
        }
        $this->isDel = 1;
        $this->saveOrFailed();
    }
}