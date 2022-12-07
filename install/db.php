<?php die();?>/*
MySQL Database Backup Tools
Server:127.0.0.1:3306
Database:db
Data:2022-01-26 13:46:31
*/
SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for jz_article
-- ----------------------------
DROP TABLE IF EXISTS `jz_article`;
CREATE TABLE `jz_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '文章标题',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '所属栏目',
  `molds` varchar(50) DEFAULT 'article' COMMENT '模型标识',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目链接',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text COMMENT '简介',
  `seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID：0前台发布',
  `litpic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `body` mediumtext COMMENT '文章内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核：1审核0未审2退回',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `istop` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否置顶：1是0否',
  `ishot` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否头条：1是0否',
  `istuijian` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否推荐：1是0否',
  `tags` varchar(255) DEFAULT NULL COMMENT 'TAG标签',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '发布会员：0后台发布',
  `target` varchar(255) DEFAULT NULL COMMENT '外链',
  `ownurl` varchar(255) DEFAULT NULL COMMENT '自定义链接',
  `jzattr` varchar(50) DEFAULT NULL COMMENT '推荐属性：1置顶2热点3推荐',
  `tids` varchar(255) DEFAULT NULL COMMENT '副栏目',
  `zan` int(11) DEFAULT '0' COMMENT '点赞数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表';
-- ----------------------------
-- Table structure for jz_attr
-- ----------------------------
DROP TABLE IF EXISTS `jz_attr`;
CREATE TABLE `jz_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '属性名',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推荐属性';
-- ----------------------------
-- Table structure for jz_buylog
-- ----------------------------
DROP TABLE IF EXISTS `jz_buylog`;
CREATE TABLE `jz_buylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0' COMMENT '内容ID',
  `userid` int(11) DEFAULT '0' COMMENT '会员ID',
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单号',
  `type` tinyint(1) DEFAULT '1' COMMENT '交易类型：1购买商品0兑换金币',
  `buytype` varchar(20) DEFAULT NULL COMMENT '支付类型',
  `msg` varchar(255) DEFAULT NULL COMMENT '记录',
  `molds` varchar(255) DEFAULT NULL COMMENT '模型标识',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总计',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `addtime` int(11) DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';
-- ----------------------------
-- Table structure for jz_cachedata
-- ----------------------------
DROP TABLE IF EXISTS `jz_cachedata`;
CREATE TABLE `jz_cachedata` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `field` varchar(50) DEFAULT NULL COMMENT '字段',
  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `isall` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否输出所有：1是0否',
  `sqls` varchar(500) DEFAULT NULL COMMENT 'SQL',
  `orders` varchar(255) DEFAULT NULL COMMENT '排序',
  `limits` int(11) NOT NULL DEFAULT '10' COMMENT '输出条数',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '更新周期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据缓存表';
-- ----------------------------
-- Table structure for jz_chain
-- ----------------------------
DROP TABLE IF EXISTS `jz_chain`;
CREATE TABLE `jz_chain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '内链词',
  `newtitle` varchar(100) DEFAULT NULL COMMENT '替换词',
  `url` varchar(255) DEFAULT NULL COMMENT '链接',
  `num` int(11) NOT NULL DEFAULT '-1' COMMENT '替换次数',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内链';
-- ----------------------------
-- Table structure for jz_classtype
-- ----------------------------
DROP TABLE IF EXISTS `jz_classtype`;
CREATE TABLE `jz_classtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(50) DEFAULT NULL COMMENT '栏目名',
  `seo_classname` varchar(50) DEFAULT NULL COMMENT 'SEO栏目名',
  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `litpic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `description` text COMMENT '描述',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `body` text COMMENT '内容',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `orderstype` int(4) NOT NULL DEFAULT '0' COMMENT '排序类型：1时间倒序2ID正序3点击量倒序4ID正序5时间正序6点击量正序',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `iscover` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否覆盖下级',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级栏目ID',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目权限：0不限制',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目链接',
  `lists_html` varchar(50) DEFAULT NULL COMMENT '栏目页模板',
  `details_html` varchar(50) DEFAULT NULL COMMENT '详情页模板',
  `lists_num` int(4) DEFAULT '0' COMMENT '列表数量',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `gourl` varchar(255) DEFAULT NULL COMMENT '栏目外链',
  `ishome` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许会员发布',
  `isclose` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关闭栏目',
  `gids` varchar(255) DEFAULT NULL COMMENT '允许访问角色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';
-- ----------------------------
-- Table structure for jz_collect
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect`;
CREATE TABLE `jz_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `description` varchar(500) DEFAULT NULL COMMENT '简介',
  `tid` int(11) DEFAULT NULL COMMENT '所属栏目',
  `litpic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `w` varchar(10) NOT NULL DEFAULT '0' COMMENT '宽',
  `h` varchar(10) NOT NULL DEFAULT '0' COMMENT '高',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示0隐藏',
  `url` varchar(255) DEFAULT NULL COMMENT '链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播图';
-- ----------------------------
-- Table structure for jz_collect_type
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect_type`;
CREATE TABLE `jz_collect_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '分类名',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播图分类';
-- ----------------------------
-- Table structure for jz_comment
-- ----------------------------
DROP TABLE IF EXISTS `jz_comment`;
CREATE TABLE `jz_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(4) NOT NULL DEFAULT '0' COMMENT '栏目tid',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '回复帖子id',
  `zid` int(11) NOT NULL DEFAULT '0' COMMENT '主回复帖子：同一层楼内回复，规定主回复id',
  `body` text COMMENT '评论内容',
  `reply` text COMMENT '回复内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '发布会员：0表示游客',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示0隐藏2被删除',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读：1已读0未读',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `aid` (`aid`),
  KEY `pid` (`pid`),
  KEY `zid` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';
-- ----------------------------
-- Table structure for jz_ctype
-- ----------------------------
DROP TABLE IF EXISTS `jz_ctype`;
CREATE TABLE `jz_ctype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '配置栏名称',
  `action` varchar(255) DEFAULT NULL COMMENT '配置标识，用于权限控制',
  `sys` tinyint(1) DEFAULT 0 COMMENT '系统配置，1是0否',
  `isopen` tinyint(1) DEFAULT 1 COMMENT '是否启用，1启用0关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置栏目名';
-- ----------------------------
-- Table structure for jz_customurl
-- ----------------------------
DROP TABLE IF EXISTS `jz_customurl`;
CREATE TABLE `jz_customurl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `url` varchar(255) DEFAULT NULL COMMENT '自定义URL',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '内容ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义链接表';
-- ----------------------------
-- Table structure for jz_fields
-- ----------------------------
DROP TABLE IF EXISTS `jz_fields`;
CREATE TABLE `jz_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL COMMENT '字段标识',
  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `fieldname` varchar(100) DEFAULT NULL COMMENT '字段名称',
  `tips` varchar(100) DEFAULT NULL COMMENT '填写提示',
  `fieldtype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '输入类型',
  `tids` text COMMENT '绑定栏目',
  `fieldlong` varchar(50) DEFAULT NULL COMMENT '字段长度',
  `body` text COMMENT '字段配置',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '表单排序',
  `ismust` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必填：1是0否',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '前台是否显示：1显示0隐藏',
  `isadmin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '后台是否显示：1显示0隐藏',
  `issearch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索显示：1显示0隐藏',
  `islist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '列表显示：1显示0隐藏',
  `format` varchar(50) DEFAULT NULL COMMENT '格式化',
  `vdata` varchar(50) DEFAULT NULL COMMENT '默认值',
  `isajax` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'AJAX显示：1显示0隐藏',
  `listorders` int(4) NOT NULL DEFAULT '0' COMMENT '列表排序',
  `isext` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否扩展信息',
  `width` varchar(50) DEFAULT NULL COMMENT '列表中显示宽度',
  `ishome` tinyint(1) NOT NULL DEFAULT '1' COMMENT '前台表单录入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_hook
-- ----------------------------
DROP TABLE IF EXISTS `jz_hook`;
CREATE TABLE `jz_hook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) DEFAULT NULL COMMENT '模块，Home/A',
  `namespace` varchar(100) DEFAULT NULL COMMENT '控制器命名空间',
  `controller` varchar(50) DEFAULT NULL COMMENT '控制器',
  `action` varchar(255) DEFAULT NULL COMMENT '执行函数：可同时注册多个方法，逗号拼接',
  `hook_namespace` varchar(100) DEFAULT NULL COMMENT '钩子控制器所在的命名空间',
  `hook_controller` varchar(50) DEFAULT NULL COMMENT '钩子控制器',
  `hook_action` varchar(50) DEFAULT NULL COMMENT '钩子执行方法',
  `all_action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全局控制器',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序：越大越靠前执行',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否关闭：1开启0关闭',
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
  `name` varchar(200) DEFAULT NULL COMMENT '桌面名称',
  `top_layout` text COMMENT '顶部菜单',
  `left_layout` text COMMENT '左侧菜单',
  `gid` int(11) DEFAULT NULL COMMENT '所属角色',
  `ext` varchar(255) DEFAULT NULL COMMENT '备注',
  `sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统配置：1是0否',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认配置：1是0否',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='桌面设置';
