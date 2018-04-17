<?php

namespace Common\Models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%vehicles}}".
 *
 * @property integer $id
 * @property string $plateNum
 * @property string $userName
 * @property string $mobile
 * @property string $idCard
 * @property string $ton
 * @property string $remark
 * @property integer $isDel
 * @property string $creater
 * @property integer $created
 * @property string $modifier
 * @property integer $modified
 */
class Vehicles extends \yii\db\ActiveRecord
{

    public $licensePlate;
    public $tonnage;
    public $remarks;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vehicles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['plateNum'], 'unique', 'message'=> '{value}已存在'],
            [['plateNum', 'userName', 'mobile'], 'required'],
            [['ton'], 'double'],
            [['created', 'modified', 'isDel'], 'integer'],
            [['plateNum'], 'string', 'max' => 7],
            [['plateNum'], 'match', 'pattern'=>'/^[\x{4e00}-\x{9fa5}][A-Z][a-z0-9]{5}/ui'],
            [['userName'], 'string', 'max' => 64],
            [['mobile'], 'match', 'pattern'=> '/(\(\d{3}-\d{7,8}|\d{4}-\d{7,8}\)|\d{3,4}-|\s)?\d{7,14}/', 'message'=> '号码格式不正确，正确格式如:固话:07xx-88888888, 手机 13xxxxxxxx'],
            [['idCard'], 'checkIdCard'],
            [['remark'], 'string', 'max' => 255],
            [['creater', 'modifier'], 'string', 'max' => 24],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'plateNum' => '车牌号',
            'userName' => '司机姓名',
            'mobile'   => '联系电话',
            'idCard'   => '身份证号',
            'ton'      => '吨位',
            'remark'   => '备注',
            'creater'  => '创建人',
            'created'  => '创建时间',
            'modifier' => '修改人',
            'modified' => '修改时间',
            'isDel'    => '删除状态:0未删除，1删除',
        ];
    }


    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->plateNum  = strtoupper($this->plateNum);

        return true;
    }


    /**
     * 身份证验证
     * @param $attribute
     * @return mixed
     */
    public function checkIdCard($attribute,$params)
    {
        if (strlen($this->idCard) == 18) {
            return $this->idcard_checksum18($this->idCard);
        } elseif ((strlen($this->idCard) == 15)) {
            $id_card = $this->idcard_15to18($this->idCard);
            return $this->idcard_checksum18($id_card);
        } else {
            $this->addError($attribute, '身份证号码不正确!');
        }
    }


    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     * @param $idcard_base
     * @return mixed
     */
    private function idcard_verify_number($idcard_base)
    {
        if (strlen($idcard_base) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum           = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod           = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    /**
     * 将15位身份证升级到18位
     * @param $idcard
     * @return mixed
     */
    private function idcard_15to18($idcard)
    {
        if (strlen($idcard) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
                $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . $this->idcard_verify_number($idcard);
        return $idcard;
    }

    /**
     * 18位身份证校验码有效性检查
     * @param $idcard
     * @return bool
     */
    private function idcard_checksum18($idcard)
    {
        if (strlen($idcard) != 18) {
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if ($this->idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除车辆信息
     * @param $id
     * @return bool
     * @throws \Exception
     */

    public static function del($ids)
    {

        if (empty($ids)) {
            return false;
        }
        $type=0;
        $errorHtml = '';
        foreach ($ids as $key => $val) {
            if (empty($errorHtml)) {
                self::getDel($val);
            }
        }
        return ['success' => !empty($errorHtml) ? false : true, 'msg' => !empty($errorHtml) ? $errorHtml : '删除成功', 'type' => $type, 'redirect' => Url::to(['index'])];
    }

    /**
     * 删除车辆信息
     * @param $id
     * @return bool
     * @throws \Exception
     */
    private static function getDel($id)
    {
        $model = self::findOne($id);
        $model->isDel = 1;
        if (!$model->save()) {
            throw new \Exception('删除<span style="color: red">' . $model->name . '</span>失败，原因：' . $model->getErrorStr() . '<br>');
        }

        return true;
    }
}
