<?php

use yii\db\Migration;

class m171120_014621_9569 extends Migration
{
    public function up()
    {
        $sql = "
CREATE TABLE `datadict` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `orders` int(11) DEFAULT NULL COMMENT '排序',
  `remark` varchar(200) DEFAULT NULL COMMENT '功能说明',
  `operator` varchar(200) NOT NULL COMMENT '开发人员',
  `datetime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='数据字典定义';
INSERT INTO `datadict` VALUES ('26', '客户来源渠道', '1', '客户来源渠道', 'admin', 1512199979);
INSERT INTO `datadict` VALUES ('27', '意向产品', '2', '意向产品', 'admin', 1512199979);
INSERT INTO `datadict` VALUES ('28', '喜爱风格', '3', '喜爱风格', 'admin', 1512199979);
INSERT INTO `datadict` VALUES ('29', '装修阶段', '4', '装修阶段', 'admin', 1512199979);
        ";
        $this->db->createCommand($sql)->execute();

        $sql = "
CREATE TABLE `datadict_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT NULL COMMENT '字段id',
  `name` varchar(200) NOT NULL COMMENT '名称',
  `fid` int(200) DEFAULT NULL COMMENT '父级Id',
  `remark` varchar(200) DEFAULT NULL COMMENT '描述',
  `orders` int(11) DEFAULT NULL COMMENT '排序',
  `flag` tinyint unsigned NOT NULL COMMENT '启用标记（0：启用；1：关闭）',
  `datetime` int(11) DEFAULT NULL COMMENT '添加时间',
  `operator` varchar(200) DEFAULT NULL COMMENT '操作人',
  PRIMARY KEY (`id`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='数据字典明细';
INSERT INTO `datadict_detail` VALUES ('17', '26', '线下广告', null, '线下广告', '1', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('18', '26', 'T牌', '17', 'T牌', '2', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('19', '26', '电梯广告', '17', '电梯广告', '3', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('20', '26', '宣传单张', '17', '宣传单张', '4', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('21', '26', '合作资源', null, '合作资源', '5', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('22', '26', '装修公司介绍', '21', '装修公司介绍', '6', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('23', '26', '朋友介绍', '21', '朋友介绍', '7', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('24', '26', '银行合作', '21', '银行合作', '7', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('25', '26', '线上推广', null, '线上推广', '8', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('26', '26', '微信平台', '25', '微信平台', '9', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('27', '26', '今日头条', '25', '今日头条', '9', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('28', '26', '其他', null, '其他', '10', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('29', '27', '主材包', null, '主材包', '11', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('30', '27', '家具包', null, '家具包', '12', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('31', '27', '整装包', null, '整装包', '13', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('32', '28', '简约', null, '简约', '13', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('33', '28', '现代', null, '现代', '14', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('34', '29', '准备装修', null, '准备装修', '15', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('35', '29', '正在装修', null, '正在装修', '15', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('36', '26', '报纸', '17', '报纸', '16', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('37', '26', '媒体合作', '21', '媒体合作', '17', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('38', '26', '官网', '25', '官网', '10', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('39', '26', 'app', '25', 'app', '11', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('40', '26', '第三方商城', '25', '第三方商城', '12', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('41', '28', '中式', null, '中式', '15', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('42', '28', '美式', null, '美式', '16', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('43', '28', '田园', null, '田园', '17', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('44', '28', '地中海', null, '地中海', '18', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('45', '28', '其他', null, '其他', '19', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('46', '29', '装修完成', null, '装修完成', '16', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('47', '29', '已入住', null, '已入住', '19', 0, 1512199979, 'admin');
INSERT INTO `datadict_detail` VALUES ('48', '26', '其他', '28', '其他', '1', 0, 1512199979, 'admin');
        ";
        $this->db->createCommand($sql)->execute();
    }

    public function down()
    {
        echo "m171120_014621_9569 cannot be reverted.\n";

        return false;
    }

    /*
// Use safeUp/safeDown to run migration code within a transaction
public function safeUp()
{
}

public function safeDown()
{
}
 */
}
