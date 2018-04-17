<?php
/**
 *
 * 字段明细控制器
 *
 * @copyright   本软件和相关文档仅限安乐窝和/或其附属公司开发团队内部交流使用，
 *              并受知识产权法的保护。除非公司以适用法律明确授权， 否则不得以任
 *              何形式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、
 *              分发、展示、执行、发布或显示本软件和相关 文档的任何部分。
 * @link        http://www.anlewo.com/
 * @package     abiao\datadict\controllers
 * @author      梁铭佳 liangmingjia@anlewo.com
 * @since       0.1.0
 */

namespace abiao\datadict\controllers;

use abiao\datadict\models\DatadictDetail;
use abiao\datadict\models\searchs\NewsDetailSearch;
use kartik\widgets\ActiveForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class DetailController extends Controller
{
    /**
     * 字段明细列表
     */
    public function actionList()
    {
        $pid = Yii::$app->request->post('expandRowKey', 0);
        if (empty($pid)) {
            $pid = Yii::$app->request->get('expandRowKey', 0);
        }

        $searchModel = new NewsDetailSearch();
        $dataProvider = $searchModel->search(['pid' => $pid]);

        return $this->renderAjax('list', [
            'pid' => $pid,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 添加字段明细
     * @param integer $pid Datadict.id
     * @return string
     */
    public function actionAdd($pid)
    {
        $model = new DatadictDetail;
        $model->pid = $pid;

        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'redirect' => Url::to(['datadict/index']),
                ];
            }
            return [
                'success' => false,
                'validation' => ActiveForm::validate($model),
            ];
        }

        return $this->renderAjax('add', [
            'model' => $model,
        ]);
    }

    /**
     * 更新字段明细
     * @param integer $id DatadictDetail.id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'redirect' => Url::to(['datadict/index']),
                ];
            }
            return [
                'success' => false,
                'validation' => ActiveForm::validate($model),
            ];
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * 停用
     * @param integer $id DatadictDetail.id
     * @return mixed
     */
    public function actionDisabled($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);
        $model->flag = 1;
        $saved = $model->save();
        return [
            'success' => $saved,
            'msg' => $saved ? '停用成功' : '停用失败',
        ];
    }

    /**
     * 启用
     * @param integer $id DatadictDetail.id
     * @return mixed
     */
    public function actionEnabled($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);
        $model->flag = 0;
        $saved = $model->save();
        return [
            'success' => $saved,
            'msg' => $saved ? '启用成功' : '启用失败',
        ];
    }

    /**
     * Finds the Datadict model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Datadict the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DatadictDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
