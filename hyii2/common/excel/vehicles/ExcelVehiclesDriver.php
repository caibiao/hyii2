<?php
/**
 * Created by PhpStorm.
 * User: panghailong-pc
 * Date: 2016/4/19
 * Time: 10:16
 */

namespace common\excel\vehicles;


class ExcelVehiclesDriver extends ExcelVehicles
{
    public function __construct()
    {
        parent::__construct();
        $this->_temp_file_name = 'vehicles_driver';

        $this->_input_format_sheet = [
            [
                'start_row' => '2',
                'start_col' => '0',
            ],
        ];
        $this->_input_format = [
            [
                'platNum' => ['title' => '车牌号'],
                'userName' => ['title' => '司机姓名','v-func' => 'isEmpty'],
                'mobile' => ['title' => '手机号码'],
                'idCard' => ['title' => '身份证号'],
                'ton' => ['title' => '吨位'],
            ],
        ];
        $this->addFixedPrefix();
    }
}
