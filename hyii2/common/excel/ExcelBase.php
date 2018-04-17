<?php

namespace common\excel;

use Yii;

class ExcelBase
{
    protected $_input_file_type = 'Excel2007';
    //模版路径
    protected $_temp_path = '';
    //默认的模版名字
    protected $_temp_file_name = 'export_default';

    //表的前部分字段。公用部分。
    protected $_fixed_prefix = [];

    //excel对象
    protected $_objPHPExcel = null;

    //工作表
    protected $_objActSheet = null;

    //子类配置的数据
    protected $_input_format = array();
    protected $_input_format_sheet = array();

    public function __construct()
    {
        //设置一下大小
        ini_set('memory_limit', '256M');
        $this->_temp_path = 'Common/excel/goods/';
    }

    /**
     * 导出入口
     * @param $data     导出的数据
     * @throws PHPExcel_Reader_Exception
     * @return 0成功，非0失败
     */
    public function export(&$data)
    {
        $this->load();
        $this->build($data);
        return $this->save();
    }

    /**
     * 导入入口
     * @param $type  类型
     * @param $filePath 路径
     * @return $data  array('error'=>0,'msg'=>'error非0的时候，会有','data'=>array());
     */
    public function import($filePath)
    {
        $this->load($filePath);
        $data = $this->readAll();
        return $data;
    }

    /**
     *  使用0-25 表示A-Z,  26就是AA
     * @param $r
     * @return string
     */
    public function getChar($r)
    {
        $out = '';
        if ($r >= 26) {
            $s = intval($r / 26);
            $s -= 1;
            $out = $this->getChar($s);
            $ss = $r % 26;
            $out .= chr($ss + 65);
        } else {
            $out = chr($r + 65);
        }
        return $out;
    }

    /**
     * 初始化一些数据，打开模版
     * @throws PHPExcel_Reader_Exception
     */
    public function load($inputFileName = '')
    {
        if ($inputFileName == '') {
            $inputFileName = $this->_temp_path . $this->_temp_file_name . '.' . ExcelManager::$fileType;
        }
//        $objReader = \PHPExcel_IOFactory::createReader($this->_input_file_type);
        //        $objReader->setIncludeCharts(TRUE);
        //        $this->_objPHPExcel = $objReader->load($inputFileName);
        $this->_objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
        $this->_objActSheet = $this->_objPHPExcel->getSheet(0);
    }

    /**
     * 自己创建表头文档
     */
    public function loadSheet()
    {
        $this->_objPHPExcel = new PHPExcel();
        $obpe_pro = $this->_objPHPExcel->getProperties();
        $obpe_pro->setCreator('phl')//设置创建者
        ->setLastModifiedBy(date('Y/m/d H:i')); //设置时间

        $len = count($this->_input_format_sheet);

        for ($i = 0; $i < $len; $i++) {
            if ($i != 0) {
                $this->_objPHPExcel->createSheet();
            }

            $this->_objActSheet = $this->_objPHPExcel->setActiveSheetIndex($i);
            if ($this->_input_format_sheet[$i]['title'] != '') {
                $this->_objActSheet->setTitle($this->_input_format_sheet[$i]['title']); //标题名称
            }

            $arr = $this->_input_format[$i];
            $k = 0;
            foreach ($arr as $v) {
                $this->setVal($k, 1, $v['title']);
                $k += 1;
            }
        }

        $this->_objPHPExcel->setActiveSheetIndex(0);
    }

    /**
     * 根据数据，建立excel表数据
     * @param $data 数据
     */
    public function build(&$data)
    {
        $count = $this->_objPHPExcel->getSheetCount();
        $len = count($this->_input_format_sheet); //工作表总数
        if ($len > $count) {
            $len = $count;
        }
        for ($i = 0; $i < $len; $i++) {
            $this->_objPHPExcel->setActiveSheetIndex($i); //指向当前工作表
            $this->_objActSheet = $this->_objPHPExcel->getActiveSheet();

            //标题是否需要设置
            if (isset($this->_input_format_sheet[$i]['title']) && $this->_input_format_sheet[$i]['title'] != '') {
                $this->_objActSheet->setTitle($this->_input_format_sheet[$i]['title']); //标题名称
            }
            //开始行
            $row = $this->_input_format_sheet[$i]['start_row'];
            //开始列
            $start_col = $this->_input_format_sheet[$i]['start_col'];
            if (isset($this->_input_format_sheet[$i]['key'])) {
                $key = $this->_input_format_sheet[$i]['key'];
                $val = $this->_input_format_sheet[$i]['val'];
            } else {
                $key = '';
                $val = '';
            }

            $format_one_arr = &$this->_input_format[$i];
            $index = 0;
            if (is_array($data)) {
                foreach ($data as &$v) {

                    if (!is_array($v)) {
                        continue;
                    }

                    if ($key == '' || $v[$key] == $val) { //这里只做相等处理(ps:如果数据要求在不等或者大于等情况下，建议在外部处理成另一个字段的类型放进去)
                        $col = $start_col;
                        $index += 1;
                        $v['i'] = $index;
                        foreach ($format_one_arr as $key_key => &$key_val) {
                            !isset($v[$key_key]) && $v[$key_key] = '';
                            if (isset($key_val['ex-func']) && $key_val['ex-func'] != '' && method_exists($this, $key_val['ex-func'])) { //ex-func 格式都为，列，当前行，对应的值
                                call_user_func([$this, $key_val['ex-func']], $col, $row, $v[$key_key]);
                            } else {
                                $this->setVal($col, $row, $v[$key_key]);
                            }
                            $col += 1;
                        }
                        $row += 1;
                    }
                }
            }

        }

        //返回默认的单元格
        $this->_objPHPExcel->setActiveSheetIndex(0);
    }

