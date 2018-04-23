<?php
/**
 * 业务逻辑Exception基类
 *
 * @copyright   本软件和相关文档仅限安乐窝和/或其附属公司开发团队内部交流使用，
 *              并受知识产权法的保护。除非公司以适用法律明确授权， 否则不得以任
 *              何形式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、
 *              分发、展示、执行、发布或显示本软件和相关 文档的任何部分。
 * @link        http://www.anlewo.com/
 * @package     package name
 * @author      梁铭佳 liangmingjia@anlewo.com
 * @since       0.1.0
 */
namespace Service\Base;

class Exception extends \yii\base\Exception
{
    const CODE = 0;

    const ERROR_CODE_VALIDATION = 1;
    const ERROR_CODE_SAVE = 2;
    const ERROR_CODE_REPEAT = 3; // 重复提交修改
    const ERROR_CODE_PARAMS_NULL = 4;
    const ERROR_CODE_NOT_EXIST = 5;
    const ERROR_CODE_NOT_NUMERIC = 6;
    const ERORO_CODE_CANOT_DELETE = 7;

    const ERROR_MSG_VALIDATION = '字段校验失败';
    const ERROR_MSG_SAVE = '保存失败';
    const ERROR_MSG_REPEAT = '重复提交修改';
    const ERROR_MSG_PARAMS_NULL = '参数为空';
    const ERROR_MSG_NOT_EXIST = '数据不存在';
    const ERROR_MSG_NOT_NUMERIC = '提交内容必须是数字';
    const ERORO_MSG_CANOT_DELETE = '不能软删除';

    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, static::CODE);
    }


    public function getName()
    {
        return 'ServiceException';
    }

    /**
     * 显示model数据验证失败错误信息
     * 示例： $this->getModelErrors($model->getErrors());
     * @param  array $modelErrors 错误信息数组
     * @return string
     */
    public static function getModelErrors($modelErrors)
    {
        $errorStr = "";
        if ($modelErrors && is_array($modelErrors)) {
            foreach ($modelErrors as $k => $error) {
                if (is_array($error)) {
                    $errorStr .= $k . ':' . implode("\n\n", $error) . ' ';
                } else {
                    $errorStr .= "Have Error!";
                }
            }
        }
        if (!$errorStr) {
            $errorStr = "Have error!";
        }
        return $errorStr;
    }
}
