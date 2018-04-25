<?php
/**
 * 部门管理-部门管理业务逻辑类
 *
 * @author CaiBiao Shen <syu150107@gmail.com>
 * @date 2018/04/23
 */

namespace Service\DataOp;


use Service\Base\Service;
use Service\DataOp\Exception\HomeownerException;

use Service\DataOp\Models\Search\DeptSearch;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use Yii;

class Homeowner extends Service
{
    /**
     * 获取部门列表
     * @param {array}   $searchFilter  搜索条件数组
     * $searchFilter = [
     *   'no' => 部门编号(string),
     *   'name' => 部门名称(string),
     *   'coins' => 网币(int),
     * ];
     * @return DeptSearch
     * @throws HomeownerException
     */
    public static function search($searchFilter=null)
    {
        try {
            $model = new DeptSearch();
            $model->load($searchFilter, '');
        } catch (HomeownerException $e) {
            throw new HomeownerException($e->getMessage(), $e->getCode());
        }

        return $model;
    }
}