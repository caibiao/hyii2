<?php
/**
 * 重写Security
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/30 10:25
 */

namespace Service\Base;


class Security extends \yii\base\Security
{
    /**
     * 重写用户密码验证规则
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validatePassword($password, $hash)
    {
        return $this->compareString(md5($password), $hash);
    }

    /**
     * 重写密码加密规则
     * @param string $password
     * @param null $cost
     * @return string
     */
    public function generatePasswordHash($password, $cost = null)
    {
        return md5($password);
    }
}