<?php
/**
 * aliyun oss
 *
 * @Link http://www.anlewo.com/
 * @Copyright Copyright (c) 2015 Anlewo Ltd
 * @License 广东安乐窝网络科技有限公司
 * @Author linmengfeng@anlewo.com
 * @Date 2016/4/6
 */

namespace Anlewo\Common\helpers;

use Yii;

/**
 * 阿里云OSS操作类
 * @package Anlewo\Common\helpers
 */
class Oss {
    private static $oss_sdk_service;
    private static $bucket;
    private static function _init() {
        require_once(Yii::getAlias('@vendor').'/oss/sdk.class.php');
        self::$oss_sdk_service = new \ALIOSS(Yii::$app->params['access_id'], Yii::$app->params['access_key'], Yii::$app->params['apiUrl']);
        //设置是否打开curl调试模式
        self::$oss_sdk_service->set_debug_mode(true);
        self::$bucket = Yii::$app->params['bucket'];
    }

    /**
     *
     * @param unknown $src_file
     * @param unknown $new_file
     */
    public static function upload($src_file,$new_file) {
        self::_init();
        try{
            $response = self::$oss_sdk_service->upload_file_by_file(self::$bucket,$new_file,$src_file);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
//             self::_format($response);exit;
        } catch (Exception $ex){
            return false;
            //die($ex->getMessage());
        }
    }

    /**
     * 删除图片
     * @param array $img_list
     * @return bool
     */
    public static function del($img_list = array()) {
        self::_init();
        try{
            $options = array(
                'quiet' => false,
            );
            $response = self::$oss_sdk_service->delete_objects(self::$bucket,$img_list,$options);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex){
            return false;
        }
    }

    /**
     * 判断文件是否存在
     * @param $file
     * @return bool
     */
    public static function is_exist($file) {
        self::_init();
        try{
            $file = substr($file,0,1) == '/' ? ltrim($file,'/') : $file;
            $response = self::$oss_sdk_service->is_object_exist(self::$bucket,$file);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex){
            return false;
        }
    }

    /**
     * @param $file
     * @return bool
     */
    public static function download($file, $localfile) {
        self::_init();
        try{
            $file = substr($file,0,1) == '/' ? ltrim($file,'/') : $file;
            $options = array(
                \ALIOSS::OSS_FILE_DOWNLOAD => $localfile,
            );
            $response = self::$oss_sdk_service->get_object(self::$bucket, $file, $options);
            if ($response->status == '200') {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex){
            return false;
        }
    }
}