-- ----------------------------
-- Table structure for jz_level
-- ----------------------------
DROP TABLE IF EXISTS `jz_level`;
CREATE TABLE `jz_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '管理员名称',
  `pass` varchar(100) DEFAULT NULL COMMENT '密码',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话号码',
  `gid` int(4) NOT NULL DEFAULT '2' COMMENT '所属角色',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `regtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `logintime` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常0冻结',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';
-- ----------------------------
-- Table structure for jz_level_group
-- ----------------------------
DROP TABLE IF EXISTS `jz_level_group`;
CREATE TABLE `jz_level_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '角色名称',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '超管：1是0否',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布审核：1需要审核0不需要',
  `classcontrol` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否配置栏目权限：1是0否',
  `paction` text COMMENT '权限列表',
  `tids` text COMMENT '拥有栏目权限',
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常0冻结',
  `description` varchar(500) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';
-- ----------------------------
-- Table structure for jz_likes
-- ----------------------------
DROP TABLE IF EXISTS `jz_likes`;
CREATE TABLE `jz_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '内容ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`,`aid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点赞表';
-- ----------------------------
-- Table structure for jz_link_type
-- ----------------------------
DROP TABLE IF EXISTS `jz_link_type`;
CREATE TABLE `jz_link_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '友链分类名',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接分类表';
-- ----------------------------
-- Table structure for jz_links
-- ----------------------------
DROP TABLE IF EXISTS `jz_links`;
CREATE TABLE `jz_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '友链名称',
  `molds` varchar(50) DEFAULT 'links' COMMENT '模型标识',
  `url` varchar(255) DEFAULT NULL COMMENT '链接',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示：1显示0隐藏',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目链接',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `target` varchar(255) DEFAULT NULL COMMENT '外链',
  `ownurl` varchar(255) DEFAULT NULL COMMENT '自定义链接',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表';
-- ----------------------------
-- Table structure for jz_member
-- ----------------------------
DROP TABLE IF EXISTS `jz_member`;
CREATE TABLE `jz_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL COMMENT '用户昵称',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信OPENID',
  `pass` varchar(255) DEFAULT NULL COMMENT '密码',
  `token` varchar(255) DEFAULT NULL COMMENT 'Token',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别：1男2女0未知',
  `gid` int(11) NOT NULL DEFAULT '1' COMMENT '会员分组ID',
  `litpic` varchar(255) DEFAULT NULL COMMENT '头像',
  `tel` varchar(50) DEFAULT NULL COMMENT '手机号码',
  `jifen` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分数',
  `likes` text COMMENT '喜欢点赞（已废弃）',
  `collection` text COMMENT '收藏（已废弃）',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金币',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `province` varchar(50) DEFAULT NULL COMMENT '省份',
  `city` varchar(50) DEFAULT NULL COMMENT '城市',
  `regtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `logintime` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常0封禁',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';
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
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣价：现金折扣或者百分比折扣',
  `discount_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '折扣类型：0无折扣1现金折扣,1百分比折扣',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员分组';
-- ----------------------------
-- Table structure for jz_menu
-- ----------------------------
DROP TABLE IF EXISTS `jz_menu`;
CREATE TABLE `jz_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '导航名称',
  `nav` text COMMENT '导航配置',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示0不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';
-- ----------------------------
-- Table structure for jz_message
-- ----------------------------
DROP TABLE IF EXISTS `jz_message`;
CREATE TABLE `jz_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '发布会员',
  `tid` int(4) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `user` varchar(255) DEFAULT NULL COMMENT '用户名',
  `ip` varchar(255) DEFAULT NULL COMMENT 'IP',
  `body` text COMMENT '留言内容',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核：1审核0未审',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶：1是0否',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `tids` varchar(255) DEFAULT NULL COMMENT '副栏目',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';
-- ----------------------------
-- Table structure for jz_molds
-- ----------------------------
DROP TABLE IF EXISTS `jz_molds`;
CREATE TABLE `jz_molds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '模型名称',
  `biaoshi` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统：1是0否',
  `isopen` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启：1开启0关闭',
  `iscontrol` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启权限：1开启权限0不开启',
  `ismust` tinyint(1) NOT NULL DEFAULT '0' COMMENT '栏目必选：1是0否',
  `isclasstype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示栏目',
  `isshowclass` tinyint(1) DEFAULT '1' COMMENT '栏目绑定：1显示0隐藏',
  `list_html` varchar(50) DEFAULT 'list.html' COMMENT '默认列表模板',
  `details_html` varchar(50) DEFAULT 'details.html' COMMENT '默认详情模板',
  `orders` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `ispreview` tinyint(1) DEFAULT '1' COMMENT '是否可以预览',
  `ishome` tinyint(1) DEFAULT '0' COMMENT '前台发布',
  PRIMARY KEY (`id`),
  KEY `biaoshi` (`biaoshi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型表';
-- ----------------------------
-- Table structure for jz_orders
-- ----------------------------
DROP TABLE IF EXISTS `jz_orders`;
CREATE TABLE `jz_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单号',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '下单会员',
  `paytype` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `ptype` tinyint(1) DEFAULT '1' COMMENT '交易类型：1商品购买2充值金额3充值积分',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `price` varchar(200) DEFAULT NULL COMMENT '价格',
  `jifen` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分',
  `qianbao` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '钱包',
  `body` text COMMENT '购买内容',
  `receive_username` varchar(50) DEFAULT NULL COMMENT '收件人',
  `receive_tel` varchar(20) DEFAULT NULL COMMENT '收件电话',
  `receive_email` varchar(50) DEFAULT NULL COMMENT '收件邮箱',
  `receive_address` varchar(255) DEFAULT NULL COMMENT '收件地址',
  `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付：1支付0未支付',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态：1提交订单,2已支付,3超时,4已提交订单,5已发货,6已废弃失效,0删除订单',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
  `yunfei` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';
-- ----------------------------
-- Table structure for jz_page
-- ----------------------------
DROP TABLE IF EXISTS `jz_page`;
CREATE TABLE `jz_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '链接',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示：1显示0隐藏',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶：1是0否',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `tids` varchar(255) NOT NULL COMMENT '副栏目',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页模型';
-- ----------------------------
-- Table structure for jz_pictures
-- ----------------------------
DROP TABLE IF EXISTS `jz_pictures`;
CREATE TABLE `jz_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '内容ID',
  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
  `path` varchar(20) DEFAULT 'Admin' COMMENT '板块：Admin后台Home前台',
  `filetype` varchar(20) DEFAULT NULL COMMENT '类型',
  `size` varchar(50) DEFAULT NULL COMMENT '大小',
  `litpic` varchar(255) DEFAULT NULL COMMENT '链接',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID/发布会员ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片集';
-- ----------------------------
-- Table structure for jz_pingjia
-- ----------------------------
DROP TABLE IF EXISTS `jz_pingjia`;
CREATE TABLE `jz_pingjia` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0' COMMENT '所属栏目',
  `tids` varchar(255) DEFAULT NULL COMMENT '副栏目',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `litpic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` varchar(500) DEFAULT NULL COMMENT '简介',
  `body` text COMMENT '内容',
  `molds` varchar(50) DEFAULT 'pingjia' COMMENT '模型标识',
  `userid` int(11) DEFAULT '0' COMMENT '发布管理员',
  `orders` int(11) DEFAULT '0' COMMENT '排序',
  `member_id` int(11) DEFAULT '0' COMMENT '前台用户',
  `comment_num` int(11) DEFAULT '0' COMMENT '评论数',
  `htmlurl` varchar(100) DEFAULT NULL COMMENT '栏目链接',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `target` varchar(255) DEFAULT NULL COMMENT '外链',
  `ownurl` varchar(255) DEFAULT NULL COMMENT '自定义URL',
  `jzattr` varchar(50) DEFAULT NULL COMMENT '推荐属性',
  `hits` int(11) DEFAULT '0' COMMENT '点击量',
  `zan` int(11) DEFAULT '0' COMMENT '点赞数',
  `tags` varchar(255) DEFAULT NULL COMMENT 'TAG',
  `addtime` int(11) DEFAULT '0' COMMENT '发布时间',
  `zhiye` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for jz_plugins
