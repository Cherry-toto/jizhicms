<?php die();?>/*
MySQL Database Backup Tools
Server:127.0.0.1:3306
Database:jz164test
Data:2019-12-31 19:12:45
*/
SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for jz_article
-- ----------------------------
DROP TABLE IF EXISTS `jz_article`;
CREATE TABLE `jz_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `molds` varchar(50) DEFAULT NULL,
  `htmlurl` varchar(50) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `litpic` varchar(255) DEFAULT NULL,
  `body` text,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `orders` int(4) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '1',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `istop` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `ishot` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否头条',
  `istuijian` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `tags` varchar(255) DEFAULT NULL,
  `member_id` int(11) NOT NULL DEFAULT '0',
  `target`  varchar(255) DEFAULT NULL,
  `ownurl`  varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_buylog
-- ----------------------------
DROP TABLE IF EXISTS `jz_buylog`;
CREATE TABLE `jz_buylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  `orderno` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `buytype` varchar(20) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `molds` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_classtype
-- ----------------------------
DROP TABLE IF EXISTS `jz_classtype`;
CREATE TABLE `jz_classtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(50) DEFAULT NULL,
  `seo_classname` varchar(50) DEFAULT NULL,
  `molds` varchar(50) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `body` text,
  `orders` int(4) NOT NULL DEFAULT '0',
  `orderstype` int(4) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '1',
  `iscover` tinyint(1) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '访问分组设定，默认0不设定',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目url命名',
  `lists_html` varchar(50) DEFAULT NULL COMMENT '栏目页HTML',
  `details_html` varchar(50) DEFAULT NULL COMMENT '详情页HTML',
  `lists_num` int(4) DEFAULT '0',
  `comment_num` int(11) NOT NULL DEFAULT '0',
  `gourl` varchar(255) DEFAULT NULL COMMENT '栏目外链',
  `ishome` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否会员发布显示',
  `isclose` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_collect
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect`;
CREATE TABLE `jz_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL,
  `w` varchar(10) NOT NULL DEFAULT '0',
  `h` varchar(10) NOT NULL DEFAULT '0',
  `orders` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_collect_type
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect_type`;
CREATE TABLE `jz_collect_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_comment
-- ----------------------------
DROP TABLE IF EXISTS `jz_comment`;
CREATE TABLE `jz_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(4) NOT NULL DEFAULT '0' COMMENT '栏目tid',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '回复帖子id',
  `zid` int(11) NOT NULL DEFAULT '0' COMMENT '主回复帖子，同一层楼内回复，规定主回复id',
  `body` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id，0表示游客',
  `likes` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '喜欢，点赞',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否删除，1未删除，0删除',
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `aid` (`aid`),
  KEY `pid` (`pid`),
  KEY `zid` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_fields
-- ----------------------------
DROP TABLE IF EXISTS `jz_fields`;
CREATE TABLE `jz_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL,
  `molds` varchar(50) DEFAULT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  `tips` varchar(100) DEFAULT NULL,
  `fieldtype` tinyint(2) NOT NULL DEFAULT '1',
  `tids` text COMMENT '栏目tid',
  `fieldlong` varchar(50) DEFAULT NULL,
  `body` text,
  `orders` int(11) NOT NULL DEFAULT '1',
  `ismust` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1必须填写0不必',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1前台显示0不显示',
  `isadmin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1后台显示0不显示',
  `issearch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1显示搜索，0不显示搜索',
  `islist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否列表中显示',
  `format` varchar(50) DEFAULT NULL COMMENT '显示格式化',
  `vdata` varchar(50) DEFAULT NULL COMMENT '字段默认值',
  `isajax` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_hook
-- ----------------------------
DROP TABLE IF EXISTS `jz_hook`;
CREATE TABLE `jz_hook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) DEFAULT NULL COMMENT '模块，Home/A',
  `namespace` varchar(100) DEFAULT NULL COMMENT '控制器命名空间',
  `controller` varchar(50) DEFAULT NULL COMMENT '控制器',
  `action` varchar(255) DEFAULT NULL COMMENT '方法,可同时注册多个方法，逗号拼接',
  `hook_namespace` varchar(100) DEFAULT NULL COMMENT '钩子控制器所在的命名空间',
  `hook_controller` varchar(50) DEFAULT NULL COMMENT '钩子控制器',
  `hook_action` varchar(50) DEFAULT NULL COMMENT '钩子执行方法',
  `all_action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全局控制器',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序，越大越靠前执行',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '插件是否关闭，默认0关闭',
  `plugins_name` varchar(50) DEFAULT NULL COMMENT '关联插件名',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件钩子';
-- ----------------------------
-- Table structure for jz_layout
-- ----------------------------
DROP TABLE IF EXISTS `jz_layout`;
CREATE TABLE `jz_layout` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `top_layout` text,
  `left_layout` text,
  `gid` int(11) DEFAULT NULL,
  `ext` varchar(255) DEFAULT NULL,
  `sys` tinyint(1) NOT NULL DEFAULT '0',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1系统默认布局',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_level
-- ----------------------------
DROP TABLE IF EXISTS `jz_level`;
CREATE TABLE `jz_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `gid` int(4) NOT NULL DEFAULT '2',
  `email` varchar(50) DEFAULT NULL,
  `regtime` int(11) NOT NULL DEFAULT '0',
  `logintime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_level_group
-- ----------------------------
DROP TABLE IF EXISTS `jz_level_group`;
CREATE TABLE `jz_level_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员，最高权限，无视控制器限制',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0',
  `classcontrol` tinyint(1) NOT NULL DEFAULT '0',
  `paction` text COMMENT '动作参数，控制器/方法，如Admin/index',
  `tids` text,
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1允许登录0不允许',
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_links
-- ----------------------------
DROP TABLE IF EXISTS `jz_links`;
CREATE TABLE `jz_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `molds` varchar(255) DEFAULT 'links',
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `isshow` tinyint(1) DEFAULT '1',
  `userid` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  `htmlurl` varchar(50) DEFAULT NULL,
  `orders` int(11) NOT NULL DEFAULT '0',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `target` varchar(255) DEFAULT NULL,
  `ownurl` varchar(255) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_link_type
