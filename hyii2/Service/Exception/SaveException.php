<?php
/**
 * 保存到数据库错误
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/1/31 14:29
 */

namespace Service\Exception;


use Service\Base\Exception;

class SaveException extends Exception
{
    const CODE = 2;

    public function __construct($message = null)
    {
        parent::__construct($message ? $message : '服务器错误');
    }
}