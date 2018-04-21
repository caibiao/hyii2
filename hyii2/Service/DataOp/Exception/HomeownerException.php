<?php
/**
 * @link http://www.anlewo.com/
 * @copyright Copyright (c) 2015-2017 Anlewo Ltd
 * @license 广东安乐窝网络科技有限公司
 * @author Jingxian Ni <nijingxian@anlewo.com>
 * @date 2017/11/16
 */

namespace Anlewo\Service\Custom\Exception;


use Anlewo\Service\Base\Exception;

class HomeownerException extends Exception
{
    const ERROR_CODE_NOT_EXISTS = 2001; //客户不存在
    const ERROR_CODE_EXISTS = 2002;     //客户已存在
    const ERROR_CODE_CREATE = 2003;
    const ERROR_CODE_UPDATE = 2004;
    const ERROR_CODE_PARAM = 2005; //传入参数不正确
    const ERROR_CODE_HOUSE_NOT = 2006; //房产信息不存在
    const ERROR_CODE_PHONE = 2007; //修改手机号码失败
    const ERROR_CODE_INTENT_UPDATE = 2008; //修改手机号码失败
    const ERROR_CODE_STAR_UPDATE = 2009; //客户等级评定失败
    const ERROR_CODE_CUSTOM_TYPE = 2010; //更新为正式客户失败
    const ERROR_CODE_SERVICE_ADD = 2011; //新增门店服务信息失败
    const ERROR_CODE_HOUSE_ADD = 2012; //新增客户房产信息失败
    const ERROR_CODE_HOUSE_UPDATE = 2013; //修改客户房产信息失败
    const ERROR_CODE_UPLOAD = 2014;
    const ERROR_CODE_SEND_CODE = 2015;
    const ERROR_CODE_VALIDATE_CODE = 2016;

    const ERROR_MSG_NOT_EXISTS = '客户不存在';
    const ERROR_MSG_EXIST = '客户已存在';
    const ERROR_MSG_CREATE = '创建客户资料失败';
    const ERROR_MSG_UPDATE = '更新客户资料失败';
    const ERROR_MSG_PARAM = '传入参数不正确';
    const ERROR_MSG_HOUSE_NOT = '房产信息不存在';
    const ERROR_MSG_PHONE = '修改手机号码失败';
    const ERROR_MSG_INTENT_UPDATE = '客户意向喜好修改失败';
    const ERROR_MSG_STAR_UPDATE = '客户等级评定失败';
    const ERROR_MSG_CUSTOM_TYPE = '更新为正式客户失败';
    const ERROR_MSG_SERVICE_ADD = '新增门店服务信息失败';
    const ERROR_MSG_HOUSE_ADD = '新增客户房产信息失败';
    const ERROR_MSG_HOUSE_UPDATE = '修改客户房产信息失败';
    const ERROR_MSG_UPLOAD = '上传客户头像失败';
    const ERROR_MSG_SEND_CODE = '验证码发送失败';
    const ERROR_MSG_VALIDATE_CODE = '验证码错误';

    public function getName()
    {
        return 'HomeownerException';
    }

}