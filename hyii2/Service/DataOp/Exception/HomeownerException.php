<?php
/**
 * @link http://www.anlewo.com/
 * @copyright Copyright (c) 2015-2017 Anlewo Ltd
 * @license 广东安乐窝网络科技有限公司
 * @author Jingxian Ni <nijingxian@anlewo.com>
 * @date 2017/11/16
 */

namespace Service\DataOp\Exception;


use Service\Base\Exception;

class HomeownerException extends Exception
{
    const ERROR_CODE_NOT_EXISTS = 2001; //部门不存在
    const ERROR_CODE_EXISTS = 2002;     //部门已存在
    const ERROR_CODE_CREATE = 2003;
    const ERROR_CODE_UPDATE = 2004;
    const ERROR_CODE_PARAM = 2005; //传入参数不正确


    const ERROR_MSG_NOT_EXISTS = '部门不存在';
    const ERROR_MSG_EXIST = '部门已存在';
    const ERROR_MSG_CREATE = '创建部门资料失败';
    const ERROR_MSG_UPDATE = '更新部门资料失败';
    const ERROR_MSG_PARAM = '传入参数不正确';


    public function getName()
    {
        return 'HomeownerException';
    }

}