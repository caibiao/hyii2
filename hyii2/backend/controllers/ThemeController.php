<?php
namespace backend\controllers;

/**
 * Created by PhpStorm.
 * User: hzhuangxianan
 * Date: 2016/11/23
 * Time: 18:18
 */

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ThemeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'set-theme' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        //获取当前使用的模板
        $config = Yii::$app->components;
        $arr = explode('/', $config['view']['theme']['baseUrl']);
        $curTheme = $arr[count($arr)-1];
        //获取所有模板
        $files = scandir('../themes');
        foreach ($files as $list){
            if($list != '.' && $list != '..'){
                $theme = ['filename'=>$list,'image_url'=>'/themes/'.$list.'/images/config/preview.jpg','is_used'=>0];
                if($list == $curTheme){
                    $theme['is_used'] = 1;
                }
                $themes[] = $theme;
            }
        }

        return $this->render('index',['themes'=>$themes,'curTheme'=>$curTheme]);
    }

    /**
     * @param string tid
     * @return string
     */
    public function actionSetTheme()
    {
        $theme = Yii::$app->request->post('tid','hyii2');
        exec('php ../set-themes '.$theme, $res);
        if($res[0] == 'success'){
            return json_encode(['state'=>true]);
        }
        return json_encode(['state'=>false,'msg'=>'模板设置失败！']);
    }
}