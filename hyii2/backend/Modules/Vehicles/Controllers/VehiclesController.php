<?php
/**
 * 货车管理控制器
 */

namespace backend\Modules\Vehicles\Controllers;

use Common\helpers\Error;

use common\Models\Vehicles;
use yii\web\Controller;
use backend\Modules\Vehicles\models\Search;
use Yii;
use yii\web\Response;
use common\excel\ExcelManager;

class VehiclesController extends Controller
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * 货车管理首页
     */
    public function actionIndex()
    {
        $data = Yii::$app->request->get();

        $searchModel = new Search();
        $searchModel->load($data);
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'vehiclesModel' => $dataProvider->getModels(),
            'pagination' => $dataProvider->getPagination(),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function daysbetweendates($date1, $date2){
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $days = ceil(abs($date1 - $date2)/86400);
        return $days;
    }
    /**
     * 货车管理详情
     */
    public function actionInfo()
    {
        return $this->_getViewVehicles();
    }

    /**
     * 车辆编辑信息
     * @return string|yii\web\Response
     */
    public function actionEdit()
    {
        return $this->_getViewVehicles();
    }

    /**
     * 查看或编辑车辆信息
     * @return string|yii\web\Response
     */
    public function _getViewVehicles()
    {
        $vehiclesModel = Vehicles::findOne(['id' => Yii::$app->request->get('id', '')]);

        if (empty($vehiclesModel)) {
            Yii::$app->getSession()->setFlash('error', '该货车不存在!');
            return $this->redirect(['/Vehicels/vehicels/index']);
        }

        $list = Vehicles::find()->where(['userName' => $vehiclesModel->userName, 'isDel' => 0])->all();

        return $this->render('info',
            [
                'vehiclesModel' => $vehiclesModel,
                'listItem' => $list,
            ]);
    }

    /**
     * 新增货车
     */
    public function actionAddVehicles()
    {
        $vehiclesModel = new Vehicles();

        return $this->render('info',
            [
                'vehiclesModel' => $vehiclesModel,
            ]
        );
    }

    /**
     * 货车信息保存
     */
    public function actionSaveVehicles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        if (empty($data)) {
            return ['success' => false, 'msg' => '提交数据出错!'];
        }

        $modelData = [];
        $msg = '';

        $trans = Yii::$app->db->beginTransaction();
        try {
            if (is_array($data['Vehicles']['id'])) {

                foreach ($data['Vehicles']['id'] as $key => $val) {

                    $detail = Vehicles::findOne(['userName' => $data['Vehicles']['userName']
                        , 'plateNum' => $data['Vehicles']['licensePlate'][$key], 'isDel' => 0]);

                    if (!empty($detail) && empty($val)) {
                        $msg = $data['Vehicles']['licensePlate'][$key] . '已存在';
                        break;
                    }

                    $vehiclesModel = Vehicles::findOne(['id' => $val]);

                    if (empty($vehiclesModel)) {
                        $vehiclesModel = new Vehicles();
                        $modelData = [
                            'plateNum' => $data['Vehicles']['licensePlate'][$key],
                            'userName' => $data['Vehicles']['userName'],
                            'mobile' => $data['Vehicles']['mobile'],
                            'idCard' => $data['Vehicles']['idCard'],
                            'ton' => round($data['Vehicles']['tonnage'][$key], 2),
                            'remark' => $data['Vehicles']['remarks'][$key],
                            'creater' => Yii::$app->user->identity->username,
                            'created' =>time(),
                            'modified' =>time(),
                        ];

                    } else {
                        $modelData = [
                            'plateNum' => $data['Vehicles']['licensePlate'][$key],
                            'userName' => $data['Vehicles']['userName'],
                            'mobile' => $data['Vehicles']['mobile'],
                            'idCard' => $data['Vehicles']['idCard'],
                            'ton' => round($data['Vehicles']['tonnage'][$key], 2),
                            'remark' => $data['Vehicles']['remarks'][$key],
                            'modifier' => Yii::$app->user->identity->username,
                            'modified' =>time(),
                        ];

                    }

                    $vehiclesModel->load(['Vehicles' => $modelData]);

                    if (!$vehiclesModel->save()) {
                        $msg = Error::toStr($vehiclesModel->getErrors());
                        break;
                    }
                }

            }
            if (!empty($msg)) {
                $trans->rollBack();
                return ['success' => false, 'msg' => $msg];
            }

            $trans->commit();
            return ['success' => true, 'msg' => $vehiclesModel->id];
        } catch (\Exception $e) {

            $trans->rollBack();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 删除货车信息
     * @TODO 已经被调拨单引用，则不能被删除
     */
    public function actionDelVehicles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userName = Yii::$app->request->get('userName', '');

        $list = Vehicles::find()
            ->where(['userName' => $userName, 'isDel' => 0])
            ->asArray()
            ->all();

        if (empty($list)) {
            Yii::$app->getSession()->setFlash('error', '该货车不存在!');
            return $this->redirect(['/Vehicels/vehicels/index']);
        }

        $trans = Yii::$app->db->beginTransaction();
        foreach ($list as $key => $val) {
            $info = Vehicles::findOne(['id' => $val['id']]);
            $info->isDel = 1;
            if (!$info->save()) {
                $msg = '删除车辆信息失败';
                break;
            }
        }

        if (!empty($msg)) {
            $trans->rollBack();
            return ['success' => false, 'msg' => $msg];
        }

        $trans->commit();
        return ['success' => true, 'msg' => '删除车辆信息成功'];
    }

    /**
     * ajax验证车牌号
     * @return mixed
     */
    public function actionValidateForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Vehicles::findOne(['id' => Yii::$app->request->post('id', '')]);
        if (empty($model)) {
            $model = new Vehicles();
        }
        $model->load(['Vehicles' => Yii::$app->request->post()]);

        if (!$model->validate($model)) {
            return ['success' => false, 'msg' => empty($model->errors['plateNum']) ? '' : $model->errors['plateNum']];
        }

        return ['success' => true, 'msg' => ''];
    }

    /**
     * 获取车牌号码和电话号码
     */
    public function actionGetPlates($name)
    {
        $return = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $vehicles = Vehicles::find()->where(['userName' => $name])->asArray()->all();
        if ($vehicles == null) {
            return ['success' => false, 'msg' => '不存在该司机信息'];
        }
        $return['mobile'] = $vehicles[0]['mobile'];
        $return['plateList'] = array_column($vehicles, 'plateNum');
        return ['success' => true, 'data' => $return];
    }

    /**
     * 获取车牌或司机列表（搜索用）
     * @param  string $q 关键字
     * @return array
     */
    public function actionFilter($op, $q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $output = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $list = Vehicles::find()
                ->select("{$op} as id, {$op} as text")
                ->where(['like', $op, $q])
                ->groupBy($op)
                ->asArray()
                ->all();
            $output['results'] = $list;
        }

        return $output;
    }


    /**
     * ajax删除车辆
     * @return mixed
     */
    public function actionDel()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids', []);
        if(empty($ids)){
            $ids=[];
            $ids[]=Yii::$app->request->post('id');
        }
        return Vehicles::del($ids);
    }
    /**
     * 导出车辆列表
     *
     *     */
    public function actionExportDetailData()
    {

        $vehicleList = Vehicles::find()->where(['<>', 'isDel', 1])->all();
        $filename = '车辆列表.xls';
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $basePath = Yii::getAlias('@common') . '/excel/vehicles/';
        $objPHPExcel = $objReader->load($basePath . "vehicles.xls");
        $curVendorCode = '';
        $j = 3;
        $t = '';
        $x = 0;
        $is_sub = false;
        $depositTotal = 0;
        $storeName = '';
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle('I2:I300')->getAlignment()->setWrapText(true);
        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        foreach ($vehicleList as $key => $value) {
            //车辆列表
            $objPHPExcel->getDefaultStyle()->applyFromArray($style);
            $objPHPExcel->getActiveSheet()
                ->setCellValue('A' . $j, $value->plateNum)
                ->setCellValue('B' . $j, $value->userName)
                ->setCellValue('C' . $j, " ".$value->mobile)
                ->setCellValue('D' . $j, " ".$value->idCard)
                ->setCellValue('E' . $j, $value->ton);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->applyFromArray($style);
            $j = $j + 1;
        }
        //设置header
        $this->_getExportHeader($objPHPExcel, $filename);
    }
    /**
     * 导出车辆列表xls
     * @param object $objPHPExcel 导出模型
     * @param string $filename 导出文件名称
     */
    public function _getExportHeader($objPHPExcel, $filename = '')
    {
        //设置header
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"{$filename}\";filename*=UTF-8''{$filename}");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文件
        $objWriter->save('php://output');
    }

    /**
     * 车辆信息导入
     */
    public function actionImport()
    {
        $request = Yii::$app->request;

        $fileName = ExcelManager::uploadFile("myFile", 'vehicle');
        $data = ExcelManager::vehicleDriverImport($fileName);

        ExcelManager::delUploadFile($fileName);

        if ($data['error'] != 0) {
            Yii::$app->session->setFlash('error', $data['msg']);
            return $this->redirect(Yii::$app->request->getReferrer());
            exit;
        }
        $list = $data['list'];
        $sql_val="";
        unset($data);
        $time = time();
        $name = Yii::$app->user->identity->username;
        $outList = [];
        $noUpdate = [];
        //进行修改操作
        foreach ($list as $item) {
            foreach ($item as $one) {
                $c = [];
                $c = ["plateNum" =>$one['plateNum'] ];
                $price_query = Vehicles::findOne($c);

                if (empty($price_query)) {
                    if (!empty($sql_val)) {
                        $sql_val .= ',';
                    }
                    $sql_val .= '(';
                    $sql_val .= $one['plateNum'];
                    $sql_val .= ",'";
                    $sql_val .= $one['userName'];
                    $sql_val .= "','";
                    $sql_val .= $one['mobile'];
                    $sql_val .= "','";
                    $sql_val .= $one['idCard'];
                    $sql_val .= "','";
                    $sql_val .= $one['ton'];
                    $sql_val .= "','";
                    $sql_val .= $time;
                    $sql_val .= "','";
                    $sql_val .= $name;
                    $sql_val .= "','";
                    $sql_val .= $time;
                    $sql_val .= "','";
                    $sql_val .= $name;
                    $sql_val .= "')";
                } else {
                    $price_query->plateNum = $one['plateNum'];
                    $price_query->userName = $one['userName'];
                    $price_query->mobile = $one['mobile'];
                    $price_query->idCard = $one['idCard'];
                    $price_query->ton = $one['ton'];
                    $price_query->modified = $time;
                    $price_query->modifier = $name;
                    $price_query->save();
                }
            }
        }

        if (!empty($one['msg'])) {
            $msg = $this->eachError($one['msg']);
            Yii::$app->session->setFlash('error', $msg);
            return $this->redirect(Yii::$app->request->getReferrer());
        }

        unset($list);
        unset($time);
        unset($name);

        if ($sql_val != '') {
            $sql = 'insert into vehicles (plateNum,userName,mobile,idCard,ton,created,creater,modified,modifier) values';
            Yii::$app->db->createCommand($sql . $sql_val)->execute();
        }
        $data = Yii::$app->request->get();

        $searchModel = new Search();
        $searchModel->load($data);
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'vehiclesModel' => $dataProvider->getModels(),
            'pagination' => $dataProvider->getPagination(),
            'dataProvider' => $dataProvider,
        ]);
    }

}
