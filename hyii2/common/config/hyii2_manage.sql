/*
Navicat MySQL Data Transfer

Source Server         : 120.26.214.48（hyii2）
Source Server Version : 50173
Source Host           : 120.26.214.48:3306
Source Database       : hyii2_manage

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2016-11-28 17:57:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('游客', '2', '1480227304');
INSERT INTO `auth_assignment` VALUES ('游客', '5', '1480325008');
INSERT INTO `auth_assignment` VALUES ('超级管理员', '1', '1479181380');

-- ----------------------------
-- Table structure for `auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', '2', null, null, null, '1479179771', '1479179771');
INSERT INTO `auth_item` VALUES ('/admin/*', '2', null, null, null, '1480237911', '1480237911');
INSERT INTO `auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1480324938', '1480324938');
INSERT INTO `auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1479180849', '1479180849');
INSERT INTO `auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1480076057', '1480076057');
INSERT INTO `auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1479180849', '1479180849');
INSERT INTO `auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1480324474', '1480324474');
INSERT INTO `auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1480312331', '1480312331');
INSERT INTO `auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1479180850', '1479180850');
INSERT INTO `auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/login', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/debug/*', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/ee', '2', null, null, null, '1480237917', '1480237917');
INSERT INTO `auth_item` VALUES ('/gii/*', '2', null, null, null, '1479180851', '1479180851');
INSERT INTO `auth_item` VALUES ('/site/*', '2', null, null, null, '1480189836', '1480189836');
INSERT INTO `auth_item` VALUES ('/site/error', '2', null, null, null, '1480189830', '1480189830');
INSERT INTO `auth_item` VALUES ('/site/index', '2', null, null, null, '1480189835', '1480189835');
INSERT INTO `auth_item` VALUES ('/site/login', '2', null, null, null, '1480237875', '1480237875');
INSERT INTO `auth_item` VALUES ('/site/logout', '2', null, null, null, '1480237909', '1480237909');
INSERT INTO `auth_item` VALUES ('/theme/*', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('/theme/index', '2', null, null, null, '1479898157', '1479898157');
INSERT INTO `auth_item` VALUES ('/theme/set-theme', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('/user/*', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('/user/create', '2', null, null, null, '1480315021', '1480315021');
INSERT INTO `auth_item` VALUES ('/user/delete', '2', null, null, null, '1480301515', '1480301515');
INSERT INTO `auth_item` VALUES ('/user/index', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('/user/signup', '2', null, null, null, '1480125909', '1480125909');
INSERT INTO `auth_item` VALUES ('/user/update', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('/user/view', '2', null, null, null, '1479898158', '1479898158');
INSERT INTO `auth_item` VALUES ('游客', '1', null, null, null, '1480227288', '1480227288');
INSERT INTO `auth_item` VALUES ('超级管理员', '1', null, null, null, '1479181344', '1479181344');

-- ----------------------------
-- Table structure for `auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/*');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/assignment/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/default/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/menu/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/permission/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/role/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/route/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/rule/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/admin/user/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/debug/default/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/site/*');
INSERT INTO `auth_item_child` VALUES ('游客', '/site/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/theme/index');
INSERT INTO `auth_item_child` VALUES ('游客', '/theme/set-theme');
INSERT INTO `auth_item_child` VALUES ('游客', '/user/*');
INSERT INTO `auth_item_child` VALUES ('游客', '/user/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '游客');

-- ----------------------------
-- Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '系统管理', '11', null, '1', 0x7B2269636F6E223A2266612066612D62617273227D);
INSERT INTO `menu` VALUES ('2', '菜单', '1', '/admin/menu/index', null, null);
INSERT INTO `menu` VALUES ('3', '路由', '1', '/admin/route/index', null, null);
INSERT INTO `menu` VALUES ('4', '权限管理', '11', null, '2', 0x7B2269636F6E223A2266612066612D636F67227D);
INSERT INTO `menu` VALUES ('5', '角色', '4', '/admin/role/index', null, null);
INSERT INTO `menu` VALUES ('6', '权限', '4', '/admin/permission/index', null, null);
INSERT INTO `menu` VALUES ('7', '授权', '4', '/admin/assignment/index', '3', null);
INSERT INTO `menu` VALUES ('8', '账户管理', '11', null, '3', 0x7B2269636F6E223A2266612066612D75736572227D);
INSERT INTO `menu` VALUES ('9', '管理员', '8', '/user/index', null, null);
INSERT INTO `menu` VALUES ('11', '系统', null, null, '1', 0x7B2269636F6E223A2266612066612D636F6773227D);
INSERT INTO `menu` VALUES ('12', '站点', null, null, '2', 0x7B2269636F6E223A2266612066612D74656C65766973696F6E227D);
INSERT INTO `menu` VALUES ('13', '模板管理', '12', null, '1', 0x7B2269636F6E223A2266612066612D636F7079227D);
INSERT INTO `menu` VALUES ('14', '模板', '13', '/theme/index', '1', null);

-- ----------------------------
-- Table structure for `migration`
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', '1477721561');
INSERT INTO `migration` VALUES ('m130524_201442_init', '1477721563');
INSERT INTO `migration` VALUES ('m161029_065330_test', '1477724186');
INSERT INTO `migration` VALUES ('m140602_111327_create_menu_table', '1478587286');
INSERT INTO `migration` VALUES ('m160312_050000_create_user', '1478587286');
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', '1478588784');

-- ----------------------------
-- Table structure for `test`
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of test
-- ----------------------------
INSERT INTO `test` VALUES ('1', '100', '1');
INSERT INTO `test` VALUES ('2', '10.2333', '1');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '7orB23ZwVsT7s72Z1M9UxPzCzyhQs9vD', '$2y$13$maRHC/tApxp3dcpMxa8otOAbTDofPkaFlYuJX4ioAahI1JJCsyxxi', null, 'test@qq.com', '10', '1477721582', '1480324921');
INSERT INTO `user` VALUES ('5', 'test', 'lDHK2g9bHnuZGt0Fd8hr_tDXmfdmziOa', '$2y$13$YRbCTyh83CLAFOv1Jb0ENONKDn72RiEU2vVBUINaWBlHlo3/LwZU6', null, '123456@qq.com', '10', '1480324999', '1480324999');
INSERT INTO `user` VALUES ('6', '123', 'cDPpUB2Qf-UoAIu6ZFPAnalnhXW0YQp_', '$2y$13$CudosMuwPBCZ82qFSaA.FeSzprJKCjeuKHvuWNFHF3VSIGJ28BKfq', null, '123123@qq.com', '10', '1480326648', '1480326648');