-- ----------------------------
DROP TABLE IF EXISTS `jz_plugins`;
CREATE TABLE `jz_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '插件名称',
  `filepath` varchar(50) DEFAULT NULL COMMENT '插件文件名',
  `description` varchar(255) DEFAULT NULL COMMENT '简介',
  `version` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '版本',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `module` varchar(20) NOT NULL DEFAULT 'Home' COMMENT '模块',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启：1开启0关闭',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `config` text COMMENT '配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';
-- ----------------------------
-- Table structure for jz_power
-- ----------------------------
DROP TABLE IF EXISTS `jz_power`;
CREATE TABLE `jz_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) DEFAULT NULL COMMENT '函数名',
  `name` varchar(50) DEFAULT NULL COMMENT '权限名',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父类权限ID',
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开放',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户权限表';
-- ----------------------------
-- Table structure for jz_product
-- ----------------------------
DROP TABLE IF EXISTS `jz_product`;
CREATE TABLE `jz_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `molds` varchar(50) DEFAULT 'product' COMMENT '模型标识',
  `title` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '所属栏目',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目链接',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT '简介',
  `litpic` varchar(255) DEFAULT NULL COMMENT '首图',
  `stock_num` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `pictures` text COMMENT '图集',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示0不显示',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `body` mediumtext COMMENT '详情',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '录入管理员ID',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `istop` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否置顶：1是0否',
  `ishot` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否头条：1是0否',
  `istuijian` varchar(2) NOT NULL DEFAULT '0' COMMENT '是否推荐：1是0否',
  `tags` varchar(255) DEFAULT NULL COMMENT 'TAG标签',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '发布会员',
  `target` varchar(255) DEFAULT NULL COMMENT '外链',
  `ownurl` varchar(255) DEFAULT NULL COMMENT '自定义链接',
  `jzattr` varchar(50) DEFAULT NULL COMMENT '推荐属性：1置顶2热点3推荐',
  `tids` varchar(255) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  `lx` varchar(2) DEFAULT NULL,
  `color` varchar(2) DEFAULT NULL,
  `hy` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';
-- ----------------------------
-- Table structure for jz_recycle
-- ----------------------------
DROP TABLE IF EXISTS `jz_recycle`;
CREATE TABLE `jz_recycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标记',
  `molds` varchar(50) DEFAULT NULL COMMENT '回收模型标志',
  `data` mediumtext COMMENT '回收数据',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '关联删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='回收站';
-- ----------------------------
-- Table structure for jz_ruler
-- ----------------------------
DROP TABLE IF EXISTS `jz_ruler`;
CREATE TABLE `jz_ruler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名称',
  `fc` varchar(50) DEFAULT NULL COMMENT '函数',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父类权限',
  `isdesktop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否桌面配置显示（已废弃）',
  `sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '系统：1是0否',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权限表';
-- ----------------------------
-- Table structure for jz_shouchang
-- ----------------------------
DROP TABLE IF EXISTS `jz_shouchang`;
CREATE TABLE `jz_shouchang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '内容ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表';
-- ----------------------------
-- Table structure for jz_sysconfig
-- ----------------------------
DROP TABLE IF EXISTS `jz_sysconfig`;
CREATE TABLE `jz_sysconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL COMMENT '配置字段',
  `title` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `tip` varchar(255) DEFAULT NULL COMMENT '字段填写提示',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参数类型：1图片2单行文本3多行文本4编辑器5文件上传6下拉开启关闭选项7下拉是否选项8栏目选项9代码',
  `data` text COMMENT '配置内容',
  `typeid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配置栏ID',
  `config` text COMMENT '单选多选配置信息',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `sys` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否系统字段',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置';
-- ----------------------------
-- Table structure for jz_tags
-- ----------------------------
DROP TABLE IF EXISTS `jz_tags`;
CREATE TABLE `jz_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0' COMMENT '栏目ID',
  `tids` varchar(500) DEFAULT NULL COMMENT '相关栏目',
  `orders` int(11) DEFAULT '0' COMMENT '排序',
  `comment_num` int(11) DEFAULT '0' COMMENT '评论数',
  `molds` varchar(50) DEFAULT 'tags' COMMENT '模型标识',
  `htmlurl` varchar(100) DEFAULT NULL COMMENT '栏目链接',
  `keywords` varchar(50) DEFAULT NULL COMMENT '关键词',
  `newname` varchar(50) DEFAULT NULL COMMENT '替换词（已废弃）',
  `num` int(4) DEFAULT '-1' COMMENT '替换次数：-1不限制',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示：1显示隐藏',
  `target` varchar(50) DEFAULT '_blank' COMMENT '外链',
  `number` int(11) DEFAULT '0' COMMENT '数量',
  `member_id` int(11) DEFAULT '0' COMMENT '发布会员',
  `ownurl` varchar(255) DEFAULT NULL COMMENT '自定义链接',
  `tags` varchar(255) DEFAULT NULL COMMENT 'TAG标签',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAGS表';
-- ----------------------------
-- Table structure for jz_task
-- ----------------------------
DROP TABLE IF EXISTS `jz_task`;
CREATE TABLE `jz_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0' COMMENT '栏目ID',
  `aid` int(11) DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) DEFAULT '0' COMMENT '发布会员',
  `puserid` int(11) DEFAULT '0' COMMENT '对象会员',
  `molds` varchar(50) DEFAULT NULL COMMENT '模块标识',
  `type` varchar(50) DEFAULT NULL COMMENT '消息类型',
  `body` varchar(255) DEFAULT NULL COMMENT '内容',
  `url` varchar(255) DEFAULT NULL COMMENT '链接',
  `isread` tinyint(1) DEFAULT '0' COMMENT '是否已读：1已读0未读',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示：1显示0隐藏',
  `readtime` int(11) DEFAULT '0' COMMENT '阅读时间',
  `addtime` int(11) DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员消息表';
