<?php

namespace Service\DataOp\Models;

use Service\Base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "dept".
 *
 * @property integer $id
 * @property string $no
 * @property string $name
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_big
 * @property string $parent_no
 * @property string $country
 * @property string $area
 * @property string $inner_no
 * @property string $role
 * @property integer $datetime
 * @property string $service_proportion
 * @property string $consume_proportion
 * @property string $coins_web
 * @property string $coins
 * @property integer $sms_nums
 * @property string $bank
 * @property string $account
 * @property string $account_name
 * @property string $mobile
 * @property string $tel
 * @property string $address
 * @property string $postalcode
 * @property string $email
 * @property string $stock
 * @property string $order_credit
 * @property string $goods_credit
 * @property integer $award_flag
 * @property string $discount
 * @property integer $currency_id
 * @property integer $flag
 * @property integer $has_coins
 * @property string $user_no
 * @property string $intro_no
 */
class Dept extends ActiveRecord
{
    /**
     * 币种
     * @var
     */
    const CURRENCY_CHINA = 0;    // 人民币
    const CURRENCY_TAIWAN = 1;  // 台币

    /**
     * 代发奖金
     * @var
     */
    const AWARD_NOT = 0;    // 禁止
    const AWARD_ALLOW = 1;  // 允许


    /**
     * 网币核算
     * @var
     */
    const IGNORE_COINS = 0;    // 禁止
    const ACCOUNT_COINS = 1;  // 允许

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'area', 'datetime', 'stock', 'order_credit', 'goods_credit', 'currency_id', 'user_no', 'intro_no'], 'required'],
            [['area'], 'string'],
            [['datetime', 'sms_nums', 'award_flag', 'currency_id', 'flag', 'has_coins'], 'integer'],
            [['service_proportion', 'consume_proportion', 'coins_web', 'coins', 'stock', 'order_credit', 'goods_credit', 'discount'], 'number'],
            [['no', 'parent_no', 'mobile', 'tel', 'user_no', 'intro_no'], 'string', 'max' => 20],
            [['name', 'name_en', 'name_ru', 'name_big', 'email'], 'string', 'max' => 50],
            [['country', 'account_name', 'address'], 'string', 'max' => 100],
            [['inner_no', 'role', 'bank'], 'string', 'max' => 255],
            [['account'], 'string', 'max' => 30],
            [['postalcode'], 'string', 'max' => 10],
        ];
    }
    /**
     * 获取部门币种信息
     * @return mixed|null
     */
    public function getDeptCurrencyMsg()
    {
        $list = static::getDeptCurrencyList();
        return isset($list[$this->currency_id]) ? $list[$this->currency_id] : '--';
    }

    /**
     * 币种数组
     * @return array
     */
    public static function getDeptCurrencyList()
    {
        if (self::$_currencyList === null) {
            self::$_currencyList = [
                self::CURRENCY_CHINA => '人民币',
                self::CURRENCY_TAIWAN => '台币',
            ];
        }

        return self::$_currencyList;
    }
    /**
     * 获取部门代发奖金信息
     * @return mixed|null
     */
    public function getDeptAwardMsg()
    {
        $list = static::getDeptAwardList();
        return isset($list[$this->award_flag]) ? $list[$this->award_flag] : '--';
    }

    /**
     * 币种数组
     * @return array
     */
    public static function getDeptAwardList()
    {
        if (self::$_awardList === null) {
            self::$_awardList = [
                self::AWARD_NOT => '禁止',
                self::AWARD_ALLOW => '允许',
            ];
        }
        return self::$_awardList;
    }

    /**
     * 获取部门网币核算信息
     * @return mixed|null
     */
    public function getDeptCoinsMsg()
    {
        $list = static::getDeptCoinsList();
        return isset($list[$this->has_coins]) ? $list[$this->has_coins] : '--';
    }

    /**
     * 币种数组
     * @return array
     */
    public static function getDeptCoinsList()
    {
        if (self::$_coinsList === null) {
            self::$_coinsList = [
                self::IGNORE_COINS => '禁止',
                self::ACCOUNT_COINS => '允许',
            ];
        }
        return self::$_coinsList;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => '店铺编号',
            'name' => '店铺名称',
            'name_en' => '店铺英文名',
            'name_ru' => '店铺俄文名',
            'name_big' => '店铺繁体名',
            'parent_no' => '上级部门',
            'country' => '国家',
            'area' => '地区',
            'inner_no' => '内部编号',
            'role' => '角色范围',
            'datetime' => '添加日期',
            'service_proportion' => '报单服务费比例',
            'consume_proportion' => '消费服务费比例',
            'coins_web' => '部门网币',
            'coins' => '部门可用网币',
            'sms_nums' => '短信数量',
            'bank' => '开户行',
            'account' => '账户号',
            'account_name' => '账户名称',
            'mobile' => '手机号码',
            'tel' => '联系电话',
            'address' => '联系地址',
            'postalcode' => '邮编',
            'email' => '邮箱',
            'stock' => '股票账户',
            'order_credit' => '订单信用额度',
            'goods_credit' => '周转货信用额度',
            'award_flag' => '是否代发奖金',
            'discount' => '零售进货折扣',
            'currency_id' => '币种',
            'flag' => '标记',
            'has_coins' => '是否参与网币结算',
            'user_no' => '所属会员',
            'intro_no' => '所属推荐人',
        ];
    }
}