-- ----------------------------
DROP TABLE IF EXISTS `jz_link_type`;
CREATE TABLE `jz_link_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_member
-- ----------------------------
DROP TABLE IF EXISTS `jz_member`;
CREATE TABLE `jz_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL COMMENT '记住密码或者忘记密码使用',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1男2女0未知',
  `gid` int(11) NOT NULL DEFAULT '1' COMMENT '分组ID',
  `litpic` varchar(255) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `jifen` decimal(10,2) NOT NULL DEFAULT '0.00',
  `likes` text COMMENT '喜欢/点赞的文章id,||tid-id||tid-id||',
  `collection` text COMMENT '收藏存储，||tid-id||tid-id||',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `regtime` int(11) NOT NULL DEFAULT '0',
  `logintime` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '1',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `birthday` varchar(25) DEFAULT NULL COMMENT '生日：2020-01-01',
  `follow` text COMMENT '关注列表',
  `fans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `ismsg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收消息提醒',
  `iscomment` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收评论消息提醒',
  `iscollect` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收收藏消息提醒',
  `islikes` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收点赞消息提醒',
  `isat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收@消息提醒',
  `isrechange` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启接收交易消息提醒',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐用户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_member_group
-- ----------------------------
DROP TABLE IF EXISTS `jz_member_group`;
CREATE TABLE `jz_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '分组名',
  `description` varchar(255) DEFAULT NULL COMMENT '分组简介',
  `paction` text COMMENT '权限',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '分组上级',
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许登录',
  `iscomment` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许评论',
  `ischeckmsg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要审核评论',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `orders` int(11) NOT NULL DEFAULT '0',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣价，现金折扣或者百分比折扣',
  `discount_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0无折扣,1现金折扣,1百分比折扣',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员分组';
-- ----------------------------
-- Table structure for jz_message
-- ----------------------------
DROP TABLE IF EXISTS `jz_message`;
CREATE TABLE `jz_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `tid` int(4) NOT NULL DEFAULT '0',
  `aid` int(11) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `body` text,
  `tel` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `orders` int(4) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  `istop` tinyint(1) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_molds
-- ----------------------------
DROP TABLE IF EXISTS `jz_molds`;
CREATE TABLE `jz_molds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `biaoshi` varchar(50) DEFAULT NULL,
  `sys` tinyint(1) NOT NULL DEFAULT '0',
  `isopen` tinyint(1) NOT NULL DEFAULT '1',
  `iscontrol` tinyint(1) NOT NULL DEFAULT '0',
  `ismust` tinyint(1) NOT NULL DEFAULT '0',
  `isclasstype` tinyint(1) NOT NULL DEFAULT '1',
  `isshowclass` tinyint(1) DEFAULT 1,
  `list_html` varchar(50) DEFAULT 'list.html',
  `details_html` varchar(50) DEFAULT 'details.html',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_orders
-- ----------------------------
DROP TABLE IF EXISTS `jz_orders`;
CREATE TABLE `jz_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `paytype` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `ptype` tinyint(1) DEFAULT '1' COMMENT '1商品购买2充值金额3充值积分',
  `tel` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `price` varchar(200) DEFAULT NULL,
  `jifen` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qianbao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `body` text,
  `receive_username` varchar(50) DEFAULT NULL,
  `receive_tel` varchar(20) DEFAULT NULL,
  `receive_email` varchar(50) DEFAULT NULL,
  `receive_address` varchar(255) DEFAULT NULL,
  `ispay` tinyint(1) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1提交订单,2已支付,3超时,4已提交订单,5已发货,6已废弃失效,0删除订单',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `yunfei` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_pictures
-- ----------------------------
DROP TABLE IF EXISTS `jz_pictures`;
CREATE TABLE `jz_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `aid` int(11) NOT NULL DEFAULT '0',
  `molds` varchar(50) DEFAULT NULL,
  `path` varchar(20) DEFAULT 'Admin',
  `filetype` varchar(20) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片集';
-- ----------------------------
-- Table structure for jz_plugins
-- ----------------------------
DROP TABLE IF EXISTS `jz_plugins`;
CREATE TABLE `jz_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `filepath` varchar(50) DEFAULT NULL COMMENT '插件文件名',
  `description` varchar(255) DEFAULT NULL,
  `version` decimal(2,1) NOT NULL DEFAULT '0.0',
  `author` varchar(50) DEFAULT NULL,
  `update_time` int(11) NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL DEFAULT 'Home',
  `isopen` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `config` text COMMENT '配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_power
-- ----------------------------
DROP TABLE IF EXISTS `jz_power`;
CREATE TABLE `jz_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开放',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='用户权限表';
-- ----------------------------
-- Table structure for jz_product
-- ----------------------------
DROP TABLE IF EXISTS `jz_product`;
CREATE TABLE `jz_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `molds` varchar(20) DEFAULT 'product',
  `title` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `htmlurl` varchar(50) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL COMMENT '首图',
  `stock_num` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pictures` text COMMENT '图集',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示,0不显示',
  `comment_num` int(11) NOT NULL DEFAULT '0',
  `body` text COMMENT '内容',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '录入管理员',
  `orders` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `istop` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `ishot` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否头条',
  `istuijian` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `tags` varchar(255) DEFAULT NULL,
  `member_id` int(11) NOT NULL DEFAULT '0',
  `target` varchar(255) DEFAULT NULL,
  `ownurl` varchar(255) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `brands` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `display` varchar(255) DEFAULT NULL,
  `camera` varchar(255) DEFAULT NULL,
  `ram` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='商品表';
-- ----------------------------
-- Table structure for jz_ruler
-- ----------------------------
DROP TABLE IF EXISTS `jz_ruler`;
CREATE TABLE `jz_ruler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `fc` varchar(50) DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `isdesktop` tinyint(1) NOT NULL DEFAULT '0',
  `sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1系统自带0不是系统自带',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=184 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_sysconfig
-- ----------------------------
DROP TABLE IF EXISTS `jz_sysconfig`;
CREATE TABLE `jz_sysconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL COMMENT '配置字段',
  `title` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `tip` varchar(255) DEFAULT NULL COMMENT '字段填写提示',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配置类型,0系统配置,1图片类型,2输入框,3简短文字',
  `data` text COMMENT '配置内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_tags
-- ----------------------------
DROP TABLE IF EXISTS `jz_tags`;
CREATE TABLE `jz_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  `molds` varchar(50) DEFAULT 'tags',
  `orders` int(11) DEFAULT '0',
  `comment_num` int(11) DEFAULT '0',
  `htmlurl` varchar(100) DEFAULT NULL,
  `keywords` varchar(50) DEFAULT NULL,
  `newname` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `num` int(4) DEFAULT '-1',
  `isshow` tinyint(1) DEFAULT '1',
  `target` varchar(50) DEFAULT '_blank',
  `number` int(11) DEFAULT '0',
  `member_id` int(11) DEFAULT '0',
  `ownurl` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_task
-- ----------------------------
DROP TABLE IF EXISTS `jz_task`;
CREATE TABLE `jz_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0',
  `aid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  `puserid` int(11) DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `isread` tinyint(1) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '1',
  `readtime` int(11) DEFAULT '0',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_page
-- ----------------------------
DROP TABLE IF EXISTS `jz_page`;
CREATE TABLE `jz_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `htmlurl` varchar(50) DEFAULT NULL,
  `orders` int(11) NOT NULL DEFAULT '0',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '1',
  `istop` tinyint(1) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_customurl
-- ----------------------------
DROP TABLE IF EXISTS `jz_customurl`;
CREATE TABLE `jz_customurl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `molds` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `aid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_menu
-- ----------------------------
DROP TABLE IF EXISTS `jz_menu`;
CREATE TABLE `jz_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `nav` text DEFAULT NULL,
  `isshow`tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_cachedata