    /**v
     * 保存excel输出
     * @throws PHPExcel_Reader_Exception
     */
    public function save()
    {
        $outputFileName = $this->_temp_file_name . '' . date("Ymd_His") . '.' . ExcelManager::$fileType;
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . $outputFileName);
        $objWriter = \PHPExcel_IOFactory::createWriter($this->_objPHPExcel, $this->_input_file_type);
        $objWriter->save('php://output');
        return 0;
    }

    /**
     * 得到完整的错误信息
     * @param $result
     * @param $col
     * @param $row
     * @param $title
     * @return array
     */
    public function getErrInfo($result, $col, $row, $title)
    {
        $s = $this->_objActSheet->getTitle();
        return array('error' => $result['error'], 'msg' => $title . $result['msg'] . ',出错工作表：' . $s . ' ,出错行为：' . $row . ',出错列为：' . ($col + 1));
    }

    /**
     * 数据导入读取生成
     * @return array
     */
    public function readAll()
    {
        $count = $this->_objPHPExcel->getSheetCount();
        $len = count($this->_input_format_sheet);
        if ($len > $count) {
            $len = $count;
        }

        $data = array('error' => 0);
        for ($i = 0; $i < $len; $i++) {
            $this->_objPHPExcel->setActiveSheetIndex($i);
            $this->_objActSheet = $this->_objPHPExcel->getActiveSheet();

            $row = $this->_input_format_sheet[$i]['start_row'];
            $start_col = $this->_input_format_sheet[$i]['start_col'];

            if (isset($this->_input_format_sheet[$i]['key'])) {
                $key = $this->_input_format_sheet[$i]['key'];
                $val = $this->_input_format_sheet[$i]['val'];
            } else {
                $key = '';
                $val = '';
            }

            $totalRow = $this->_objActSheet->getHighestRow(); //总行数
            $format_one_arr = &$this->_input_format[$i];
            $list = array();
            $curVal = '';
            for (; $row <= $totalRow; ++$row) {
                //数据是空的，忽略跳过
                if ($this->readVal(0, $row) == '') {
                    continue;
                }
                $col = $start_col;
                $arr_val = array();
                foreach ($format_one_arr as $f_key => &$f_val) {
                    if (isset($f_val['im-func']) && $f_val['im-func'] != '' && method_exists($this, $f_val['im-func'])) { //ex-func 格式都为，列，当前行，对应的值
                        $curVal = call_user_func([$this,$f_val['im-func']],$col, $row);
                    } else {
                        $curVal = $this->readVal($col, $row);
                    }
                    //验证字段不为空，则需要验证
                    if (isset($f_val['v-func']) && $f_val['v-func'] != '') {
                        $func_arr = explode('|', $f_val['v-func']);
                        $func_len = count($func_arr);
                        for ($func_i = 0; $func_i < $func_len; ++$func_i) {
                            if (method_exists($this, $func_arr[$func_i])) {
                                $result = call_user_func([$this, $func_arr[$func_i]], $curVal);
                                if ($result['error'] != 0) {
                                    return $this->getErrInfo($result, $col, $row, $f_val['title']);
                                }
                            }
                        }
                    }
                    $arr_val[$f_key] = $curVal;
                    $col += 1;
                }
                if ($key != '') {
                    $arr_val[$key] = $val;
                }
                array_push($list, $arr_val);
            }
            $data['list'][] = $list;
        }

        //返回默认的单元格
        $this->_objPHPExcel->setActiveSheetIndex(0);

        return $data;
    }

    /**
     * 读取值
     * @param $col  列(0开始)
     * @param $row  行(1开始）
     * @return string
     */
    public function readVal($col, $row)
    {
        return trim($this->_objActSheet->getCellByColumnAndRow($col, $row)->getValue());
    }

    /**
     * 保留两位小数
     * @param $col
     * @param $row
     * @return string
     */
    public function round_2($col, $row)
    {
        $val = $this->readVal($col, $row);
        $result = $this->isNumeric($val);
        if ($result['error'] != 0) {
            return $val;
        }
        return sprintf("%.2f", $val);
    }

    /**
     * 保留三位小数
     * @param $col
     * @param $row
     * @return string
     */
    public function round_3($col, $row)
    {
        $val = $this->readVal($col, $row);
        $result = $this->isNumeric($val);
        if ($result['error'] != 0) {
            return $val;
        }
        return sprintf("%.3f", $val);
    }

    /**
     * 舍弃保留两位小数
     * @param $col
     * @param $row
     * @return string
     */
    public function number_format_2($col, $row)
    {
        $val = $this->readVal($col, $row);
        $result = $this->isNumeric($val);
        if ($result['error'] != 0) {
            return $val;
        }
        return floor($val*100)/100;
    }

    /**
     * 舍弃保留三位小数
     * @param $col
     * @param $row
     * @return string
     */
    public function number_format_3($col, $row)
    {
        $val = $this->readVal($col, $row);
        $result = $this->isNumeric($val);
        if ($result['error'] != 0) {
            return $val;
        }
        return floor($val*1000)/1000;
    }
    /**
     * 设置值
     * @param $key  设置的单元格
     * @param $val  设置的值
     */
    public function setVal($col, $row, $val)
    {
        $this->setCellValue($this->getChar($col) . $row, $val);
    }

    public function setCellValue($key, $val)
    {
        $this->_objActSheet->setCellValue($key, $val);
    }

    /**
     * 判断是否为空
     * @param $val
     * @return array
     */
    public function isEmpty($val)
    {
        if ($val !== 0 && empty($val)) {
            return array('error' => '-1', 'msg' => '数据为空');
        }

        return array('error' => '0');
    }

    /**
     * 判断数据是否为数字
     * @param $val
     * @return array
     */
    public function isNumeric($val)
    {
        if (!is_numeric($val)) {
            return array('error' => '-1', 'msg' => '有非数字');
        }
        return array('error' => '0');
    }

    /**
     * 判断数据是否大于0
     */
    public function egt0($val)
    {
        $res = $this->isNumeric($val);
        if ($res['error'] != 0) {
            return $res;
        }

        if ($val < 0) {
            return array('error' => '-1', 'msg' => '有负数');
        }
        return array('error' => '0');
    }

    /**
     * 输出_input_format_sheet和_input_format数组信息
     */
    public function echoFormat()
    {

        echo '
        $this->_input_format_sheet = [';
        $len = count($this->_input_format_sheet);

        for ($i = 0; $i < $len; ++$i) {
            echo '
            [';

            foreach ($this->_input_format_sheet[$i] as $k => $item) {
                echo '
                \'' . $k . '\'=>\'' . $item . '\',';
            }
            echo '
            ],';
        }
        echo '
        ];';

        echo '
        $this->_input_format = [';

        $len = count($this->_input_format);

        for ($i = 0; $i < $len; ++$i) {
            echo '
            [';

            foreach ($this->_input_format[$i] as $k => $item) {
                echo '
                \'' . $k . '\'=>[';
                $j = 0;
                foreach ($item as $key => $val) {
                    if ($j != 0) {
                        echo ',';
                    }
                    ++$j;
                    echo '\'' . $key . '\'=>\'' . $val . '\'';
                }
                echo '],';
            }
            echo '
            ],';
        }
        echo '
        ];';
    }

    /**
     * 返回工作簿的头部
     * @param $one
     */
    public function returnTitle(&$one)
    {
        $len = count($this->_input_format_sheet);
        $i = 0;
        $outarr = array();
        if ($len > 1) {
            for (; $i < $len; ++$i) {
                $key = $this->_input_format_sheet[$i]['key'];
                if ($key == '') {
                    break;
                }
                if ($this->_input_format_sheet[$i]['val'] == $one[$key]) {
                    break;
                }
            }
        }

        foreach ($this->_input_format[$i] as $k => &$v) {
            if ($k == 'i') {
                continue;
            }
            $outarr[$k] = $v['title'];
        }
        $this->_objPHPExcel->setActiveSheetIndex($i);
        $this->_objActSheet = $this->_objPHPExcel->getActiveSheet();

        $title = $this->_objActSheet->getTitle();

        return array($title, $outarr);
    }

}
