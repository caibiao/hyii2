<?php
/**
 * Created by PhpStorm.
 * User: panghailong-pc
 * Date: 2016/3/11
 * Time: 15:24
 */
namespace common\excel\vehicles;

use common\excel\ExcelBase;
class ExcelVehicles extends ExcelBase{

    public function __construct()
    {
        parent::__construct();
        $this->_fixed_prefix = [
            'plateNum' => ['title' => '车牌号'],
            'userName' => ['title' => '司机姓名'],
            'mobile' => ['title' => '手机号码'],
            'idCard' => ['title' => '身份证号'],
            'ton' => ['title' => '吨位'],
        ];
    }

    /**
     * 加上固定的表头前缀部分
     */
    public function addFixedPrefix(){
        foreach ($this->_input_format as &$item) {
            $item = array_merge($this->_fixed_prefix, $item);
        }
    }

    /**
     * 导出入口
     * @param $data     导出的数据
     * @throws PHPExcel_Reader_Exception
     * @return 0成功，非0失败
     */
    public function export(&$data)
    {
        $this->dataAssembling($data);
        return parent::export($data);
    }

    /**
     * 导入入口
     * @param $type  类型
     * @param $filePath 路径
     * @return $data  array('error'=>0,'msg'=>'error非0的时候，会有','data'=>array());
     */
    public function import($filePath){
        $data = parent::import($filePath);
        $this->dataSplice($data);
        return $data;
    }

    /**
     * 设置标类的值和样式
     * @param $col
     * @param $row
     * @param $val
     */
    public function setPriceType($col,$row,$val){
        $gptname = '';
        $rgb = '000000';
        if($val == GoodsTable::PRICE_TYPE_BLUE){
            $gptname = '蓝标';
            $rgb = '0000FF';
        }
        else if($val == GoodsTable::PRICE_TYPE_ORANGE){
            $gptname = '橙标';
            $rgb = 'ffb200';
        }
        else if($val == GoodsTable::PRICE_TYPE_RED) {
            $gptname = '红标';
            $rgb = 'FF0000';
        }
        else if($val == GoodsTable::PRICE_TYPE_GREEN){
            $gptname = '绿标';
            $rgb = '00FF00';
        }
        //I:标类
        $this->setVal($col,$row,$gptname);
        $this->_objActSheet->getStyle($this->getChar($col).$row)->getFont()->getColor()->setRGB($rgb);
    }

    /**
     * @param $val
     * @return int
     */
    public function getPriceType($col,$row){
        $val = $this->readVal($col,$row);
        if($val == '蓝标'){
            return GoodsTable::PRICE_TYPE_BLUE;
        }
        else if($val == '红标'){
            return GoodsTable::PRICE_TYPE_RED;
        }
        else if($val == '绿标'){
            return GoodsTable::PRICE_TYPE_GREEN;
        }
        else{
            return GoodsTable::PRICE_TYPE_ORANGE;
        }
    }

    /**
     * 设置商品状态的样式
     * @param $col
     * @param $row
     * @param $val
     */
    public function setGoodsState($col,$row,$val){
        $gptname = '';
        $rgb = '000000';
        if($val == GoodsTable::GOODS_STATE_OUT){  //下架
            $gptname = '下架';
            $rgb = '3a8104';
        }
        else if($val == GoodsTable::GOODS_STATE_NORMAL){  //正常
            $gptname = '正常';
            $rgb = '0000FF';
        }
        else if($val == GoodsTable::GOODS_STATE_ILLEGAL) { //违规
            $gptname = '违规';
            $rgb = 'FF0000';
        }
        //I:标类
        $this->setVal($col,$row,$gptname);
        $this->_objActSheet->getStyle($this->getChar($col).$row)->getFont()->getColor()->setRGB($rgb);
    }

    /**
     * @param $val
     * @return int
     */
    public function getGoodsState($col,$row){
        $val = $this->readVal($col,$row);
        if($val == '正常'){
            return GoodsTable::GOODS_STATE_NORMAL;
        }
        else if($val == '下架'){
            return GoodsTable::GOODS_STATE_OUT;
        }
        else if($val == '违规'){
            return GoodsTable::GOODS_STATE_ILLEGAL;
        }
        else{
            return $val;
        }
    }

    /**
     * 数据组装
     * @param $data
     */
    public function dataAssembling(&$data){

    }

    /**
     * 数据拼接
     * @param $data
     */
    public function dataSplice(&$data){

    }
}