-- ----------------------------
DROP TABLE IF EXISTS `jz_cachedata`;
CREATE TABLE `jz_cachedata` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `molds` varchar(50) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `isall`tinyint(1) NOT NULL DEFAULT '1',
  `sqls` varchar(500) DEFAULT NULL,
  `orders` varchar(255) DEFAULT NULL,
  `limits`int(11) NOT NULL DEFAULT '10',
  `times`int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_article
-- ----------------------------
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('13','极致CMS新闻测试标题1','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','1','15','1','0','1','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('12','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','8','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('11','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('4','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('10','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('9','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('7','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('8','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','12','7','1','0','0','0','1', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('14','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('15','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('16','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('17','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','1','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('18','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('19','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('20','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','2','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('21','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','22','21','1','0','0','1','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('22','极致CMS需要付费授权吗？','14','article','faq','极致CMS','极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。','极致CMS_建站系统_免费建站系统_建站CMS_需要付费授权吗？','1', NULL,'<p>极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。</p>','1566150298','11','5','1','0','0','0','1', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('23','极致CMS建站对比其他建站系统，有什么特别明显的优势？','14','article','faq','极致CMS','模板标签简单易学易用，对整个系统权限控制比较完整，系统自由度很高，如果你熟悉她整个架构，那么她可以更改成很多类型的系统，不仅仅是建站系统，她可以是购物网站，她也可以是问答社区等等，自由发挥~','极致CMS建站对比其他建站系统，有什么特别明显的优势？','1', NULL,'<p>模板标签简单易学易用，对整个系统权限控制比较完整，系统自由度很高，如果你熟悉她整个架构，那么她可以更改成很多类型的系统，不仅仅是建站系统，她可以是购物网站，她也可以是问答社区等等，自由发挥~</p>','1566150677','0','5','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('24','极致CMS需要付费授权吗？','14','article','faq','极致CMS','极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。','极致CMS_建站系统_免费建站系统_建站CMS_需要付费授权吗？','1', NULL,'<p>极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。</p>','1566150298','11','6','1','0','0','0','0', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('25','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','2','8','1','0','0','0','1', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('26','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','12','24','1','0','0','0','1', NULL,'0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`) VALUES ('28','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','12','22','1','0','0','0','1', NULL,'0');
-- ----------------------------
-- Records of jz_buylog
-- ----------------------------
-- ----------------------------
-- Records of jz_classtype
-- ----------------------------
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('1','商品','product', NULL, NULL, NULL, NULL,'0','1','0','0','0','shangpin','list','details','9','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('2','新闻','article', NULL, NULL, NULL, NULL,'0','1','0','0','0','xinwen','article-list','article-details','4','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('3','联系','article', NULL, NULL, NULL,'<p style="text-align: center;">这是后台录入的内容~</p><p style="text-align: center;">这很简单的吧？赶紧去后台试试吧！</p>','0','1','0','0','0','lianxi','contact-us', NULL,'10','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('4','留言','message', NULL, NULL, NULL, NULL,'0','1','0','0','0','msg','message', NULL,'10','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('5','关于','article', NULL, NULL, NULL,'<p>极致建站系统是一个简单快捷高效的建站CMS，我们的宗旨是追求极致，极力为大众打造一个更好、更快、更方便的建站系统。</p><p>极致建站系统有几大核心优势：</p><p>1、官方手把手指导教学</p><p>2、自由开发，免商业授权</p><p>3、后台自由定制功能展示桌面，方便应对不同客户需求</p><p>4、用户无需自己手动配置伪静态，也支持配置各种格式的自定义链接</p><p>5、对于手机端、Ajax、接口访问数据处理非常友好</p><p>6、对数据输出的调用是完全自由公开的，即你可以在前台输出数据库里面存储的任何数据</p><p>7、自带静态数据缓存，无需更新生成静态页面也能达到静态访问效率</p><p>8、上传图片管理，缓存清理等，方便管理服务器的文件</p><p>9、具备完整的评论点评功能，用户可以在前台配置评论，点评商品</p><p>10、自带一套简单的购物流程</p><p>11、官方提供支付接口，只需简单获得官方授权，即可体验网站支付功能</p><p>12、系统开发自由度相当高，扩展方便</p>','0','1','0','0','0','guanyu','about-us', NULL,'10','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('6','分类一','product', NULL, NULL, NULL, NULL,'0','1','1','1','0','fenleiyi','list','details','9','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('7','分类二','product', NULL, NULL, NULL, NULL,'0','1','1','1','0','fenleier','list','details','9','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('8','分类三','product', NULL, NULL, NULL, NULL,'0','1','1','1','0','fenleisan','list','details','9','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('9','分类四','product', NULL, NULL, NULL, NULL,'0','1','1','1','0','fenleisi','list','details','9','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('10','新闻分类一','article', NULL, NULL, NULL, NULL,'0','1','0','2','0','xinwenfenleiyi','article-list','article-details','4','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('11','新闻分类二','article', NULL, NULL, NULL, NULL,'0','1','0','2','0','xinwenfenleier','article-list','article-details','4','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('12','新闻分类三','article', NULL, NULL, NULL, NULL,'0','1','0','2','0','xinwenfenleisan','article-list','article-details','4','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('13','新闻分类四','article', NULL, NULL, NULL, NULL,'0','1','0','2','0','xinwenfenleisi','article-list','article-details','4','0', NULL,'1');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`,`gourl`,`ishome`) VALUES ('14','常见问题','article', NULL, NULL, NULL, NULL,'0','1','0','0','0','faq','faq','article-details','10','0', NULL,'1');
-- ----------------------------
-- Records of jz_collect
-- ----------------------------
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('1','测试1','测试','1','/static/default/assets/img/scenery/image1.jpg','0','0','0','1565353707','1', NULL);
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('2','测试二', NULL,'1','/static/default/assets/img/scenery/image4.jpg','0','0','0','1565353751','1', NULL);
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('3','测试三', NULL,'1','/static/default/assets/img/scenery/image6.jpg','0','0','0','1565353774','1', NULL);
-- ----------------------------
-- Records of jz_collect_type
-- ----------------------------
INSERT INTO `jz_collect_type` (`id`,`name`,`addtime`) VALUES ('1','首页轮播图','1565353707');
-- ----------------------------
-- Records of jz_comment
-- ----------------------------
-- ----------------------------
-- Records of jz_fields
-- ----------------------------
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('1','url','links','链接', NULL,'1', NULL,'255', NULL,'0','1','1','1','0','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('2','title','links','链接名称', NULL,'1', NULL,'255', NULL,'0','1','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('3','email','message','联系邮箱', NULL,'1', NULL,'255', NULL,'0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('4','keywords','tags','关键词','尽量简短，但不能重复','1', NULL,'50', NULL,'0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('5','newname','tags','替换词','尽量简短，但不能重复，20字以内，可不填。','1', NULL,'50', NULL,'0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('6','url','tags','内链','填写详细链接，带http','1', NULL,'255', NULL,'0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('7','num','tags','替换次数','一篇文章内替换的次数，默认-1，全部替换','4', NULL,'4', NULL,'0','0','1','1','0','1', NULL,'-1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('8','target','tags','打开方式', NULL,'7', NULL,'50','新窗口=_blank,本窗口=_self','0','0','1','1','0','1', NULL,'_blank');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('9','number','tags','标签数','无需填写，程序自动处理','4', NULL,'11', NULL,'0','0','1','1','0','1', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('10','member_id','article','发布用户','前台会员，无需填写','13', NULL,'11','3,username','0','0','0','0','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('11','member_id','product','发布用户','前台会员，无需填写','13', NULL,'11','3,username','0','0','0','0','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('12','member_id','links','发布用户','前台会员，无需填写','13', NULL,'11','3,username','0','0','0','0','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('13','categories','product','类别','商品类别','7',',1,6,7,8,9,2,10,11,12,13,3,4,5,', NULL,'Phones=1,Laptops=2,PC=3,Tablets=4','0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('14','brands','product','品牌', NULL,'7',',1,6,7,8,9,2,10,11,12,13,3,4,5,', NULL,'Samsung=1,Apple=2,HTC=3','0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('15','os','product','操作系统', NULL,'7',',1,6,7,8,9,2,10,11,12,13,3,4,5,', NULL,'Android=1,iOS=2','0','0','1','1','1','1', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('16','display','product','显示屏', NULL,'1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','10', NULL,'0','1','1','1','0','0', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('17','camera','product','像素', NULL,'1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','20', NULL,'0','1','1','1','0','0', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('18','ram','product','运行内存', NULL,'1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','20', NULL,'0','1','1','1','0','0', NULL, NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('19','target','links','外链URL','默认为空，系统访问内容则直接跳转到此链接','1', NULL,'255',NULL,'0','0','0','0','0','0', NULL,NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('20','ownurl','links','自定义URL','默认为空，自定义URL','1', NULL,'255',NULL,'0','0','0','0','0','0', NULL,NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('21','ownurl','tags','自定义URL','默认为空，自定义URL','1', NULL,'255',NULL,'0','0','0','0','0','0', NULL,NULL);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('22','addtime','links','添加时间','系统自带','11', NULL,'11',NULL,'0','0','0','0','0','0', 'date_2','0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`) VALUES ('23','addtime','tags','添加时间','系统自带','11', NULL,'11',NULL,'0','0','0','0','0','0', 'date_2','0');
-- ----------------------------
-- Records of jz_hook
-- ----------------------------
-- ----------------------------
-- Records of jz_layout
-- ----------------------------
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('1','系统默认','[]','[{"name":"内容管理","icon":"&amp;#xe6b4;","nav":["9","105"]},{"name":"栏目管理","icon":"&amp;#xe699;","nav":["42"]},{"name":"互动管理","icon":"&amp;#xe69b;","nav":["22","16"]},{"name":"SEO设置","icon":"&amp;#xe6b3;","nav":["147","95","153"]},{"name":"用户管理","icon":"&amp;#xe6b8;","nav":["2","118","123","54","49","66","129","177"]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":["40","70","190","83","89","114"]},{"name":"扩展管理","icon":"&amp;#xe6ce;","nav":["76","116","61","35","194","141","142","143","154","115"]}]','0','CMS默认配置，不可删除！','1','1');
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('2','旧版桌面','[]','[{"name":"网站管理","icon":"&amp;#xe699;","nav":["42","9","95","83","147","22"]},{"name":"商品管理","icon":"&amp;#xe698;","nav":["105","129","2","118","123","16","177"]},{"name":"扩展管理","icon":"&amp;#xe6ce;","nav":["76","116","141","142","143","194","35","61","154","153"]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":["40","54","49","190","70","115","114","66"]}]','0','旧版本配置','0','0');
-- ----------------------------
-- Records of jz_level
-- ----------------------------
INSERT INTO `jz_level` (`id`,`name`,`pass`,`tel`,`gid`,`email`,`regtime`,`logintime`,`status`) VALUES ('1','admin','0acdd3e4a8a2a1f8aa3ac518313dab9d','13600136000','1','123456@qq.com','1577760166','1577785245','1');
-- ----------------------------
-- Records of jz_level_group
-- ----------------------------
INSERT INTO `jz_level_group` (`id`,`name`,`isadmin`,`paction`,`isagree`,`description`) VALUES ('1','超级管理员','1',',Fields,','1', NULL);
-- ----------------------------
-- Records of jz_links
-- ----------------------------
-- ----------------------------
-- Records of jz_member
-- ----------------------------
-- ----------------------------
-- Records of jz_member_group
-- ----------------------------
INSERT INTO `jz_member_group` (`id`,`name`,`description`,`paction`,`pid`,`isagree`,`iscomment`,`ischeckmsg`,`addtime`,`orders`,`discount`,`discount_type`) VALUES ('1','注册会员','前台会员分组，最低等级分组',',Message,Comment,User,Order,Home,Common,','0','1','1','1','0','0','0.00','0');
-- ----------------------------
-- Records of jz_message
-- ----------------------------
-- ----------------------------
-- Records of jz_molds
-- ----------------------------
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`,`list_html`,`details_html`) VALUES ('1','内容','article','1','1','1','1','1','1','article-list.html','article-details.html');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('2','栏目','classtype','1','1','1','1','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('3','会员','member','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('4','订单','orders','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('5','商品','product','1','1','1','1','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('6','会员分组','member_group','1','1','1','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('7','评论','comment','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`,`list_html`) VALUES ('8','留言','message','1','1','1','0','0','1','message.html');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('9','轮播图','collect','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('10','友情链接','links','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('11','管理员','level','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`) VALUES ('12','TAG','tags','1','1','0','0','0','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`isclasstype`,`ismust` ,`iscontrol`,`isshowclass`,`list_html`) VALUES ('13','单页','page','1','1','1','1','1','1','page.html');
-- ----------------------------
-- Records of jz_orders
-- ----------------------------
-- ----------------------------
-- Records of jz_pictures
-- ----------------------------
-- ----------------------------
-- Records of jz_plugins
-- ----------------------------
-- ----------------------------
-- Records of jz_power
-- ----------------------------
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('1','Common','公共权限','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('2','Home','前台网站','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('3','User','个人中心','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('4','Login','会员登录','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('5','Message','站内留言','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('6','Comment','会员评论','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('7','Screen','网站筛选','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('8','Order','会员下单','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('9','Mypay','网站支付','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('10','Jzpay','极致支付','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('11','Tags','TAG标签','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('12','Wechat','微信模块','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('13','Common/vercode','验证码生成','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('14','Common/checklogin','检查是否登录','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('15','Common/multiuploads','多附件上传','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('16','Common/uploads','单附件上传','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('17','Common/qrcode','二维码生成','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('18','Common/get_fields','获取扩展信息','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('19','Common/jizhi','链接错误提示','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('20','Common/error','报错提示','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('21','Home/index','网站首页','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('22','Home/jizhi','网站内容','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('23','Home/auto_url','自定义链接','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('24','Home/jizhi_details','详情内容','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('25','Home/search','网站搜索','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('26','Home/searchAll','网站多模块搜索','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('27','Home/start_cache','开启网站缓存','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('28','Home/end_cache','输出缓存','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('29','User/checklogin','检查是否登录','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('30','User/index','个人中心首页','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('31','User/userinfo','会员资料','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('32','User/orders','订单记录','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('33','User/orderdetails','订单详情','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('34','User/payment','订单支付','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('35','User/orderdel','删除订单','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('36','User/changeimg','上传头像','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('37','User/comment','评论列表','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('38','User/commentdel','删除评论','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('39','User/likesAction','点赞文章','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('40','User/likes','点赞列表','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('41','User/likesdel','取消点赞','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('42','User/collectAction','收藏文章','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('43','User/collect','收藏列表','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('44','User/collectdel','删除收藏','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('45','User/cart','购物车','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('46','User/addcart','添加购物车','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('47','User/delcart','删除购物车','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('48','User/posts','发布管理','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('49','User/release','会员发布','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('50','User/del','删除发布','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('51','User/uploads','会员上传附件','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('52','User/jizhi','404提示','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('53','User/follow','关注用户','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('54','User/nofollow','取消关注','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('55','User/fans','粉丝列表','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('56','User/notify','消息提醒','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('57','User/notifyto','查看消息','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('58','User/notifydel','删除消息','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('59','User/active','公共主页','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('60','User/setmsg','消息提醒设置','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('61','User/getclass','获取栏目列表','2','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('62','User/wallet','用户钱包','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('63','User/buy','会员充值','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('64','User/buylist','充值列表','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('65','User/buydetails','交易详情','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('66','Login/index','登录首页','4','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('67','Login/register','注册页面','4','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('68','Login/forget','忘记密码','4','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('69','Login/nologin','未登录页面','4','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('70','Login/loginout','退出登录','4','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('71','Message/index','发送留言','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('72','Comment/index','发表评论','6','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('73','Screen/index','筛选列表','7','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('74','Order/create','创建订单','8','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('75','Order/pay','订单支付','8','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('76','Tags/index','TAG标签列表','11','1');
-- ----------------------------
-- Records of jz_product
-- ----------------------------
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('1','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('2','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('3','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('4','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('5','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('6','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('7','极致商品模块测试DEMO','极致商品模块测试DEMO','13','0','xinwenfenleisi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('8','极致商品模块测试DEMO','极致商品模块测试DEMO','13','0','xinwenfenleisi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('9','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('10','极致商品模块测试DEMO','极致商品模块测试DEMO','6','5','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('11','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('12','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('13','极致商品模块测试DEMO','极致商品模块测试DEMO','7','5','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','0','0', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('14','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('15','极致商品模块测试DEMO','极致商品模块测试DEMO','8','3','fenleisan','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('16','极致商品模块测试DEMO','极致商品模块测试DEMO','8','3','fenleisan','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('17','极致商品模块测试DEMO','极致商品模块测试DEMO','6','4','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('18','极致商品模块测试DEMO','极致商品模块测试DEMO','6','4','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('19','极致商品模块测试DEMO','极致商品模块测试DEMO','7','5','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('20','极致商品模块测试DEMO','极致商品模块测试DEMO','7','5','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','1','1565359153','0','0','1', NULL,'0','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('21','极致商品模块测试DEMO','极致商品模块测试DEMO','7','6','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','1','1565359153','0','1','1', NULL,'0','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('22','极致商品模块测试DEMO','极致商品模块测试DEMO','6','45','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','1', NULL,'0','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`istop`,`ishot`,`istuijian`,`tags`,`member_id`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('23','极致商品模块测试DEMO','极致商品模块测试DEMO','13','0','xinwenfenleisi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','0','0','0', NULL,'0','4','3','1','5.2寸','12MP','4GB');
-- ----------------------------
-- Records of jz_ruler
-- ----------------------------
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('1','会员管理','Member','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('2','会员列表','Member/index','1','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('3','添加会员','Member/memberadd','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('4','修改会员','Member/memberedit','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('5','删除会员','Member/member_del','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('6','批量删除','Member/deleteAll','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('7','修改状态','Member/change_status','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('8','内容管理','Article','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('9','内容列表','Article/articlelist','8','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('10','添加内容','Article/addarticle','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('11','修改内容','Article/editarticle','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('12','删除内容','Article/deletearticle','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('13','批量删除','Article/deleteAll','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('14','复制内容','Article/copyarticle','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('15','评论管理','Comment','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('16','评论列表','Comment/commentlist','15','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('17','添加评论','Comment/addcomment','15','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('18','修改评论','Comment/editcomment','15','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('19','删除评论','Comment/deletecomment','15','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('20','批量删除','Comment/deleteAll','15','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('21','留言管理','Message','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('22','留言列表','Message/messagelist','21','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('23','修改留言','Message/editmessage','21','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('24','删除留言','Message/deletemessage','21','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('25','批量删除','Message/deleteAll','21','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('26','字段管理','Fields','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('27','字段列表','Fields/index','26','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('28','新增字段','Fields/addFields','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('29','修改字段','Fields/editFields','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('30','删除字段','Fields/deleteFields','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('31','获取字段','Fields/get_fields','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('32','基本功能','Index','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('33','系统界面','Index/index','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('34','后台首页','Index/welcome','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('35','数据库备份','Index/beifen','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('36','数据库备份','Index/backup','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('37','数据库还原','Index/huanyuan','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('38','数据库删除','Index/shanchu','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('39','系统设置','Sys','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('40','基本设置','Sys/index','39','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('41','栏目管理','Classtype','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('42','栏目列表','Classtype/index','41','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('43','新增栏目','Classtype/addclass','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('44','修改栏目','Classtype/editclass','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('45','删除栏目','Classtype/deleteclass','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('46','修改排序','Classtype/editClassOrders','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('47','栏目隐藏','Classtype/change_status','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('48','管理员管理','Admin','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('49','角色管理','Admin/group','48','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('50','新增角色','Admin/groupadd','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('51','修改角色','Admin/groupedit','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('52','删除角色','Admin/group_del','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('53','角色状态','Admin/change_group_status','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('54','管理员列表','Admin/adminlist','48','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('55','新增管理员','Admin/adminadd','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('56','修改管理员','Admin/adminedit','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('57','管理员状态','Admin/change_status','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('58','删除管理员','Admin/admindelete','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('59','个人信息','Index/details','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('60','模块管理','Molds','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('61','模块列表','Molds/index','60','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('62','新增模块','Molds/addMolds','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('63','修改模块','Molds/editMolds','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('64','删除模块','Molds/deleteMolds','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('65','权限管理','Rulers','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('66','权限列表','Rulers/index','65','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('67','新增权限','Rulers/addrulers','65','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('68','修改权限','Rulers/editrulers','65','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('69','删除权限','Rulers/deleterulers','65','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('70','桌面设置','Index/desktop','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('71','新增桌面','Index/desktop_add','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('72','修改桌面','Index/desktop_edit','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('73','删除桌面','Index/desktop_del','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('74','图标库','Index/unicode','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('75','插件管理','Plugins','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('76','插件列表','Plugins/index','75','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('77','模块扩展','Extmolds','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('82','轮播图','Collect','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('83','轮播图','Collect/index','82','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('84','新增轮播图','Collect/addcollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('85','修改轮播图','Collect/editcollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('86','删除轮播图','Collect/deletecollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('87','复制轮播图','Collect/copycollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('88','批量删除轮播图','Collect/deleteAll','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('89','轮播图分类','Collect/collectType','82','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('90','新增轮播图分类','Collect/collectTypeAdd','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('91','修改轮播图分类','Collect/collectTypeEdit','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('92','删除轮播图分类','Collect/collectTypeDelete','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('93','批量复制','Article/copyAll','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('94','批量修改栏目','Article/changeType','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('95','友情链接','Links/index','189','1','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('96','新增友链','Links/addlinks','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('97','修改友链','Links/editlinks','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('98','复制友链','Links/copylinks','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('99','删除友链','Links/deletelinks','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('100','批量删除友链','Links/deleteAll','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('101','通用模块','Common','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('102','上传文件','Common/uploads','101','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('103','更新cookie','Index/update_session_maxlifetime','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('104','商品管理','Product','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('105','商品列表','Product/productlist','104','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('106','新增商品','Product/addproduct','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('107','修改商品','Product/editproduct','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('108','删除商品','Product/deleteproduct','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('109','复制商品','Product/copyproduct','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('110','批量删除','Product/deleteAll','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('111','批量复制','Product/copyAll','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('112','修改栏目','Product/changeType','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('113','修改排序','Product/editProductOrders','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('114','清理缓存','Index/cleanCache','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('115','登录日志','Sys/loginlog','39','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('116','图库管理','Sys/pictures','39','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('117','修改排序','Extmolds/editOrders','77','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('118','会员分组','Member/membergroup','1','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('119','新增分组','Member/groupadd','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('120','修改分组','Member/groupedit','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('121','更改分组状态','Member/change_group_status','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('122','删除分组','Member/group_del','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('123','会员权限','Member/power','1','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('124','添加权限','Member/addrulers','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('125','修改权限','Member/editrulers','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('126','删除权限','Member/deleterulers','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('127','修改分组排序','Member/editOrders','1','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('128','订单管理','Order','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('129','订单列表','Order/index','128','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('130','订单详情','Order/details','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('131','批量删除','Order/deleteAll','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('132','上传支付证书','Sys/uploadcert','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('133','更改状态','Plugins/change_status','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('134','安装卸载','Plugins/action_do','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('135','复制转移文件','Plugins/file2dir','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('136','移动文件夹','Plugins/recurse_copy','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('137','删除图库图片','Sys/deletePic','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('138','批量删除图库','Sys/deletePicAll','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('139','安装说明','Plugins/desc','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('140','微信公众号','Wechat','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('141','公众号菜单','Wechat/wxcaidan','140','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('142','公众号素材','Wechat/sucai','140','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('143','模板制作','Index/showlabel','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('144','获取首字母拼音','Classtype/get_pinyin','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('145','批量新增栏目','Classtype/addmany','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('146','自定义配置删除','Sys/custom_del','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('147','TAG列表','Extmolds/index/molds/tags','77','1','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('148','新增TAG','Extmolds/addmolds/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('149','修改TAG','Extmolds/editmolds/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('150','复制TAG','Extmolds/copymolds/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('151','删除TAG','Extmolds/deletemolds/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('152','批量删除TAG','Extmolds/deleteAll/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('153','网站地图','Index/sitemap','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('154','生成静态文件','Index/tohtml','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('155','更新栏目HTML','Index/html_classtype','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('156','更新模块HTML','Index/html_molds','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('157','批量修改推荐属性','Article/changeAttribute','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('158','批量修改推荐属性','Product/changeAttribute','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('159','批量修改友链栏目','Links/changeType','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('160','批量修改TAG栏目','Extmolds/changeType/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('161','批量复制友链','Links/copyAll','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('162','批量复制TAG','Extmolds/copyAll/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('163','批量修改友链排序','Links/editOrders','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('164','批量修改TAG排序','Extmolds/editOrders/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('165','删除订单','Order/deleteorder','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('166','批量删除','Admin/deleteAll','48','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('167','高级设置','Sys/high-level','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('168','邮箱订单','Sys/email-order','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('169','支付配置','Sys/payconfig','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('170','公众号配置','Sys/wechatbind','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('171','批量审核','Article/checkAll','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('172','批量审核','Product/checkAll','104','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('173','批量审核','Message/checkAll','21','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('174','批量审核','Comment/checkAll','15','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('175','批量审核友链','Links/checkAll','189','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('176','批量审核TAG','Extmolds/checkAll/molds/tags','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('177','充值列表','Order/czlist','128','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('178','手动充值','Order/chongzhi','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('179','删除记录','Order/delbuylog','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('180','批量删除记录','Order/delAllbuylog','128','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('181','积分配置','Sys/jifenset','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('182','插件更新','Plugins/update','75','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('183','获取栏目模板','Classtype/get_html','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('184','批量修改栏目','Classtype/changeClass','41','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('185','友链分类','Links/linktype','189','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('186','新增友链分类','Links/linktypeadd','189','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('187','修改友链分类','Links/linktypeedit','189','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('188','删除友链分类','Links/linktypedelete','189','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('189','友情链接','Links','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('190','导航设置','Index/menu','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('191','新增导航','Index/addmenu','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('192','修改导航','Index/editmenu','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('193','删除导航','Index/delmenu','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('194','碎片化','Sys/datacache','39','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('195','新增碎片','Sys/addcache','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('196','修改碎片','Sys/editcache','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('197','删除碎片','Sys/delcache','39','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('198','预览SQL','Sys/viewcache','39','0','1');
-- ----------------------------
-- Records of jz_sysconfig
-- ----------------------------
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('1','web_version','系统版号', NULL,'0','1.9.4');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('2','web_name','网站SEO名称', NULL,'0','极致CMS建站系统');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('3','web_keyword','网站SEO关键词', NULL,'0','极致CMS');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('4','web_desc','网站SEO描述', NULL,'0','极致CMS');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('5','web_js','统计代码', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('6','web_copyright','底部版权', NULL,'0','@2019-2099');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('7','web_beian','备案号', NULL,'0','冀ICP备18036869号');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('8','web_tel','公司电话', NULL,'0','0316-2222616');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('9','web_tel_400','400电话', NULL,'0','400-0000-000');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('10','web_qq','公司QQ', NULL,'0','12345678');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('11','web_email','公司邮箱', NULL,'0','123456@qq.com');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('12','web_address','公司地址', NULL,'0','河北省廊坊市广阳区凯创大厦第1幢2单元1606号');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('13','pc_template','PC端模板', NULL,'0','default');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('14','wap_template','WAP端模板', NULL,'0','wap');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('15','weixin_template','微信端模板', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('16','iswap','是否开启WAP端', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('17','isopenhomeupload','是否开启前台上传', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('18','isopenhomepower','是否开启前台权限', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('19','cache_time','缓存时间', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('20','fileSize','限制上传文件大小', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('21','fileType','允许上传文件类型', NULL,'0','pdf|jpg|jpeg|png|zip|rar|gzip|doc|docx|xlsx');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('22','ueditor_config','UEditor编辑器导航条配置', NULL,'0','&quot;fullscreen&quot;, 
&quot;source&quot;,&quot;undo&quot;, &quot;redo&quot;,&quot;bold&quot;, &quot;italic&quot;, &quot;underline&quot;, &quot;fontborder&quot;, &quot;strikethrough&quot;, &quot;superscript&quot;, &quot;subscript&quot;, &quot;removeformat&quot;, &quot;formatmatch&quot;, &quot;autotypeset&quot;, &quot;blockquote&quot;, &quot;pasteplain&quot;,&quot;forecolor&quot;, &quot;backcolor&quot;, &quot;insertorderedlist&quot;, &quot;insertunorderedlist&quot;, &quot;selectall&quot;, &quot;cleardoc&quot;,&quot;rowspacingtop&quot;, &quot;rowspacingbottom&quot;, &quot;lineheight&quot;,&quot;customstyle&quot;, &quot;paragraph&quot;, &quot;fontfamily&quot;, &quot;fontsize&quot;,&quot;directionalityltr&quot;, &quot;directionalityrtl&quot;, &quot;indent&quot;,&quot;justifyleft&quot;, &quot;justifycenter&quot;, &quot;justifyright&quot;, &quot;justifyjustify&quot;,&quot;touppercase&quot;, &quot;tolowercase&quot;,&quot;link&quot;, &quot;unlink&quot;, &quot;anchor&quot;, &quot;imagenone&quot;, &quot;imageleft&quot;, &quot;imageright&quot;, &quot;imagecenter&quot;,&quot;simpleupload&quot;, &quot;insertimage&quot;, &quot;emotion&quot;, &quot;scrawl&quot;, &quot;insertvideo&quot;, &quot;music&quot;, &quot;attachment&quot;, &quot;map&quot;, &quot;gmap&quot;, &quot;insertframe&quot;, &quot;insertcode&quot;, &quot;webapp&quot;, &quot;pagebreak&quot;,&quot;template&quot;, &quot;background&quot;,&quot;horizontal&quot;, &quot;date&quot;, &quot;time&quot;, &quot;spechars&quot;, &quot;snapscreen&quot;, &quot;wordimage&quot;,&quot;inserttable&quot;, &quot;deletetable&quot;, &quot;insertparagraphbeforetable&quot;, &quot;insertrow&quot;, &quot;deleterow&quot;, &quot;insertcol&quot;, &quot;deletecol&quot;, &quot;mergecells&quot;, &quot;mergeright&quot;, &quot;mergedown&quot;, &quot;splittocells&quot;, &quot;splittorows&quot;, &quot;splittocols&quot;, &quot;charts&quot;,&quot;print&quot;, &quot;preview&quot;, &quot;searchreplace&quot;, &quot;help&quot;, &quot;drafts&quot;');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('23','search_table','允许前台搜索的表', NULL,'0','article|product');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('24','imagequlity','上传图片压缩比例', NULL,'0','75');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('25','ispngcompress','PNG是否压缩', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('26','email_server','邮件服务器', NULL,'0','smtp.163.com');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('27','email_port','收发端口', NULL,'0','465');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('28','shou_email','收件人Email地址', NULL,'0',NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('29','send_email','发件人Email地址', NULL,'0','ttuuffuu@163.com');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('30','send_pass','发件人Email秘钥', NULL,'0','tu123456');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('31','send_name','发件人昵称', NULL,'0','极致建站系统');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('32','tj_msg','客户订单通知', NULL,'0','尊敬的{xxx}，我们已经收到您的订单！请留意您的电子邮件以获得最新消息，谢谢您！');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('33','send_msg','订单出货通知', NULL,'0','尊敬的{xxx}，我们已确认了您的订单，请于3日内汇款，逾期恕不保留，不便请见谅。汇款完成后，烦请告知客服人员您的交易账号后五位，即完成下单手续，谢谢您。');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('34','yunfei','订单运费', NULL,'0','0.00');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('35','paytype','支付方式','0关闭支付，1极致支付，2自主平台支付','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('36','jizhi_pay_url','极致平台接口', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('37','jizhi_mchid','极致平台商户', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('38','jizhi_appid','极致平台应用appid', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('39','jizhi_key','极致平台应用秘钥', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('40','alipay_partner','自主平台支付宝合作者', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('41','alipay_key','自主平台支付宝key', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('42','alipay_private_key','自主平台支付宝私钥', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('43','alipay_public_key','自主平台支付宝公钥', NULL,'0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('44','wx_mchid','自主平台微信商户mchid','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('45','wx_key','自主平台微信商户key','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('46','wx_appid','自主平台微信公众号appid','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('47','wx_appsecret','自主平台公众号appsecret','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('48','wx_client_cert','自主平台微信apiclient_cert','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('49','wx_client_key','自主平台微信apiclient_key','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('50','wx_login_appid','公众号appid','用户登录相关，如果跟支付的一样，那就再填写一遍','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('51','wx_login_appsecret','公众号appsecret','用户登录相关，如果跟支付的一样，那就再填写一遍','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('52','wx_login_token','公众号token','用户登录相关，如果跟支付的一样，那就再填写一遍','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('53','huanying','公众号关注欢迎语','公众号关注时发送的第一句推送','0','欢迎关注公众号~');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('54','wx_token','自主平台微信token','支付相关','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('55','web_logo','网站LOGO', NULL,'0', '/static/default/assets/img/logo.png');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('56','admintpl','后台模板风格','默认tpl,原始风格default','0','default');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('58','isopenwebsite','是否绑定多域名','开启绑定多域名后，需要到插件中配置','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('59','domain','网站网址','全局网址,最后不带/（斜杠）','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('60','isrelative','基本信息下扩展','新增字段是否显示在【基本信息】底部，默认在【扩展信息】下','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('61','overtime','订单超时','按小时计算，超过该小时订单过期，仅限于开启支付后，0代表不限制','0','4');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('62','islevelurl','开启层级URL','默认关闭层级URL，开启后URL会按照父类层级展现','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('63','iscachepage','缓存完整页面','前台完整页面缓存，结合缓存时间，可以提高访问速度','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('64','isautohtml','自动生成静态HTML','前台访问网站页面，将自动生成静态HTML，下次访问直接进入静态HTML页面','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('65','pc_html','PC静态文件目录','电脑端静态HTML存放目录，默认根目录[ / ]','0','/');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('66','mobile_html','WAP静态文件目录','手机端静态HTML存放目录，默认[ m ]，PC和WAP静态目录不能相同，否则文件会混乱','0','m');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('67','autocheckmessage','是否留言自动审核','开启后，留言自动审核（显示）','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('68','autocheckcomment','是否评论自动审核','开启后评论自动审核（显示）','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('69','mingan','网站敏感词过滤','将敏感词放到里面，用“,”分隔，用{xxx}代替通配内容','0','最,最佳,最具,最爱,最赚,最优,最优秀,最大,最大程度,最高,最高级,最高端,最奢侈,最低,最低级,最低价,最底,最便宜,史上最低价,最流行,最受欢迎,最时尚,最聚拢,最符合,最舒适,最先,最先进,最先进科学,最后,最新,最新技术,最新科学,第一,中国第一,全网第一,销量第一,排名第一,唯一,第一品牌,NO.1,TOP1,独一无二,全国第一,遗留,一天,仅此一次,仅此一款,最后一波,全国{xxx}大品牌之一,销冠,国家级,国际级,世界级,千万级,百万级,星级,5A,甲级,超甲级,顶级,顶尖,尖端,顶级享受,高级,极品,极佳,绝佳,绝对,终极,极致,致极,极具,完美,绝佳,极佳,至,至尊,至臻,臻品,臻致,臻席,压轴,问鼎,空前,绝后,绝版,无双,非此莫属,巅峰,前所未有,无人能及,顶级,鼎级,鼎冠,定鼎,完美,翘楚之作,不可再生,不可复制,绝无仅有,寸土寸金,淋漓尽致,无与伦比,唯一,卓越,卓著,稀缺,前无古人后无来者,绝版,珍稀,臻稀,稀少,绝无仅有,绝不在有,稀世珍宝,千金难求,世所罕见,不可多得,空前绝后,寥寥无几,屈指可数,独家,独创,独据,开发者,缔造者,创始者,发明者,首个,首选,独家,首发,首席,首府,首选,首屈一指,全国首家,国家领导人,国门,国宅,首次,填补国内空白,国际品质,黄金旺铺,黄金价值,黄金地段,金钱,金融汇币图片,外国货币,金牌,名牌,王牌,领先上市,巨星,著名,掌门人,至尊,冠军,王之王,王者楼王,墅王,皇家,世界领先,遥遥领先,领导者,领袖,引领,创领,领航,耀领,史无前例,前无古人,永久,万能,百分之百,特供,专供,专家推荐,国家{xxx}领导人推荐');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('70','iswatermark','是否开启水印','开启水印需要上传水印图片','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('71','watermark_file','水印图片','水印图片在250px以内','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('72','watermark_t','水印位置','参考键盘九宫格1-9','0','9');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('73','watermark_tm','水印透明度','透明度越大，越难看清楚水印','0', NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('74','money_exchange','钱包兑换率','站内钱包与RMB的兑换率，即1元=多少金币','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('75','jifen_exchange','积分兑换率','站内积分与RMB的兑换率，即1元=多少积分','0','100');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('76','isopenjifen','积分支付','开启积分支付后，商品可以用积分支付','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('77','isopenqianbao','钱包支付','开启钱包支付后，商品可以用钱包支付','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('78','isopenweixin','微信支付','开启微信支付后，商品可以用微信支付','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('79','isopenzfb','支付宝支付','开启支付宝支付后，商品可以用支付宝支付','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('80','login_award','每次登录奖励','每天登录奖励积分数，最小为0，每天登录只奖励一次','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('81','login_award_open','登录奖励','开启登录奖励后，登录后就会获得积分奖励','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('82','release_award_open','发布奖励','开启后，发布内容会奖励积分','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('83','release_award','每次发布奖励','每次发布内容奖励积分数','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('84','release_max_award','每天发布最高奖励','每天奖励不超过积分上限，设置0则无上限','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('85','collect_award_open','收藏奖励','开启后，发布内容被收藏会奖励积分','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('86','collect_award','每次收藏奖励','每次发布内容被收藏奖励积分数','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('87','collect_max_award','每天收藏最高奖励','每天奖励不超过积分上限，设置0则无上限','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('88','likes_award_open','点赞奖励','开启后，发布内容被点赞会奖励积分','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('89','likes_award','每次点赞奖励','每次发布内容被点赞奖励积分数','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('90','likes_max_award','每天点赞最高奖励','每天奖励不超过积分上限，设置0则无上限','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('91','comment_award_open','评论奖励','开启后，发布内容被评论会奖励积分','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('92','comment_award','每次评论奖励','每次发布内容被评论奖励积分数','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('93','comment_max_award','每天评论最高奖励','每天奖励不超过积分上限，设置0则无上限','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('94','follow_award_open','关注奖励','开启后，用户被粉丝关注会奖励积分','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('95','follow_award','每次关注奖励','每次被关注奖励积分数','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('96','follow_max_award','每天关注最高奖励','每天关注奖励不超过积分上限，设置0则无上限','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('97','isopenemail','发送邮件','是否开启邮件发送','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('98','closeweb','关闭网站', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('99','closetip','关站提示', NULL,'0','抱歉！该站点已经被管理员停止运行，请联系管理员了解详情！');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('100','admin_save_path','后台文件存储路径', '默认static/upload/{yyyy}/{mm}/{dd}，存储路径相对于根目录，最后不能带斜杠[ / ]','0','static/upload/{yyyy}/{mm}/{dd}');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('101','home_save_path','前台文件存储路径', '默认static/upload/{yyyy}/{mm}/{dd}，存储路径相对于根目录，最后不能带斜杠[ / ]','0','static/upload/{yyyy}/{mm}/{dd}');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('102','isajax','是否开启前台AJAX', '开启后AJAX，前台可以通过栏目链接+ajax=1获取JSON数据','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('103','isautositemap','自动生成sitemap', '开启后，前台访问每天会自动生成1次sitemap','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('104','invite_award_open','是否开启邀请奖励', '开启邀请后则会奖励','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('105','invite_type','邀请奖励类型', NULL,'0','jifen');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('106','invite_award','邀请奖励数量', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('107','web_phone','联系手机', NULL,'0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('108','web_weixin','站长微信', NULL,'1',NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('109','ispicsdes','开启多图描述', '开启后图集每张图可以添加描述，注意模板输出需要更改输出方式！(附件同理)','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('110','isregister','前台用户注册', '关闭前台注册后，前台无法进入注册页面','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('111','onlyinvite','仅邀请码注册', '开启后，必须通过邀请链接才能注册！','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('112','release_table','允许前台发布模块', '防止数据泄露,填写允许发布模块标识,留空表示不允许发布,多个表可用|分割','0','article|product');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('113','search_words','前台搜索的字段', '可以设置搜索表中的相关字段进行模糊查询,多个字段可用|分割','0','title');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('114','closehomevercode','前台验证码', '关闭后，登录注册不需要验证码','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('115','closeadminvercode','后台验证码', '关闭后，后台管理员登录不需要验证码','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('116','tag_table','TAG包含模型', '在tag列表上查询的相关模型,多个模型标识可用|分割,如：article|product','0','article|product');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('117','paydata','支付配置', NULL,'0',NULL);
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('118','isopendmf','支付宝当面付', NULL,'0','1');