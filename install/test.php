<?php die();?>/*
MySQL Database Backup Tools
Server:127.0.0.1:3306
Database:test
Data:2019-09-15
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_article
-- ----------------------------
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('13','极致CMS新闻测试标题1','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','1','10','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('12','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','8','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('11','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('4','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('10','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('9','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('7','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('8','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','1','7','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('14','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('15','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('16','极致CMS新闻测试标题','12','article','xinwenfenleisan','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('17','极致CMS新闻测试标题','13','article','xinwenfenleisi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('18','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/minibus.jpeg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('19','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/building.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('20','极致CMS新闻测试标题','11','article','xinwenfenleier','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/loft.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','0','6','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('21','极致CMS新闻测试标题','10','article','xinwenfenleiyi','极致CMS,演示站','极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容极致CMS新闻测试简介内容','极致CMS新闻测试SEO标题','1','/static/default/assets/img/desk.jpg','<p>极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容极致CMS测试文章内容</p><p><img src="/static/default/assets/img/scenery/image6.jpg"/></p>','1565351871','1','20','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('22','极致CMS需要付费授权吗？','14','article','faq','极致CMS','极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。','极致CMS_建站系统_免费建站系统_建站CMS_需要付费授权吗？','1','','<p>极致CMS不需要付费，是一个免费商用系统，您可以自由的进行二次开发，做网站。但，有一点需要特别注意，不能做形成竞争行业的系统。比如用极致CMS做一个相似的CMS建站系统而不经过官方同意，请仔细阅读使用本系统的服务条款。</p>','1566150298','0','5','1','0');
INSERT INTO `jz_article` (`id`,`title`,`tid`,`molds`,`htmlurl`,`keywords`,`description`,`seo_title`,`userid`,`litpic`,`body`,`addtime`,`orders`,`hits`,`isshow`,`comment_num`) VALUES ('23','极致CMS建站对比其他建站系统，有什么特别明显的优势？','14','article','faq','极致CMS','模板标签简单易学易用，对整个系统权限控制比较完整，系统自由度很高，如果你熟悉她整个架构，那么她可以更改成很多类型的系统，不仅仅是建站系统，她可以是购物网站，她也可以是问答社区等等，自由发挥~','极致CMS建站对比其他建站系统，有什么特别明显的优势？','1','','<p>模板标签简单易学易用，对整个系统权限控制比较完整，系统自由度很高，如果你熟悉她整个架构，那么她可以更改成很多类型的系统，不仅仅是建站系统，她可以是购物网站，她也可以是问答社区等等，自由发挥~</p>','1566150677','0','5','1','0');

-- ----------------------------
-- Table structure for jz_classtype
-- ----------------------------
DROP TABLE IF EXISTS `jz_classtype`;
CREATE TABLE `jz_classtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(50) DEFAULT NULL,
  `molds` varchar(50) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `body` text,
  `orders` int(4) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '1',
  `iscover` tinyint(1) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '访问分组设定，默认0不设定',
  `htmlurl` varchar(50) DEFAULT NULL COMMENT '栏目url命名',
  `lists_html` varchar(50) DEFAULT NULL COMMENT '栏目页HTML',
  `details_html` varchar(50) DEFAULT NULL COMMENT '详情页HTML',
  `lists_num` int(4) DEFAULT '0',
  `comment_num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_classtype
-- ----------------------------
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('1','商品','product','','','','','0','1','0','0','0','shangpin','list','details','9','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('2','新闻','article','','','','','0','1','0','0','0','xinwen','article-list','article-details','4','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('3','联系','article','','','','<p style="text-align: center;">这是后台录入的内容~</p><p style="text-align: center;">这很简单的吧？赶紧去后台试试吧！</p>','0','1','0','0','0','lianxi','contact-us','','10','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('4','留言','message','','','','','0','1','0','0','0','liuyan','message','','10','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('5','关于','article','','','','<p>极致建站系统是一个简单快捷高效的建站CMS，我们的宗旨是追求极致，极力为大众打造一个更好、更快、更方便的建站系统。</p><p>极致建站系统有几大核心优势：</p><p>1、官方手把手指导教学</p><p>2、自由开发，免商业授权</p><p>3、后台自由定制功能展示桌面，方便应对不同客户需求</p><p>4、用户无需自己手动配置伪静态，也支持配置各种格式的自定义链接</p><p>5、对于手机端、Ajax、接口访问数据处理非常友好</p><p>6、对数据输出的调用是完全自由公开的，即你可以在前台输出数据库里面存储的任何数据</p><p>7、自带静态数据缓存，无需更新生成静态页面也能达到静态访问效率</p><p>8、上传图片管理，缓存清理等，方便管理服务器的文件</p><p>9、具备完整的评论点评功能，用户可以在前台配置评论，点评商品</p><p>10、自带一套简单的购物流程</p><p>11、官方提供支付接口，只需简单获得官方授权，即可体验网站支付功能</p><p>12、系统开发自由度相当高，扩展方便</p>','0','1','0','0','0','guanyu','about-us','','10','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('6','分类一','product','','','','','0','1','1','1','0','fenleiyi','list','details','9','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('7','分类二','product','','','','','0','1','1','1','0','fenleier','list','details','9','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('8','分类三','product','','','','','0','1','1','1','0','fenleisan','list','details','9','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('9','分类四','product','','','','','0','1','1','1','0','fenleisi','list','details','9','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('10','新闻分类一','article','','','','','0','1','0','2','0','xinwenfenleiyi','article-list','article-details','4','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('11','新闻分类二','article','','','','','0','1','0','2','0','xinwenfenleier','article-list','article-details','4','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('12','新闻分类三','article','','','','','0','1','0','2','0','xinwenfenleisan','article-list','article-details','4','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('13','新闻分类四','article','','','','','0','1','0','2','0','xinwenfenleisi','article-list','article-details','4','0');
INSERT INTO `jz_classtype` (`id`,`classname`,`molds`,`litpic`,`description`,`keywords`,`body`,`orders`,`isshow`,`iscover`,`pid`,`gid`,`htmlurl`,`lists_html`,`details_html`,`lists_num`,`comment_num`) VALUES ('14','常见问题','article','','','','','0','1','0','0','0','faq','faq','article-details','10','0');

-- ----------------------------
-- Table structure for jz_collect
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect`;
CREATE TABLE `jz_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
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
-- Records of jz_collect
-- ----------------------------
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('1','测试1','测试','1','/static/default/assets/img/scenery/image1.jpg','0','0','0','1565353707','1','');
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('2','测试二','','1','/static/default/assets/img/scenery/image4.jpg','0','0','0','1565353751','1','');
INSERT INTO `jz_collect` (`id`,`title`,`description`,`tid`,`litpic`,`w`,`h`,`orders`,`addtime`,`isshow`,`url`) VALUES ('3','测试三','','1','/static/default/assets/img/scenery/image6.jpg','0','0','0','1565353774','1','');

-- ----------------------------
-- Table structure for jz_collect_type
-- ----------------------------
DROP TABLE IF EXISTS `jz_collect_type`;
CREATE TABLE `jz_collect_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_collect_type
-- ----------------------------
INSERT INTO `jz_collect_type` (`id`,`name`,`addtime`) VALUES ('1','首页轮播图','1565353702');

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
  `body` text COMMENT '回复内容',
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_comment
-- ----------------------------
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('1','6','10','0','0','还不错哦~','1565515278','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('2','6','10','1','1','你买的啥牌子的？','1565517775','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('3','6','10','2','1','楼中楼测试下~','1565518302','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('4','6','22','0','0','很好很漂亮！','1565763496','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('5','6','22','0','0','重新评分','1565763558','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('6','6','22','0','0','测试','1565763691','1','0.0','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('7','6','22','0','0','啦啦','1565763808','1','3.5','1','0');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('8','6','22','7','7','快上课上课是','1565763831','1','0.0','1','1');
INSERT INTO `jz_comment` (`id`,`tid`,`aid`,`pid`,`zid`,`body`,`addtime`,`userid`,`likes`,`isshow`,`isread`) VALUES ('9','6','22','8','7','坎坎坷坷','1565763849','1','0.0','1','1');

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
  `issearch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1显示搜索，0不显示搜索',
  `islist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否列表中显示',
  `format` varchar(50) DEFAULT NULL COMMENT '显示格式化',
  `vdata` varchar(50) DEFAULT NULL COMMENT '字段默认值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_fields
-- ----------------------------
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('22','url','links','链接','','1',',1,2,6,7,8,9,3,4,5,10,','255','','0','1','1','0','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('23','isshow','links','是否显示','','7',',1,2,6,7,8,9,3,4,5,10,','','显示=1,不显示=0','0','1','1','0','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('21','title','links','链接名称','','1',',1,2,6,7,8,9,3,4,5,10,','255','','0','1','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('30','email','message','联系邮箱','','1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','255','','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('24','categories','product','类别','商品类别','7',',1,6,7,8,9,2,10,11,12,13,3,4,5,','','Phones=1,Laptops=2,PC=3,Tablets=4','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('25','brands','product','品牌','','7',',1,6,7,8,9,2,10,11,12,13,3,4,5,','','Samsung=1,Apple=2,HTC=3','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('26','os','product','操作系统','','7',',1,6,7,8,9,2,10,11,12,13,3,4,5,','','Android=1,iOS=2','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('27','display','product','显示屏','','1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','10','','0','1','1','0','0','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('28','camera','product','像素','','1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','20','','0','1','1','0','0','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('29','ram','product','运行内存','','1',',1,6,7,8,9,2,10,11,12,13,3,4,5,','20','','0','1','1','0','0','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('32','keywords','tags','关键词','尽量简短，但不能重复','1',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','50','','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('33','newname','tags','替换词','尽量简短，但不能重复，20字以内，可不填。','1',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','50','','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('34','url','tags','内链','填写详细链接，带http','1',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','255','','0','0','1','1','1','','');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('35','num','tags','替换次数','一篇文章内替换的次数，默认-1，全部替换','4',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','4','','0','0','1','0','1','','-1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('36','isshow','tags','状态','','12',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','20','显示=1,不显示=0','0','0','1','1','1','','1');
INSERT INTO `jz_fields` (`id`,`field`,`molds`,`fieldname`,`tips`,`fieldtype`,`tids`,`fieldlong`,`body`,`orders`,`ismust`,`isshow`,`issearch`,`islist`,`format`,`vdata`) VALUES ('37','target','tags','打开方式','','7',',1,6,15,16,7,8,9,2,10,18,11,19,12,20,13,3,4,5,14,17,','50','新窗口=_blank,本窗口=_self','0','0','1','0','1','','_blank');

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
-- Records of jz_hook
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_layout
-- ----------------------------
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('1','CMS系统默认','[]','[{"name":"栏目管理","icon":"&amp;#xe699;","nav":["42"]},{"name":"内容管理","icon":"&amp;#xe6fb;","nav":["9"]},{"name":"商品管理","icon":"&amp;#xe6cb;","nav":["105"]},{"name":"订单管理","icon":"&amp;#xe722;","nav":["129"]},{"name":"轮播图管理","icon":"&amp;#xe6a8;","nav":["83"]},{"name":"友情链接","icon":"&amp;#xe6f7;","nav":["95"]},{"name":"TAG标签","icon":"&amp;#xe6a0;","nav":["147"]},{"name":"会员管理","icon":"&amp;#xe6b8;","nav":["2","118","123"]},{"name":"留言管理","icon":"&amp;#xe69f;","nav":["22"]},{"name":"评论管理","icon":"&amp;#xe69b;","nav":["16"]},{"name":"公众号相关","icon":"&amp;#xe60e;","nav":["141","142"]},{"name":"管理员管理","icon":"&amp;#xe726;","nav":["54","49","66"]},{"name":"系统扩展","icon":"&amp;#xe6ce;","nav":["35","61","66","70","76","83","143"]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":["40","114","115","116","153","154"]}]','0','系统配置，不可删除！','1','1');
INSERT INTO `jz_layout` (`id`,`name`,`top_layout`,`left_layout`,`gid`,`ext`,`sys`,`isdefault`) VALUES ('2','客户界面','[]','[{"name":"内容管理","icon":"&amp;#xe6fb;","nav":["9"]},{"name":"栏目管理","icon":"&amp;#xe699;","nav":["42"]},{"name":"商品管理","icon":"&amp;#xe6cb;","nav":["105"]},{"name":"订单管理","icon":"&amp;#xe722;","nav":["129"]},{"name":"会员管理","icon":"&amp;#xe6b8;","nav":["2"]},{"name":"网站留言","icon":"&amp;#xe69f;","nav":["22"]},{"name":"友情链接","icon":"&amp;#xe6f7;","nav":["95"]},{"name":"轮播图","icon":"&amp;#xe6a8;","nav":["83"]},{"name":"公众号","icon":"&amp;#xe60e;","nav":["141","142"]},{"name":"系统设置","icon":"&amp;#xe6ae;","nav":["40","35","114","116"]}]','2','网站管理员-客户端-按需配置','0','0');

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
  `email` varchar(50) NOT NULL,
  `regtime` int(11) NOT NULL DEFAULT '0',
  `logintime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `logintime` (`logintime`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_level
-- ----------------------------
INSERT INTO `jz_level` (`id`,`name`,`pass`,`tel`,`gid`,`email`,`regtime`,`logintime`,`status`) VALUES ('1','admin','0acdd3e4a8a2a1f8aa3ac518313dab9d','13600136000','1','123456@qq.com','0','1568247079','1');
INSERT INTO `jz_level` (`id`,`name`,`pass`,`tel`,`gid`,`email`,`regtime`,`logintime`,`status`) VALUES ('13','xadmin','d4c53f13549606cb52e62aa755533a63','','2','','1566292722','1566293778','1');

-- ----------------------------
-- Table structure for jz_level_group
-- ----------------------------
DROP TABLE IF EXISTS `jz_level_group`;
CREATE TABLE `jz_level_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员，最高权限，无视控制器限制',
  `paction` text COMMENT '动作参数，控制器/方法，如Admin/index',
  `isagree` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1允许登录0不允许',
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_level_group
-- ----------------------------
INSERT INTO `jz_level_group` (`id`,`name`,`isadmin`,`paction`,`isagree`,`description`) VALUES ('1','超级管理员','1',',Fields,','1','');
INSERT INTO `jz_level_group` (`id`,`name`,`isadmin`,`paction`,`isagree`,`description`) VALUES ('2','网站管理员','0',',Member,Article,Comment,Message,Fields/get_fields,Index,Sys,Classtype,Extmolds,Collect,Common,Product,Order,Wechat,','1','网站管理员，客户使用分组，权限仅次于超级管理员。');

-- ----------------------------
-- Table structure for jz_links
-- ----------------------------
DROP TABLE IF EXISTS `jz_links`;
CREATE TABLE `jz_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `isshow` text,
  `tid` int(11) NOT NULL DEFAULT '0',
  `htmlurl` varchar(50) DEFAULT NULL,
  `orders` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_links
-- ----------------------------
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('1','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('5','极致CMS','http://www.jizhicms.com','1','0','','0');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('4','极致CMS','http://www.jizhicms.cn','1','0','','1');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('6','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('7','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('8','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('9','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('10','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('11','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('12','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('13','极致CMS','http://www.jizhicms.com','1','0','','2');
INSERT INTO `jz_links` (`id`,`title`,`url`,`isshow`,`tid`,`htmlurl`,`orders`) VALUES ('14','极致CMS','http://www.jizhicms.com','1','0','','2');

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
  `jifen` int(11) NOT NULL DEFAULT '0',
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_member
-- ----------------------------
INSERT INTO `jz_member` (`id`,`username`,`openid`,`pass`,`token`,`sex`,`gid`,`litpic`,`tel`,`jifen`,`likes`,`collection`,`money`,`email`,`address`,`province`,`city`,`regtime`,`logintime`,`isshow`) VALUES ('1','13600136000','','281ab141a12f67f5238719cd876ce96e','6nDiKpPFT0GoYaSh2cVsb9Ezu0WBU3IW','0','1','/Public/Home/201908197586.png','13600136000','0','||10-21||','||7-20||6-18||10-21||6-22||','0.00','123456@qq.com','','','','0','1567681382','1');

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
-- Records of jz_member_group
-- ----------------------------
INSERT INTO `jz_member_group` (`id`,`name`,`description`,`paction`,`pid`,`isagree`,`iscomment`,`ischeckmsg`,`addtime`,`orders`,`discount`,`discount_type`) VALUES ('1','注册会员','前台会员分组，最低等级分组',',Message,Comment,User,Order,Home,Common,','0','1','1','1','0','0','0.00','0');

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_message
-- ----------------------------
INSERT INTO `jz_message` (`id`,`title`,`userid`,`tid`,`aid`,`user`,`ip`,`body`,`tel`,`addtime`,`orders`,`email`,`isshow`) VALUES ('1','我想问下极致CMS的二次开发如何收费？','0','4','0','小明','127.0.0.1','我想接入微信登录，做公众号开发，请问如何收费？','','1566004821','0','',1);
INSERT INTO `jz_message` (`id`,`title`,`userid`,`tid`,`aid`,`user`,`ip`,`body`,`tel`,`addtime`,`orders`,`email`,`isshow`) VALUES ('2','如何套模板标签？','0','4','0','小白','127.0.0.1','常用的模板标签有哪些？如何使用？','13800138000','1566056033','0','123456@qq.com',1);

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_molds
-- ----------------------------
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('1','内容','article','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('2','栏目','classtype','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('3','会员','member','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('5','订单','orders','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('13','评论','comment','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('14','留言','message','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('17','轮播图','collect','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('18','友情链接','links','0','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('6','商品','product','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('7','会员分组','member_group','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('19','管理员','level','1','1');
INSERT INTO `jz_molds` (`id`,`name`,`biaoshi`,`sys`,`isopen`) VALUES ('20','TAG','tags','0','1');

-- ----------------------------
-- Table structure for jz_orders
-- ----------------------------
DROP TABLE IF EXISTS `jz_orders`;
CREATE TABLE `jz_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `paytype` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `tel` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `price` varchar(200) DEFAULT NULL,
  `jifen` int(11) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_orders
-- ----------------------------
INSERT INTO `jz_orders` (`id`,`orderno`,`userid`,`paytype`,`tel`,`username`,`tid`,`price`,`jifen`,`body`,`receive_username`,`receive_tel`,`receive_email`,`receive_address`,`ispay`,`paytime`,`addtime`,`send_time`,`isshow`,`discount`,`yunfei`) VALUES ('1','No.20190814122444','1','','13600136000','13600136000','0','0.01','0','||6-18-1-0.01||','13600136000','13600136000','123456@qq.com','广州天河区','0','0','1565756684','0','1','0.00','0.00');
INSERT INTO `jz_orders` (`id`,`orderno`,`userid`,`paytype`,`tel`,`username`,`tid`,`price`,`jifen`,`body`,`receive_username`,`receive_tel`,`receive_email`,`receive_address`,`ispay`,`paytime`,`addtime`,`send_time`,`isshow`,`discount`,`yunfei`) VALUES ('2','No.20190814142754','1','','13600136000','13600136000','0','0.01','0','||6-22-1-0.01||','13600136000','13600136000','123456@qq.com','广州天河区','0','0','1565764074','0','5','0.00','0.00');

-- ----------------------------
-- Table structure for jz_pictures
-- ----------------------------
DROP TABLE IF EXISTS `jz_pictures`;
CREATE TABLE `jz_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `aid` int(11) NOT NULL DEFAULT '0',
  `size` varchar(50) DEFAULT NULL,
  `litpic` varchar(255) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片集';
-- ----------------------------
-- Records of jz_pictures
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_plugins
-- ----------------------------
INSERT INTO `jz_plugins` (`id`,`name`,`filepath`,`description`,`version`,`author`,`update_time`,`module`,`isopen`,`addtime`,`config`) VALUES ('1','网站多域名绑定模板','website','它可以将模板与域名绑定在一起，实现一个域名对应一个模板的功能','1.0','留恋风2581047041@qq.com','1568044800','Home','1','1568127966','[{"website":"www.2demo1.mm","model":"1","tpl":""},{"website":"www.jizhicms.com","model":"1","tpl":"default"}]');

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
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='用户权限表';
-- ----------------------------
-- Records of jz_power
-- ----------------------------
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('1','Message','留言功能','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('2','Message/index','网站留言','1','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('3','Comment','评论功能','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('4','Comment/index','网站评论','3','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('5','User','个人中心','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('6','User/index','个人中心','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('7','User/userinfo','修改个人资料','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('8','User/order','订单管理','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('9','User/order_del','删除订单','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('10','User/change_img','修改个人头像','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('11','User/comment','评论管理','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('12','User/comment_del','删除评论','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('13','User/likes','喜欢点赞','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('14','User/likes_del','删除点赞','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('15','User/collect','收藏管理','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('16','User/collect_del','删除收藏','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('17','User/cart','购物车','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('18','User/addcart','添加商品到购物车','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('19','User/delcart','删除购物车商品','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('20','Order','订单相关','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('21','Order/create','创建订单','20','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('22','Order/details','查看订单详情','20','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('23','Order/payment','前台支付','20','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('24','Order/pay','提交支付','20','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('25','Home','基本权限','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('26','Home/index','网站首页','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('27','Home/jizhi','网站系统','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('28','Home/error','错误提示','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('29','Home/jizhi_details','查看详情','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('30','Home/start_cache','开启缓存','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('31','Home/end_cache','结束缓存','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('32','Home/search','搜索功能','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('33','Common','公共模块','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('34','Common/uploads','上传文件','33','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('35','Common/qrcode','生成二维码','33','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('36','Common/get_fields','获取扩展内容','33','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('37','Screen','筛选模块','0','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('38','Screen/index','筛选列表','37','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('39','Screen/error','错误提示','37','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('40','User/collectAction','收藏模块','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('41','User/likesAction','点赞模块','5','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('42','Home/searchAll','全局搜索','25','1');
INSERT INTO `jz_power` (`id`,`action`,`name`,`pid`,`isagree`) VALUES ('43','Common/vercode','生成图形验证码','33','1');

-- ----------------------------
-- Table structure for jz_product
-- ----------------------------
DROP TABLE IF EXISTS `jz_product`;
CREATE TABLE `jz_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `categories` text,
  `brands` text,
  `os` text,
  `display` varchar(10) DEFAULT NULL,
  `camera` varchar(20) DEFAULT NULL,
  `ram` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='商品表';
-- ----------------------------
-- Records of jz_product
-- ----------------------------
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('1','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('2','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('3','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('4','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('5','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('6','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('7','极致商品模块测试DEMO','极致商品模块测试DEMO','13','0','xinwenfenleisi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('8','极致商品模块测试DEMO','极致商品模块测试DEMO','13','0','xinwenfenleisi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('9','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('10','极致商品模块测试DEMO','极致商品模块测试DEMO','6','4','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','3','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('11','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('12','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('13','极致商品模块测试DEMO','极致商品模块测试DEMO','7','3','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('14','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('15','极致商品模块测试DEMO','极致商品模块测试DEMO','8','3','fenleisan','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('16','极致商品模块测试DEMO','极致商品模块测试DEMO','8','3','fenleisan','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('17','极致商品模块测试DEMO','极致商品模块测试DEMO','6','4','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('18','极致商品模块测试DEMO','极致商品模块测试DEMO','6','3','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('19','极致商品模块测试DEMO','极致商品模块测试DEMO','7','5','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','2','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('20','极致商品模块测试DEMO','极致商品模块测试DEMO','7','4','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','4','3','1','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('21','极致商品模块测试DEMO','极致商品模块测试DEMO','7','5','fenleier','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','0','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','2','2','5.2寸','12MP','4GB');
INSERT INTO `jz_product` (`id`,`title`,`seo_title`,`tid`,`hits`,`htmlurl`,`keywords`,`description`,`litpic`,`stock_num`,`price`,`pictures`,`isshow`,`comment_num`,`body`,`userid`,`orders`,`addtime`,`categories`,`brands`,`os`,`display`,`camera`,`ram`) VALUES ('22','极致商品模块测试DEMO','极致商品模块测试DEMO','6','27','fenleiyi','极致商品模块','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec augue nunc, pretium at augue at, convallis pellentesque ipsum. Vestibulum diam risus, sagittis at fringilla at, pulvinar vel risus. Vestibulum dignissim eu nulla eu imperdiet. Morbi mollis tel','/static/default/assets/img/tech/image2.jpg','50','0.01','/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg||/Public/Admin/201908114507.jpg','1','6','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><div class="row"><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div><div class="col-md-7"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div></div><div class="row"><div class="col-md-7 right"><h4>Lorem Ipsum</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quam urna, dignissim nec auctor in, mattis vitae leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div><div class="col-md-5"><figure class="figure"><img class="img-fluid figure-img" src="/static/default/assets/img/tech/image3.png"/></figure></div></div>','1','0','1565359153','1','1','1','5.2寸','12MP','4GB');

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
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('31','获取字段列表','Fields/get_fields','26','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('32','基本功能','Index','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('33','系统界面','Index/index','32','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('34','后台首页','Index/welcome','32','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('35','数据库列表','Index/beifen','32','1','1');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('102','上传文件','Common/uploads','101','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('101','通用模块','Common','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('82','轮播图','Collect','0','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('83','轮播图列表','Collect/index','82','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('84','新增轮播图','Collect/addcollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('85','修改轮播图','Collect/editcollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('86','删除轮播图','Collect/deletecollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('87','复制轮播图','Collect/copycollect','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('88','批量删除轮播图','Collect/deleteAll','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('89','轮播图分类列表','Collect/collectType','82','1','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('90','新增轮播图分类','Collect/collectTypeAdd','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('91','修改轮播图分类','Collect/collectTypeEdit','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('92','删除轮播图分类','Collect/collectTypeDelete','82','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('93','批量复制','Article/copyAll','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('94','批量修改栏目','Article/changeType','8','0','1');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('95','友情链接列表','Extmolds/index/molds/links','77','1','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('96','新增友情链接','Extmolds/addmolds/molds/links','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('97','修改友情链接','Extmolds/editmolds/molds/links','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('98','复制友情链接','Extmolds/copymolds/molds/links','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('99','删除友情链接','Extmolds/deletemolds/molds/links','77','0','0');
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('100','批量删除友情链接','Extmolds/deleteAll/molds/links','77','0','0');
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
INSERT INTO `jz_ruler` (`id`,`name`,`fc`,`pid`,`isdesktop`,`sys`) VALUES ('143','模板制作手册','Index/showlabel','32','1','1');
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
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_sysconfig
-- ----------------------------
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('1','web_version','系统版号','','0','1.5');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('2','web_name','网站SEO名称','','0','极致CMS建站系统');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('3','web_keyword','网站SEO关键词','','0','极致CMS');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('4','web_desc','网站SEO描述','','0','极致CMS');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('5','web_js','统计代码','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('6','web_copyright','底部版权','','0','@2019-2099');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('7','web_beian','备案号','','0','冀ICP备18036869号');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('8','web_tel','公司电话','','0','0316-2222616');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('9','web_tel_400','400电话','','0','400-0000-000');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('10','web_qq','公司QQ','','0','12345678');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('11','web_email','公司邮箱','','0','123456@qq.com');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('12','web_address','公司地址','','0','河北省廊坊市广阳区凯创大厦第1幢2单元1606号');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('13','pc_template','PC端模板','','0','default');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('14','wap_template','WAP端模板','','0','wap');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('15','weixin_template','微信端模板','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('16','iswap','是否开启WAP端','','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('17','isopenhomeupload','是否开启前台上传','','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('18','isopenhomepower','是否开启前台权限','','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('19','cache_time','缓存时间','','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('20','fileSize','限制上传文件大小','','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('21','fileType','允许上传文件类型','','0','txt|pdf|jpg|jpeg|png|zip|rar|gzip|doc|docx|xlsx');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('22','ueditor_config','UEditor编辑器导航条配置','','0','&#039;undo&#039;, &#039;redo&#039;, &#039;|&#039;,
&#039;paragraph&#039;,
&#039;bold&#039;, &#039;italic&#039;, &#039;blockquote&#039;, &#039;insertparagraph&#039;, 
&#039;justifyleft&#039;, &#039;justifycenter&#039;, &#039;justifyright&#039;,&#039;justifyjustify&#039;,&#039;|&#039;,
&#039;indent&#039;, &#039;insertorderedlist&#039;, &#039;insertunorderedlist&#039;,&#039;|&#039;, 
&#039;insertimage&#039;, &#039;insertframe&#039;,
&#039;link&#039;,
&#039;inserttable&#039;, &#039;deletetable&#039;, &#039;insertparagraphbeforetable&#039;, &#039;insertrow&#039;, &#039;deleterow&#039;, &#039;insertcol&#039;, &#039;deletecol&#039;,
&#039;mergecells&#039;, &#039;mergeright&#039;, &#039;mergedown&#039;, &#039;splittocells&#039;, &#039;splittorows&#039;, &#039;splittocols&#039;, &#039;|&#039;,
&#039;drafts&#039;, &#039;|&#039;,,&#039;source&#039;,&#039;fullscreen&#039;,');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('23','search_table','允许前台搜索的表','','0','article|product');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('24','imagequlity','上传图片压缩比例','','0','75');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('25','ispngcompress','PNG是否压缩','','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('26','email_server','邮件服务器','','0','smtp.163.com');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('27','email_port','收发端口','','0','465');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('28','shou_email','收件人Email地址','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('29','send_email','发件人Email地址','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('30','send_pass','发件人Email秘钥','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('31','send_name','发件人昵称','','0','极致建站系统');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('32','tj_msg','客户订单通知','','0','尊敬的{xxx}，我们已经收到您的订单！请留意您的电子邮件以获得最新消息，谢谢您！');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('33','send_msg','订单出货通知','','0','尊敬的{xxx}，我们已确认了您的订单，请于3日内汇款，逾期恕不保留，不便请见谅。汇款完成后，烦请告知客服人员您的交易账号后五位，即完成下单手续，谢谢您。');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('34','yunfei','订单运费','','0','0.00');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('35','paytype','支付方式','0关闭支付，1极致支付，2自主平台支付','0','0');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('36','jizhi_pay_url','极致平台接口','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('37','jizhi_mchid','极致平台商户','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('38','jizhi_appid','极致平台应用appid','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('39','jizhi_key','极致平台应用秘钥','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('40','alipay_partner','自主平台支付宝合作者','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('41','alipay_key','自主平台支付宝key','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('42','alipay_private_key','自主平台支付宝私钥','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('43','alipay_public_key','自主平台支付宝公钥','','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('44','wx_mchid','自主平台微信商户mchid','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('45','wx_key','自主平台微信商户key','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('46','wx_appid','自主平台微信公众号appid','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('47','wx_appsecret','自主平台公众号appsecret','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('48','wx_client_cert','自主平台微信apiclient_cert','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('49','wx_client_key','自主平台微信apiclient_key','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('50','wx_login_appid','公众号appid','用户登录相关，如果跟支付的一样，那就再填写一遍','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('51','wx_login_appsecret','公众号appsecret','用户登录相关，如果跟支付的一样，那就再填写一遍','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('52','wx_login_token','公众号token','用户登录相关，如果跟支付的一样，那就再填写一遍','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('53','huanying','公众号关注欢迎语','公众号关注时发送的第一句推送','0','欢迎关注公众号~');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('54','wx_token','自主平台微信token','支付相关','0','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('55','jz_55','测试ABC','','1','');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('56','admintpl','后台模板风格','默认tpl,原始风格default','0','tpl');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('58','isopenwebsite','是否绑定多域名','开启绑定多域名后，需要到插件中配置','0','1');
INSERT INTO `jz_sysconfig` (`id`,`field`,`title`,`tip`,`type`,`data`) VALUES ('59','domain','网址网址','全局网址，最后不带/斜杠','0','');

-- ----------------------------
-- Table structure for jz_tags
-- ----------------------------
DROP TABLE IF EXISTS `jz_tags`;
CREATE TABLE `jz_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0',
  `orders` int(11) DEFAULT '0',
  `comment_num` int(11) DEFAULT '0',
  `htmlurl` varchar(100) DEFAULT NULL,
  `keywords` varchar(50) DEFAULT NULL,
  `newname` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `num` int(4) DEFAULT '-1',
  `isshow` varchar(20) DEFAULT NULL,
  `target` varchar(50) DEFAULT '_blank',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of jz_tags
-- ----------------------------
INSERT INTO `jz_tags` (`id`,`tid`,`orders`,`comment_num`,`htmlurl`,`keywords`,`newname`,`url`,`num`,`isshow`,`target`) VALUES ('1','0','0','0','','测试','','http://www.jizhicms.cn','-1','0','_blank');