-- ----------------------------
-- Records of jz_article
-- ----------------------------
-- ----------------------------
-- Records of jz_attr
-- ----------------------------
INSERT INTO `jz_attr` (`id`,`name`,`isshow`) VALUES ('1','置顶','1');
INSERT INTO `jz_attr` (`id`,`name`,`isshow`) VALUES ('2','热点','1');
INSERT INTO `jz_attr` (`id`,`name`,`isshow`) VALUES ('3','推荐','1');
-- ----------------------------
-- Records of jz_buylog
-- ----------------------------
-- ----------------------------
-- Records of jz_cachedata
-- ----------------------------
-- ----------------------------
-- Records of jz_chain
-- ----------------------------
-- ----------------------------
-- Records of jz_classtype
-- ----------------------------
-- ----------------------------
-- Records of jz_collect
-- ----------------------------
-- ----------------------------
-- Records of jz_collect_type
-- ----------------------------
-- ----------------------------
-- Records of jz_comment
-- ----------------------------
-- ----------------------------
-- Records of jz_ctype
-- ----------------------------
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('1','基本设置','base',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('2','高级设置','high-level',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('3','搜索配置','searchconfig',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('4','邮件订单','email-order',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('5','支付配置','payconfig',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('6','公众号配置','wechatbind',1,1);
INSERT INTO `jz_ctype` (`id`,`title`,`action`,`sys`,`isopen`) VALUES ('7','积分配置','jifenset',1,1);
-- ----------------------------
-- Records of jz_customurl
-- ----------------------------
-- ----------------------------
-- Records of jz_fields
-- ----------------------------
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('1','url','links','链接地址', NULL,'1',',0,','255', NULL,'0','1','1','1','0','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('2','title','links','链接名称', NULL,'1', NULL,'255', NULL,'1','1','1','1','1','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('3','email','message','联系邮箱', NULL,'1', NULL,'255', NULL,'0','0','1','1','1','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('4','keywords','tags','关键词','尽量简短，但不能重复','1', NULL,'50', NULL,'0','1','1','1','1','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('5','newname','tags','替换词','尽量简短，但不能重复，20字以内，可不填。【已废弃】','1', NULL,'50', NULL,'0','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('7','num','tags','替换次数','一篇文章内替换的次数，默认-1，全部替换【已废弃】','4', NULL,'4', NULL,'0','0','1','0','0','0', NULL,'-1','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('8','target','tags','打开方式', NULL,'7', NULL,'50','新窗口=_blank,本窗口=_self','0','0','1','0','0','0', NULL,'_blank','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('9','number','tags','标签数','无需填写，程序自动处理','4', NULL,'11', NULL,'0','0','1','1','0','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('10','member_id','article','用户','前台会员，无需填写','15', NULL,'11','3,username','0','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('11','member_id','product','用户','前台会员，无需填写','15', NULL,'11','3,username','0','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('12','member_id','links','发布用户','前台会员，无需填写','13', NULL,'11','3,username','0','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('13','target','links','外链URL','默认为空，系统访问内容则直接跳转到此链接','1', NULL,'255', NULL,'0','0','0','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('14','ownurl','links','自定义URL','默认为空，自定义URL','1', NULL,'255', NULL,'0','0','0','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('15','ownurl','tags','自定义URL','默认为空，自定义URL','1', NULL,'255', NULL,'0','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('16','addtime','links','添加时间','系统自带','11', NULL,'11', NULL,'0','0','0','0','0','0','date_2','0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('17','addtime','tags','添加时间','系统自带','11', NULL,'11', NULL,'0','0','1','1','0','0','date_2','0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('43','molds','product','模型', NULL,'15', NULL,'50', NULL,'1','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('19','title','article','标题', NULL,'1', NULL,'255', NULL,'1','1','1','1','1','1', NULL, NULL,'1','0','0','250','1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('20','tid','article','所属栏目', NULL,'17', NULL,'13', NULL,'1','1','1','1','1','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('21','molds','article','模型', NULL,'15', NULL,'50', NULL,'1','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('22','htmlurl','article','栏目链接', NULL,'1', NULL,'255', NULL,'1','0','1','0','0','0', NULL, NULL,'1','0','1', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('23','keywords','article','关键词', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('24','description','article','简介', NULL,'2', NULL,'0', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('25','seo_title','article','SEO标题', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('26','userid','article','管理员', NULL,'15', NULL,'11','11,name','1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('27','litpic','article','缩略图', NULL,'5', NULL,'255', NULL,'1','0','1','1','0','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('28','body','article','内容', NULL,'3', NULL,'0', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('29','addtime','article','发布时间', NULL,'11',NULL,'11', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0','150','0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('30','orders','article','排序', NULL,'4', NULL,'4', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('31','hits','article','点击量', NULL,'4', NULL,'11', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('32','isshow','article','是否显示', NULL,'7',',0,','1','显示=1,未审=0,退回=2','1','0','1','1','1','1', NULL,'1','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('33','comment_num','article','评论数', NULL,'4', NULL,'11', NULL,'1','0','1','0','0','0', NULL,'0','1','0','1', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('34','istop','article','是否置顶：1是0否', NULL,'1',',0,1,2,3,4,5,6,7,8,9,10,11,12,13,','2','是=1,否=0','1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('35','ishot','article','是否头条：1是0否', NULL,'1',',0,1,2,3,4,5,6,7,8,9,10,11,12,13,','2','是=1,否=0','1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('36','istuijian','article','是否推荐：1是0否', NULL,'1',',0,1,2,3,4,5,6,7,8,9,10,11,12,13,','2','是=1,否=0','1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('37','tags','article','Tags', NULL,'19',',0,1,2,3,4,5,6,7,8,9,10,11,12,13,','255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('38','target','article','外链', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','1', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('39','ownurl','article','自定义链接', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','1', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('40','jzattr','article','推荐属性', NULL,'16', NULL,'255','14,name','1','0','1','1','1','1', NULL, NULL,'1','0','0','150','0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('41','tids','article','副栏目', NULL,'18', NULL,'255', NULL,'100','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('42','zan','article','点赞数', NULL,'4', NULL,'11', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('44','title','product','标题', NULL,'1', NULL,'255', NULL,'1','1','1','1','1','1', NULL, NULL,'1','100','0','300','1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('45','seo_title','product','SEO标题', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('46','tid','product','所属栏目', NULL,'17', NULL,'11', NULL,'1','0','1','1','1','1', NULL,'0','1','100','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('47','hits','product','点击量', NULL,'4',',0,10,','11', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('48','htmlurl','product','栏目链接', NULL,'1', NULL,'255', NULL,'1','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('49','keywords','product','关键词', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('50','description','product','简介', NULL,'2', NULL,'0', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('51','litpic','product','缩略图', NULL,'5', NULL,'255', NULL,'1','0','1','1','0','1', NULL, NULL,'1','100','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('52','stock_num','product','库存', NULL,'1', NULL,'11', NULL,'1','0','1','1','0','0', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('53','price','product','价格', NULL,'1', NULL,'10,2', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('54','pictures','product','图集', NULL,'6',',0,1,2,3,4,5,6,7,8,9,10,11,12,13,', NULL, NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('55','isshow','product','是否显示', NULL,'7',',0,','1','显示=1,未审=0,退回=2','1','0','1','1','0','1', NULL,'1','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('56','comment_num','product','评论数', NULL,'4', NULL,'11', NULL,'1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('57','body','product','内容', NULL,'3', NULL,'0', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('58','userid','product','管理员', NULL,'15', NULL,'11','11,name','1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('59','orders','product','排序', NULL,'4', NULL,'4', NULL,'1','0','1','1','0','1', NULL,'0','1','100','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('60','addtime','product','发布时间', NULL,'11',NULL,'11', NULL,'1','0','1','1','0','1', NULL,'0','1','99','0','120','0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('61','istop','product','是否置顶：1是0否', NULL,'1', NULL,'2', NULL,'1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('62','ishot','product','是否头条：1是0否', NULL,'1', NULL,'2', NULL,'1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('63','istuijian','product','是否推荐：1是0否', NULL,'1', NULL,'2', NULL,'1','0','1','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('64','tags','product','Tags', NULL,'19', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('65','target','product','外链', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('66','ownurl','product','自定义链接', NULL,'1', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('67','jzattr','product','推荐属性', NULL,'16', NULL,'255','14,name','1','0','1','1','1','1', NULL, NULL,'1','100','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('68','tids','product','副栏目', NULL,'18', NULL,'255', NULL,'1','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('69','zan','product','点赞数', NULL,'4', NULL,'11', NULL,'1','0','1','1','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('70','isshow','tags','是否显示', NULL,'7', NULL,'1','显示=1,隐藏=0,退回=2','0','0','1','1','1','1', NULL,'1','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('71','lx','product','类型', NULL,'7',',1,6,7,','2','响应式=1,PC=2,手机=3,PC+手机=4,小程序=5','2','0','1','1','1','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('72','color','product','颜色', NULL,'7',',1,6,7,','2','红色=1,橙色=2,黄色=3,绿色=4,蓝色=5,紫色=6,粉色=7','2','0','1','1','1','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('73','hy','product','行业', NULL,'8',',1,6,7,','500','金融/证券=1,IT科技/软件=2,教育/培训=3,珠宝/工艺品=4,五金/机电=5,婚庆/摄影/美容=6,旅游/餐饮/美食=7,房产/汽车/运输=8,休闲/文化=9,医疗/生物/化工=10,儿童/游乐园=11,动物/宠物=12,鲜花/礼物=13,运动/俱乐部=14,生态/农业=15,建筑/装饰=16,广告/网站/设计=17,个人/导航/博客=18','2','0','1','1','1','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('74','title','pingjia','用户名','默认为空','1',',0,10,','255', NULL,'100','0','1','1','1','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('75','tid','pingjia','所属栏目','选择栏目','17',',10,','11', NULL,'100','0','1','1','1','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('76','tids','pingjia','副栏目','绑定后可以在当前模型的其他栏目中显示','18', NULL,'255', NULL,'100','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('77','keywords','pingjia','关键词','每个词用英文逗号(,)拼接','1', NULL,'255', NULL,'100','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('78','tags','pingjia','TAG','每个词用英文逗号(,)拼接','19', NULL,'255', NULL,'100','0','1','0','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('79','litpic','pingjia','头像','可留空','5',',0,10,','255', NULL,'100','0','1','1','0','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('80','description','pingjia','简述','可留空','2',',0,10,','500', NULL,'100','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('81','body','pingjia','内容','可留空','3',',10,','500', NULL,'100','0','1','1','0','0', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('82','member_id','pingjia','发布会员','前台发布会员ID记录','13', NULL,'11','3,username','100','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('83','userid','pingjia','管理员','后台发布管理员ID记录','13', NULL,'11','11,name','100','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('84','target','pingjia','外链URL','默认为空，系统访问内容则直接跳转到此链接','1', NULL,'255','11,name','100','0','0','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('85','ownurl','pingjia','自定义URL','默认为空，自定义URL','1', NULL,'255','11,name','100','0','0','0','0','0', NULL, NULL,'1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('86','hits','pingjia','点击量','系统自动添加','4', NULL,'11', NULL,'100','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('87','comment_num','pingjia','评论数','系统自带','4', NULL,'11', NULL,'100','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('88','zan','pingjia','点赞数','系统自带','4', NULL,'11', NULL,'100','0','0','0','0','0', NULL,'0','1','0','0', NULL,'0');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('89','addtime','pingjia','添加时间','选择时间','11',NULL,'11', NULL,'100','0','1','1','0','1','date_2','0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('90','jzattr','pingjia','推荐属性','1置顶2热点3推荐','16', NULL,'50','14,name','100','0','1','0','0','0', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('91','isshow','pingjia','是否显示','显示隐藏','7',',10,','1','显示=1,隐藏=0,退回=2','100','0','1','1','1','1', NULL,'1','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('92','zhiye','pingjia','职业', NULL,'1',',10,','255', NULL,'100','0','1','1','0','1', NULL, NULL,'1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES ('93','orders','pingjia','排序', NULL,'4', NULL,'4', NULL,'1','0','1','1','0','1', NULL,'0','1','0','0', NULL,'1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (94, 'username', 'member', '用户昵称', NULL, 1, ',0,', '255', NULL, 2, 1, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (95, 'openid', 'member', '微信OPENID', NULL, 1, ',0,', '255', NULL, 2, 0, 1, 1, 0, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (96, 'sex', 'member', '性别', NULL, 12, ',0,', '2', '男=1,女=2,未知=0', 2, 0, 1, 1, 1, 1, NULL, '0', 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (97, 'gid', 'member', '会员分组', NULL, 13, ',0,', '11', '6,name', 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (98, 'litpic', 'member', '会员头像', NULL, 5, ',0,', '255', NULL, 2, 0, 1, 1, 0, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (99, 'tel', 'member', '电话号码', NULL, 1, ',0,', '12', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (100, 'jifen', 'member', '积分', NULL, 14, ',0,', '10,2', NULL, 2, 0, 1, 1, 0, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (101, 'money', 'member', '金币', NULL, 14, ',0,', '10,2', NULL, 2, 0, 1, 1, 0, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (102, 'email', 'member', '邮箱', NULL, 1, ',0,', '255', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (103, 'province', 'member', '省份', NULL, 1, ',0,', '50', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (104, 'city', 'member', '城市', NULL, 1, ',0,', '50', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (105, 'address', 'member', '详细地址', NULL, 1, ',0,', '255', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (106, 'regtime', 'member', '注册时间', NULL, 11, ',0,', '11', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (107, 'logintime', 'member', '最近登录', NULL, 11, ',0,', '11', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (108, 'signature', 'member', '个性签名', NULL, 1, ',0,', '255', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (109, 'birthday', 'member', '生日', NULL, 1, ',0,', '50', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (110, 'pid', 'member', '推荐人', NULL, 13, ',0,', '11', '3,username', 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (111, 'isshow', 'member', '状态', '封禁后不能登录', 7, ',0,', '2', '正常=1,封禁=0', 2, 0, 1, 1, 1, 1, NULL, '1', 1, 0, 0, NULL, 0);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (112, 'title', 'message', '标题', NULL, 1, ',4,', '255', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (113, 'user', 'message', '用户昵称', NULL, 1, ',4,', '255', NULL, 2, 0, 1, 0, 1, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (114, 'tid', 'message', '相关栏目', NULL, 13, ',4,', '11', '2,classname', 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (115, 'tel', 'message', '联系电话', NULL, 1, ',4,', '20', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (116, 'ip', 'message', '留言IP', NULL, 1, ',4,', '50', NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (117, 'body', 'message', '留言内容', NULL, 3, ',4,', NULL, NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (118, 'isshow', 'message', '是否审核', NULL, 7, ',4,', '1', '未审核=0,已审核=1', 2, 0, 1, 1, 1, 1, NULL, '0', 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (119, 'addtime', 'message', '提交时间', NULL, 11, ',4,', '11', NULL, 2, 0, 1, 1, 1, 1, NULL, NULL, 1, 0, 0, NULL, 1);
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`isadmin`,`issearch`,`islist`,`format`,`vdata`,`isajax`,`listorders`,`isext`,`width`,`ishome`) VALUES (120, 'reply', 'message', '回复留言', NULL, 3, ',4,', NULL, NULL, 2, 0, 1, 1, 0, 0, NULL, NULL, 1, 0, 0, NULL, 1);
-- ----------------------------
-- Records of jz_hook
-- ----------------------------
-- ----------------------------
-- Records of jz_layout
-- ----------------------------
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('1','系统默认','[]','[{"name":"内容管理","icon":"&amp;#xe6b4;","nav":[{"key":"16948","title":"内容列表","value":"9","icon":""},{"key":"12349","title":"商品列表","value":"105","icon":""},{"key":"19748","title":"推荐属性","value":"202","icon":""}]},{"name":"栏目管理","icon":"&amp;#xe699;","nav":[{"key":"10518","title":"栏目列表","value":"42","icon":""}]},{"name":"互动管理","icon":"&amp;#xe69b;","nav":[{"key":"11832","title":"留言列表","value":"22","icon":""},{"key":"11262","title":"评论列表","value":"16","icon":""}]},{"name":"SEO设置","icon":"&amp;#xe6b3;","nav":[{"key":"16628","title":"TAG列表","value":"147","icon":""},{"key":"16214","title":"友情链接","value":"95","icon":""},{"key":"16254","title":"网站地图","value":"153","icon":""},{"key":"16917","title":"内链列表","value":"210","icon":""}]},{"name":"用户管理","icon":"&amp;#xe6b8;","nav":[{"key":"11957","title":"会员列表","value":"2","icon":""},{"key":"15086","title":"会员分组","value":"118","icon":""},{"key":"10618","title":"会员权限","value":"123","icon":""},{"key":"17578","title":"管理员列表","value":"54","icon":""},{"key":"19552","title":"角色管理","value":"49","icon":""},{"key":"10895","title":"权限列表","value":"66","icon":""},{"key":"12582","title":"订单列表","value":"129","icon":""},{"key":"17076","title":"充值列表","value":"177","icon":""}]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":[{"key":"11314","title":"网站设置","value":"40","icon":""},{"key":"10572","title":"桌面设置","value":"70","icon":""},{"key":"18242","title":"导航设置","value":"190","icon":""},{"key":"13002","title":"轮播图","value":"83","icon":""},{"key":"15936","title":"轮播图分类","value":"89","icon":""},{"key":"19847","title":"清理缓存","value":"114","icon":""},{"key":"12739","title":"模板列表","value":"223","icon":""}]},{"name":"扩展管理","icon":"&amp;#xe6ce;","nav":[{"key":"11957","title":"插件列表","value":"76","icon":""},{"key":"13870","title":"图库管理","value":"116","icon":""},{"key":"12472","title":"模型列表","value":"61","icon":""},{"key":"15551","title":"数据库备份","value":"35","icon":""},{"key":"16311","title":"碎片化","value":"194","icon":""},{"key":"18982","title":"公众号菜单","value":"141","icon":""},{"key":"14568","title":"公众号素材","value":"142","icon":""},{"key":"13219","title":"模板制作","value":"143","icon":""},{"key":"17893","title":"生成静态文件","value":"154","icon":""},{"key":"16926","title":"登录日志","value":"115","icon":""}]},{"name":"回收站","icon":"&amp;#xe8a3;","nav":[{"key":"17056","title":"回收站","value":"217","icon":""}]},{"name":"评价管理","icon":"&amp;#xe717;","nav":[{"key":"16835","title":"用户评价","value":"227","icon":""}]}]','0','CMS默认配置，不可删除！','1','1');
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('2','旧版桌面','[]','[{"name":"网站管理","icon":"&amp;#xe699;","nav":["42","9","95","83","147","22"]},{"name":"商品管理","icon":"&amp;#xe698;","nav":["105","129","2","118","123","16","177"]},{"name":"扩展管理","icon":"&amp;#xe6ce;","nav":["76","116","141","142","143","194","35","61","154","153"]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":["40","54","49","190","70","115","114","66"]}]','0','旧版本配置','0','0');
-- ----------------------------
-- Records of jz_level
-- ----------------------------
INSERT INTO `jz_level` (`id`,`name`,`pass`,`tel`,`gid`,`email`,`regtime`,`logintime`,`status`) VALUES ('1','admin','0acdd3e4a8a2a1f8aa3ac518313dab9d','13600136000','1','123456@qq.com','1635997469','1643156842','1');
-- ----------------------------
-- Records of jz_level_group
-- ----------------------------
INSERT INTO `jz_level_group` (`id`,`name`,`isadmin`,`ischeck`,`classcontrol`,`paction`,`tids`,`isagree`,`description`) VALUES ('1','超级管理员','1','0','0',',Fields,', NULL,'1', NULL);
-- ----------------------------
-- Records of jz_likes
-- ----------------------------
-- ----------------------------
-- Records of jz_link_type
-- ----------------------------
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
-- Records of jz_menu
-- ----------------------------
-- ----------------------------
-- Records of jz_message
-- ----------------------------
-- ----------------------------
-- Records of jz_molds
-- ----------------------------
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('1','内容','article','1','1','1','1','1','1','article-list.html','article-details.html','100','0','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('2','栏目','classtype','1','1','1','1','1','1','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('3','会员','member','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('4','订单','orders','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('5','商品','product','1','1','1','1','1','1','list.html','details.html','100','0','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('6','会员分组','member_group','1','1','0','0','1','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('7','评论','comment','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('8','留言','message','1','1','0','0','1','1','message.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('9','轮播图','collect','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('10','友情链接','links','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('11','管理员','level','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('12','TAG','tags','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('13','单页','page','1','1','1','1','1','1','page.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('14','推荐属性','attr','1','1','0','0','0','0','list.html','details.html','100','1','0');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`,`iscontrol`,`ismust`,`isclasstype`,`isshowclass`,`list_html`,`details_html`,`orders`,`ispreview`,`ishome`) VALUES ('15','用户评价','pingjia','0','1','0','1','1','1','lists.html','details.html','100','0','0');
-- ----------------------------
-- Records of jz_orders
-- ----------------------------
-- ----------------------------
-- Records of jz_page
-- ----------------------------
-- ----------------------------
-- Records of jz_pictures
-- ----------------------------
-- ----------------------------
-- Records of jz_pingjia
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
-- ----------------------------
-- Records of jz_recycle
-- ----------------------------
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('39','系统功能','Sys','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('40','网站设置','Sys/index','39','1','1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('60','模型管理','Molds','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('61','模型列表','Molds/index','60','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('62','新增模型','Molds/addMolds','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('63','修改模型','Molds/editMolds','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('64','删除模型','Molds/deleteMolds','60','0','1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('223','模板列表','Template/index','222','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('222','模板管理','Template','0','0','1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('167','高级设置','Sys/ctype/type/high-level','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('168','邮箱订单','Sys/ctype/type/email-order','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('169','支付配置','Sys/ctype/type/payconfig','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('170','公众号配置','Sys/ctype/type/wechatbind','39',1,'1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('181','积分配置','Sys/ctype/type/jifenset','39',1,'1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('199','搜索配置','Sys/ctype/type/searchconfig','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('200','修改字段属性','Fields/editFieldsValue','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('201','推荐属性','Jzattr','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('202','推荐属性','Jzattr/index','201','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('203','新增推荐属性','Jzattr/addAttr','201','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('204','修改推荐属性','Jzattr/editAttr','201','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('205','删除推荐属性','Jzattr/delAttr','201','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('206','修改状态','Jzattr/changeStatus','201','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('207','列表设置','Fields/fieldsList','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('208','获取列表字段','Fields/fieldsList','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('209','内链模块','Jzchain','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('210','内链列表','Jzchain/index','209','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('211','新增内链','Jzchain/addchain','209','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('212','修改内链','Jzchain/editchain','209','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('213','删除内链','Jzchain/delchain','209','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('214','批量删除','Jzchain/delAll','209','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('215','修改状态','Jzchain/changeStatus','209','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('216','回收站','Recycle','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('217','回收站','Recycle/index','216','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('218','恢复数据','Recycle/restore','216','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('219','删除数据','Recycle/del','216','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('220','批量删除','Recycle/delAll','216','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('221','批量恢复','Recycle/restoreAll','216','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('224','安装卸载','Template/actionDo','222','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('225','安装说明','Template/desc','222','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('226','模板更新','Template/update','222','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('227','用户评价列表','Extmolds/index/molds/pingjia','77','1','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('228','新增用户评价','Extmolds/addmolds/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('229','修改用户评价','Extmolds/editmolds/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('230','复制用户评价','Extmolds/copymolds/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('231','删除用户评价','Extmolds/deletemolds/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('232','批量删除用户评价','Extmolds/deleteAll/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('233','批量修改用户评价栏目','Extmolds/changeType/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('234','批量复制用户评价','Extmolds/copyAll/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('235','批量修改用户评价列表','Extmolds/editOrders/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('236','批量审核用户评价','Extmolds/checkAll/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('237','重构字段','Molds/restrucFields','60','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('238','基本配置','Sys/ctype/type/base','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('239','批量修改评价推荐属性','Extmolds/changeAttribute/molds/pingjia','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('240','配置栏目','Sys/systype','39',1,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('241','设置配置状态','Sys/systypestatus','39',0,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('242','修改配置分组','Sys/editctype','39',0,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('243','新增配置分组','Sys/addctype','39',0,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('244','全局配置','Sys/ctype','39',0,'1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('245','修改配置字段','Sys/setfield','39',0,'1');
-- ----------------------------
-- Records of jz_shouchang
-- ----------------------------
-- ----------------------------
-- Records of jz_sysconfig
-- ----------------------------
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('1','web_version','系统版号','版本号是系统自带，请勿改动','0','2.4.3','0', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('2','web_name','网站SEO名称','控制在25个字、50个字节以内','2','极致CMS建站系统','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('3','web_keyword','网站SEO关键词','5个左右，8汉字以内，用英文逗号隔开','2','极致建站,cms,开源cms,免费cms,cms系统,phpcms,免费企业建站,建站系统,企业cms,jizhicms,极致cms,建站cms,建站系统,极致博客,极致blog,内容管理系统','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('4','web_desc','网站SEO描述','控制在80个汉字，160个字符以内','3','极致CMS是开源免费的PHPCMS网站内容管理系统，无商业授权，简单易用，提供丰富的插件，帮您实现零基础搭建不同类型网站（企业站，门户站，个人博客站等），是您建站的好帮手。极速建站，就选极致CMS。','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('5','web_js','统计代码','将百度统计、cnzz等平台的流量统计JS代码放到这里','9', NULL,'1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('6','web_copyright','底部版权','如：&copy; 2016 xxx版权','2','@2020-2099','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('7','web_beian','备案号','如：京ICP备00000000号','2','冀ICP备88888号','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('8','web_tel','网站电话','网站联系电话','2','0666-8888888','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('9','web_tel_400','400电话','400电话','2','400-0000-000','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('10','web_qq','网站QQ','网站QQ','2','12345678','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('11','web_email','网站邮箱','网站邮箱','2','123456@qq.com','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('12','web_address','公司地址','公司地址','2','河北省廊坊市广阳区xxx大厦xx楼001号','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('13','pc_template','PC网站模板','将模板名称填写到此处','2','cms','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('14','wap_template','WAP网站模板','开启了手机端，这个设置才会生效，否则调用电脑端模板','2','cms','2',NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('15','weixin_template','微信网站模板','开启了手机端，这个设置才会生效，否则调用电脑端模板。由于微信内有一些特殊的js，所以可以在这里单独设置微信模板','2','cms','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('16','iswap','是否开启手机端','如果不开启手机端，则默认调用电脑端模板','6','1','2','开启=1,关闭=0','1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('17','isopenhomeupload','是否开启前台上传','关闭后，前台无法上传文件。如果网站没有使用会员，建议关闭前台上传。','6','1','2','开启=1,关闭=0','1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('18','isopenhomepower','是否开启前台权限','开启后前台用户权限可以在后台控制','6','0','2','开启=1,关闭=0','1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('19','cache_time','缓存时间','单位：分钟，留空或0则不设置缓存。如果生成静态文件，静态文件清空后才生效。此设置与缓存完整页面，模板缓存有关。','2','0','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('20','fileSize','限制上传文件大小','0代表不限，单位kb','2','0','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('21','fileType','允许上传文件类型','请用|分割，如：pdf|jpg|png','2','pdf|jpg|jpeg|png|zip|rar|gzip|doc|docx|xlsx','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('22','ueditor_config','后台编辑器导航条配置', "后台UEditor编辑器导航条配置",'3','&quot;fullscreen&quot;, &quot;source&quot;,&quot;undo&quot;, &quot;redo&quot;,&quot;bold&quot;, &quot;italic&quot;, &quot;underline&quot;, &quot;fontborder&quot;, &quot;strikethrough&quot;, &quot;super&quot;, &quot;removeformat&quot;, &quot;formatmatch&quot;, &quot;autotypeset&quot;, &quot;blockquote&quot;, &quot;pasteplain&quot;,&quot;forecolor&quot;, &quot;backcolor&quot;, &quot;insertorderedlist&quot;, &quot;insertunorderedlist&quot;, &quot;selectall&quot;, &quot;cleardoc&quot;,&quot;rowspacingtop&quot;, &quot;rowspacingbottom&quot;, &quot;lineheight&quot;,&quot;customstyle&quot;, &quot;paragraph&quot;, &quot;fontfamily&quot;, &quot;fontsize&quot;,&quot;directionalityltr&quot;, &quot;directionalityrtl&quot;, &quot;indent&quot;,&quot;justifyleft&quot;, &quot;justifycenter&quot;, &quot;justifyright&quot;, &quot;justifyjustify&quot;,&quot;touppercase&quot;, &quot;tolowercase&quot;,&quot;link&quot;, &quot;unlink&quot;, &quot;anchor&quot;, &quot;imagenone&quot;, &quot;imageleft&quot;, &quot;imageright&quot;, &quot;imagecenter&quot;,&quot;simpleupload&quot;, &quot;insertimage&quot;, &quot;emotion&quot;, &quot;scrawl&quot;, &quot;insertvideo&quot;, &quot;music&quot;, &quot;attachment&quot;, &quot;map&quot;, &quot;gmap&quot;, &quot;insertframe&quot;, &quot;insertcode&quot;, &quot;webapp&quot;, &quot;pagebreak&quot;,&quot;template&quot;, &quot;background&quot;,&quot;horizontal&quot;, &quot;date&quot;, &quot;time&quot;, &quot;spechars&quot;, &quot;snapscreen&quot;, &quot;wordimage&quot;,&quot;inserttable&quot;, &quot;deletetable&quot;, &quot;insertparagraphbeforetable&quot;, &quot;insertrow&quot;, &quot;deleterow&quot;, &quot;insertcol&quot;, &quot;deletecol&quot;, &quot;mergecells&quot;, &quot;mergeright&quot;, &quot;mergedown&quot;, &quot;splittocells&quot;, &quot;splittorows&quot;, &quot;splittocols&quot;, &quot;charts&quot;,&quot;print&quot;, &quot;preview&quot;, &quot;searchreplace&quot;, &quot;help&quot;, &quot;drafts&quot;','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('23','search_table','允许前台搜索的表','防止数据泄露,填写允许发布模块标识,留空表示不允许发布,多个表可用|分割,如：article|product','2','article|product','3', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('24','imagequlity','上传图片压缩比例','100%则不压缩，如果PNG是透明图，压缩后背景变黑色。格式如：80','6','75','2','不压缩使用原图=100,95%=95,90%=90,85%=85,80%=80,75%=75,70%=70,65%=65,60%=60,55%=55,50%=50','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('25','ispngcompress','PNG是否压缩','PNG压缩后容易变成背景黑色，关闭后，不会压缩。','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('26','email_server','邮件服务器','smtp.163.com,smtp.qq.com','2','smtp.163.com','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('27','email_port','邮件收发端口','163、126邮件端口(465)，QQ邮件端口(587)','2','465','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('28','shou_email','收件人Email地址', NULL,'2', NULL,'4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('29','send_email','发件人Email地址','指邮件服务器发件邮箱','2', NULL,'4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('30','send_pass','发件人Email秘钥','这个秘钥不是登录密码','2', NULL,'4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('31','send_name','发件人昵称','发件邮箱会带一个昵称','2','极致建站系统','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('32','tj_msg','客户订单通知','购买商品的时候会发送的一条邮件信息','3','尊敬的{xxx}，我们已经收到您的订单！请留意您的电子邮件以获得最新消息，谢谢您！','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('33','send_msg','订单出货通知','发货的时候发送给客户的通知','3','尊敬的{xxx}，我们已确认了您的订单，请于3日内汇款，逾期恕不保留，不便请见谅。汇款完成后，烦请告知客服人员您的交易账号后五位，即完成下单手续，谢谢您。','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('34','yunfei','订单运费','购物下单时会加上这个运费','2','0.00','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('35','paytype','在线支付','0关闭支付，1自主平台支付','6','0','5','关闭=0,开启=1','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('40','alipay_partner','支付宝APPID','账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('41','alipay_key','支付宝key','MD5密钥，安全检验码，由数字和字母组成的32位字符串','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('42','alipay_private_key','支付宝私钥', NULL,'3', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('43','alipay_public_key','支付宝公钥', NULL,'3', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('44','wx_mchid','微信商户mchid','支付相关','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('45','wx_key','微信商户key','支付相关','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('46','wx_appid','微信公众号appid','支付相关','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('47','wx_appsecret','微信公众号appsecret','支付相关','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('48','wx_client_cert','微信apiclient_cert','支付相关','5', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('49','wx_client_key','微信apiclient_key','支付相关','5', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('50','wx_login_appid','公众号appid','用户登录相关，如果跟支付的一样，那就再填写一遍','2', NULL,'6', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('51','wx_login_appsecret','公众号appsecret','用户登录相关，如果跟支付的一样，那就再填写一遍','2', NULL,'6', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('52','wx_login_token','公众号token','用户登录相关，如果跟支付的一样，那就再填写一遍','2', NULL,'6', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('53','huanying','公众号关注欢迎语','公众号关注时发送的第一句推送','3','欢迎关注公众号~','6', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('54','wx_token','公众号token','支付相关','2', NULL,'5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('55','web_logo','网站LOGO', NULL,'1','/static/cms/static/images/logo.png','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('56','admintpl','后台模板风格','内页弹窗：点击新增/修改等操作，页面是一个弹出层，更美观。内嵌页面：点击新增/修改等操作，页面直接进入新页面，不会弹出层。','6','default','2','内页弹窗=default,内嵌页面=tpl','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('59','domain','网站SEO网址','一般不填，全局网址，最后不带/,如：http://www.xxx.com','2', NULL,'1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('61','overtime','订单超时','按小时计算，超过该小时订单过期，仅限于开启支付后，0代表不限制','2','4','4', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('62','islevelurl','开启层级URL','默认关闭层级URL，开启后URL会按照父类层级展现','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('63','iscachepage','缓存完整页面','前台完整页面缓存，结合缓存时间，可以提高访问速度','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('64','isautohtml','自动生成静态','前台访问网站页面，将自动生成静态HTML，下次访问直接进入静态HTML页面','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('65','pc_html','PC静态文件目录','电脑端静态HTML存放目录，默认根目录[ / ]','2','/','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('66','mobile_html','WAP静态文件目录','手机端静态HTML存放目录，默认[ m ]，PC和WAP静态目录不能相同，否则文件会混乱','2','m','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('67','autocheckmessage','是否留言自动审核','开启后，留言自动审核（显示）','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('68','autocheckcomment','是否评论自动审核','开启后评论自动审核（显示）','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('69','mingan','网站敏感词过滤','将敏感词放到里面，用“,”分隔，用{xxx}代替通配内容','3', NULL,'1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('70','iswatermark','是否开启水印','开启水印需要上传水印图片','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('71','watermark_file','水印图片','水印图片在250px以内','1', NULL,'2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('72','watermark_t','水印位置','参考键盘九宫格1-9','2','9','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('73','watermark_tm','水印透明度','透明度越大，越难看清楚水印','6','0','2','不显示=0,10%=10,20%=20,30%=30,40%=40,50%=50,60%=60,70%=70,80%=80,90%=90','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('74','money_exchange','钱包兑换率','站内钱包与RMB的兑换率，即1元=多少金币','2','1','5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('75','jifen_exchange','积分兑换率','站内积分与RMB的兑换率，即1元=多少积分','2','100','5', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('76','isopenjifen','积分支付','开启积分支付后，商品可以用积分支付','6','1','5','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('77','isopenqianbao','钱包支付','开启钱包支付后，商品可以用钱包支付','6','1','5','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('78','isopenweixin','微信支付','开启微信支付后，商品可以用微信支付','6','1','5','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('79','isopenzfb','支付宝支付','开启支付宝支付后，商品可以用支付宝支付','6','1','5','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('80','login_award','每次登录奖励','每天登录奖励积分数，最小为0，每天登录只奖励一次','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('81','login_award_open','登录奖励','开启登录奖励后，登录后就会获得积分奖励','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('82','release_award_open','发布奖励','开启后，发布内容会奖励积分','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('83','release_award','每次发布奖励','每次发布内容奖励积分数','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('84','release_max_award','每天发布最高奖励','每天奖励不超过积分上限，设置0则无上限','2','0','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('85','collect_award_open','收藏奖励','开启后，发布内容被收藏会奖励积分','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('86','collect_award','每次收藏奖励','每次发布内容被收藏奖励积分数','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('87','collect_max_award','每天收藏最高奖励','每天奖励不超过积分上限，设置0则无上限','2','1000','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('88','likes_award_open','点赞奖励','开启后，发布内容被点赞会奖励积分','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('89','likes_award','每次点赞奖励','每次发布内容被点赞奖励积分数','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('90','likes_max_award','每天点赞最高奖励','每天奖励不超过积分上限，设置0则无上限','2','1000','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('91','comment_award_open','评论奖励','开启后，发布内容被评论会奖励积分','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('92','comment_award','每次评论奖励','每次发布内容被评论奖励积分数','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('93','comment_max_award','每天评论最高奖励','每天奖励不超过积分上限，设置0则无上限','2','1000','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('94','follow_award_open','关注奖励','开启后，用户被粉丝关注会奖励积分','6','1','7','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('95','follow_award','每次关注奖励','每次被关注奖励积分数','2','1','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('96','follow_max_award','每天关注最高奖励','每天关注奖励不超过积分上限，设置0则无上限','2','1000','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('97','isopenemail','发送邮件','是否开启邮件发送','6','1','4','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('98','closeweb','关闭网站','关闭网站后，前台无法访问，后台可以进入','6','0','1','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('99','closetip','关站提示', NULL,'3','抱歉！该站点已经被管理员停止运行，请联系管理员了解详情！','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('100','admin_save_path','后台文件存储路径','默认static/upload/{yyyy}/{mm}/{dd}，存储路径相对于根目录，最后不能带斜杠[ / ]','2','static/upload/{yyyy}/{mm}/{dd}','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('101','home_save_path','前台文件存储路径','默认static/upload/{yyyy}/{mm}/{dd}，存储路径相对于根目录，最后不能带斜杠[ / ]','2','static/upload/{yyyy}/{mm}/{dd}','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('102','isajax','是否开启前台AJAX','开启后AJAX，前台可以通过栏目链接+ajax=1获取JSON数据','6','0','2', '开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('104','invite_award_open','是否开启邀请奖励','开启邀请后则会奖励','6','0','7', '开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('105','invite_type','邀请奖励类型', NULL,'6','jifen','7', '积分=jifen,金币=money','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('106','invite_award','邀请奖励数量', NULL,'2','0','7', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('107','web_phone','网站手机', NULL,'2','0','1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('108','web_weixin','站长微信', NULL,'1', NULL,'1', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('110','isregister','前台用户注册','关闭前台注册后，前台无法进入注册页面','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('111','onlyinvite','仅邀请码注册','开启后，必须通过邀请链接才能注册！','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('112','release_table','允许前台发布模块','防止数据泄露,填写允许发布模块标识,留空表示不允许发布,多个表可用|分割','2','article|product','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('113','search_words','前台搜索的字段','可以设置搜索表中的相关字段进行模糊查询,多个字段可用|分割','2','title','3', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('114','closehomevercode','前台验证码','关闭后，登录注册不需要验证码','6','0','2','关闭=1,开启=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('115','closeadminvercode','后台验证码','关闭后，后台管理员登录不需要验证码','6','0','2','关闭=1,开启=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('116','tag_table','TAG包含模型','在tag列表上查询的相关模型,多个模型标识可用|分割,如：article|product','2','article|product','2', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('118','isopendmf','支付宝当面付', NULL,'6','1','5','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('119','search_words_muti','前台多模块搜索的字段','多个模块直接必须都有相同的字段，否则会报错','3','title','3', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('120','search_table_muti','多模块允许搜索的表','防止数据泄露,填写允许搜索的表名,留空表示不允许搜索,多个表可用|分割,如：article|product','2','article|product','3', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('121','search_fields_muti','允许查询显示的字段','多模块搜索允许查询显示的字段','3','id,tid,litpic,title,tags,keywords,molds,htmlurl,description,addtime,userid,member_id,hits,ownurl,target','3', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('122','ueditor_user_config','前台编辑器设置','前台的编辑器功能菜单设置','3','&quot;undo&quot;, &quot;redo&quot;, &quot;|&quot;,&quot;paragraph&quot;,&quot;bold&quot;,&quot;forecolor&quot;,&quot;fontfamily&quot;,&quot;fontsize&quot;, &quot;italic&quot;, &quot;blockquote&quot;, &quot;insertparagraph&quot;, &quot;justifyleft&quot;, &quot;justifycenter&quot;, &quot;justifyright&quot;,&quot;justifyjustify&quot;,&quot;|&quot;,&quot;indent&quot;, &quot;insertorderedlist&quot;, &quot;insertunorderedlist&quot;,&quot;|&quot;, &quot;insertimage&quot;, &quot;inserttable&quot;, &quot;deletetable&quot;, &quot;insertparagraphbeforetable&quot;, &quot;insertrow&quot;, &quot;deleterow&quot;, &quot;insertcol&quot;, &quot;deletecol&quot;,&quot;mergecells&quot;, &quot;mergeright&quot;, &quot;mergedown&quot;, &quot;splittocells&quot;, &quot;splittorows&quot;, &quot;splittocols&quot;, &quot;|&quot;,&quot;drafts&quot;, &quot;|&quot;,&quot;fullscreen&quot;','2', NULL,'1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('123','article_config','内容配置', NULL,'3','{"seotitle":1,"litpic":1,"description":1,"tags":1,"filter":"title,keywords,body"}','0', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('124','product_config','商品配置', NULL,'3','{"seotitle":1,"litpic":1,"description":1,"tags":1,"filter":"title,keywords,body"}','0', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('125','isdebug','PHP调试','测试环境，开启调试，提示错误，实时更新模板。正式上线，请关闭调试，打开页面更快。','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('126','plugins_config','插件配置', NULL,'2','http://api.jizhicms.cn/plugins.php','0', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('127','template_config','插件配置', NULL,'2','http://api.jizhicms.cn/template.php','0', NULL,'0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('128','closesession','前台SESSION','关闭前台SESSION后，前台会员模块无法使用，但是可以减少session缓存文件。纯内容网站可以关闭，使用会员支付等必须开启','6','0','2','关闭=1,开启=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('129','messageyzm','留言验证码','开启后，前台留言需要填写验证码','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('130','homerelease','前台发布审核','开启后需要后台审核，关闭则不需要','6','1','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('131','hideclasspath','栏目隐藏.html','开启后栏目链接将没有.html后缀','6','0','2','开启=1,关闭=0','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('132','classtypemaxlevel','栏目全局递归','默认开启，栏目超过20个，请关闭此选项，有一定程度提升访问速度！','6','0','2','开启=1,关闭=0','1','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`,`typeid`,`config`,`orders`,`sys`) VALUES ('133','hidetitleonliy','字段重复检测', '将【模块标识-检测字段】填写进去，用|进行分割，将会进行标题重复检测。如：article-title|product-title','2','article-title|product-title','2', NULL,'0','1');
-- ----------------------------
-- Records of jz_tags
-- ----------------------------
-- ----------------------------
-- Records of jz_task
-- ----------------------------

