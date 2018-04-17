<?php
/**
 * Created by PhpStorm.
 * User: panghailong-pc
 * Date: 2016/3/12
 * Time: 9:21
 */
namespace common\excel;
use common\excel\vehicles\ExcelVehiclesDriver;
class ExcelManager
{

    public static $goodsExcelClass = [
        //房门
        '341' => [
            '109' => 'ExcelGoodsPriceDoorMengtian', //康E木门
            'default' => 'ExcelGoodsPriceDoorRoom', //默认
        ],
        //卫生间门
        '342' => [
            '109' => 'ExcelGoodsPriceDoorMengtian',   //康E木门
            'default' => 'ExcelGoodsPriceDoorToilet', //默认
        ],
        //厨房门
        '343' => [
            '108' => 'ExcelGoodsPriceDoorTata',        //派的门
            '109' => 'ExcelGoodsPriceDoorMengtian',    //康E木门
            'default' => 'ExcelGoodsPriceDoorKitchen', //默认
        ],
        '347' => [
            'default' => 'ExcelGoodsPriceClosestool', //默认
        ],
    ];

    public static $fileType = 'xlsx';

    public static function get_path()
    {
        return '/tmp/';
    }

    /**
     * 上传导入的文件
     * @param $name  导入的input对应的name
     * @return string  返回路径
     */
    public static function uploadFile($name, $prefix)
    {
        //文件上传
        $tmp_ext = explode(".", $_FILES[$name]['name']);
        $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
        $ext = strtolower($tmp_ext);
        if ($ext != self::$fileType) {
            echo '格式不对';
        }
        $fileName = $prefix . '-' . uniqid() . '.' . self::$fileType;
        $filePath = self::get_path() . $fileName;
        move_uploaded_file($_FILES[$name]["tmp_name"], $filePath);
        return $filePath;
    }

    /**
     * 删除文件
     * @param $filePath
     */
    public static function delUploadFile($filePath)
    {
        unlink($filePath);
    }

    /**
     * 房门默认导入导出类
     * @return ExcelGoodsPriceDoorRoom
     */
    public static function ExcelGoodsPriceDoorRoom()
    {
        return new ExcelGoodsPriceDoorRoom();
    }

    /**
     * 卫生间门
     * @return ExcelGoodsPriceDoorToilet
     */
    public static function ExcelGoodsPriceDoorToilet()
    {
        return new ExcelGoodsPriceDoorToilet();
    }

    /**
     * 厨房门
     * @return ExcelGoodsPriceDoorToilet
     */
    public static function ExcelGoodsPriceDoorKitchen()
    {
        return new ExcelGoodsPriceDoorKitchen();
    }

    /**
     * 梦天门
     * @return ExcelGoodsPriceDoorMengtian
     */
    public static function ExcelGoodsPriceDoorMengtian()
    {
        return new ExcelGoodsPriceDoorMengtian();
    }

    /**
     * TATA推拉门
     * @return ExcelGoodsPriceDoorTata
     */
    public static function ExcelGoodsPriceDoorTata()
    {
        return new ExcelGoodsPriceDoorTata();
    }

    /**
     * 马桶
     * @return ExcelGoodsPriceClosestool
     */
    public static function ExcelGoodsPriceClosestool()
    {
        return new ExcelGoodsPriceClosestool();
    }

    /**
     * 根据种类和品牌获取类名
     * @param $gcId
     * @param $brandId
     * @return ExcelGoodsPrice|null
     */
    public static function getGoodsClass($gcId, $brandId)
    {
        if (!empty(self::$goodsExcelClass[$gcId][$brandId])) {
            $val = self::$goodsExcelClass[$gcId][$brandId];
            return self::$val();
        } else if (!empty(self::$goodsExcelClass[$gcId]['default'])) {
            $val = self::$goodsExcelClass[$gcId]['default'];
            return self::$val();
        } else {
            return new ExcelGoodsPrice();
        }
    }

    /**
     * 商品导出
     * @param $gcId     种类ID
     * @param $brandId  品牌ID
     * @param $list     数据
     * @return bool
     */

    public static function goodsExport($gcId, $brandId, $list)
    {
        $c = self::getGoodsClass($gcId, $brandId);
        if (empty($c)) {
            return false;
        }
        $c->export($list);
        return true;
    }

    /**
     * 商品价格的导入
     * @param $gcId  分类
     * @param $brandId 品牌
     * @param $path     路径
     * @return array
     */
    public static function goodsImport($gcId, $brandId, $path)
    {
        $c = self::getGoodsClass($gcId, $brandId);
        if (empty($c)) {
            return ['error' => '404', 'msg' => '数据出错'];
        }

        return $c->import($path);
    }

    /**
     * 车辆管理的导入
     * @param $gcId  分类
     * @param $brandId 品牌
     * @param $path     路径
     * @return array
     */
    public static function vehicleDriverImport($path)
    {
        $c =  new ExcelVehiclesDriver();

        if (empty($c)) {
            return ['error' => '404', 'msg' => '数据出错'];
        }

        return $c->import($path);
    }
}
