<?php

/**
 * 访问统计插件 —— 安装 / 卸载 / 配置
 * 目录：app/admin/exts/jzvisit
 */

namespace app\admin\exts;

use frphp\lib\Controller;

class PluginsController extends Controller
{
	/** 插件标识（必须与插件目录名、plugins.filepath 一致） */
	private $plugin = 'jzvisit';

	public function _init(){
		//后台未登录直接跳登录
		if(!isset($_SESSION['admin']) || empty($_SESSION['admin']['id'])){
			Redirect(U('Login/index'));
		}
		$this->tpl = '@'.dirname(__FILE__).'/tpl/';
		$this->webconf = webConf();
	}

	/* ==================== 配置项默认值 ==================== */
	public static function defaults(){
		return [
			'isopen'          => 1,//是否开启前台统计
			'record_interval' => 60,//同一IP同一页面记录间隔(秒)，0=每次都记录
			'ignore_admin'    => 1,//不记录已登录后台的管理员
			'record_spider'   => 1,//是否记录搜索引擎蜘蛛
			'sync_hits'       => 0,//是否把访问次数同步写入内容表 hits
			'only_detail'     => 0,//只记录内容详情页
			'keep_days'       => 90,//明细默认保留天数（后台一键清理用）
			'ignore_ip'       => '',//不统计的IP，多个用英文逗号分隔
		];
	}

	/* ==================== 安装 ==================== */
	public function install(){
		//1、创建数据表（已存在则跳过）
		if(!$this->tableExists('visit_log')){
			M()->runSql("CREATE TABLE `".DB_PREFIX."visit_log` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `aid` int(11) NOT NULL DEFAULT 0 COMMENT '内容ID，0为非详情页',
			  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
			  `tid` int(11) NOT NULL DEFAULT 0 COMMENT '栏目ID',
			  `classname` varchar(100) DEFAULT NULL COMMENT '栏目名称',
			  `title` varchar(255) DEFAULT NULL COMMENT '页面/内容标题',
			  `url` varchar(255) DEFAULT NULL COMMENT '访问地址',
			  `referer` varchar(255) DEFAULT NULL COMMENT '来源地址',
			  `ip` varchar(45) DEFAULT NULL COMMENT '访客IP',
			  `os` varchar(30) DEFAULT NULL COMMENT '操作系统',
			  `browser` varchar(30) DEFAULT NULL COMMENT '浏览器',
			  `user_agent` varchar(255) DEFAULT NULL COMMENT '浏览器UA',
			  `ismobile` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否手机端',
			  `is_spider` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否蜘蛛',
			  `member_id` int(11) NOT NULL DEFAULT 0 COMMENT '会员ID',
			  `day` int(11) NOT NULL DEFAULT 0 COMMENT '日期(当日0点时间戳)',
			  `addtime` int(11) NOT NULL DEFAULT 0 COMMENT '访问时间',
			  PRIMARY KEY (`id`),
			  KEY `k_day` (`day`),
			  KEY `k_tid` (`tid`),
			  KEY `k_aid` (`aid`),
			  KEY `k_ip` (`ip`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='前台浏览记录'");
		}
		if(!$this->tableExists('visit_day')){
			M()->runSql("CREATE TABLE `".DB_PREFIX."visit_day` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `day` int(11) NOT NULL DEFAULT 0 COMMENT '日期(当日0点时间戳)',
			  `tid` int(11) NOT NULL DEFAULT 0 COMMENT '栏目ID，0为首页/其他',
			  `molds` varchar(50) DEFAULT NULL COMMENT '模型标识',
			  `pv` int(11) NOT NULL DEFAULT 0 COMMENT '浏览次数',
			  `spider` int(11) NOT NULL DEFAULT 0 COMMENT '蜘蛛次数',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uk_day_tid` (`day`,`tid`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='前台浏览统计(按天)'");
		}

		//2、注册后台权限与菜单
		$this->regRuler();

		//3、注册前台钩子（模块 home + 控制器 Home + 全部方法）
		$this->regHook();

		setCache('hook',null);
		setCache('jzvisit_config',null);
		return true;
	}

	/* ==================== 卸载 ==================== */
	public function uninstall(){
		//移除钩子
		M('hook')->delete(['plugins_name'=>$this->plugin]);
		//移除权限与菜单
		M('ruler')->delete(" fc='Visit' or fc like 'Visit/%' ");
		//移除桌面左侧“访问统计”分组
		$layouts = M('layout')->findAll();
		if($layouts){
			foreach($layouts as $lay){
				$left = json_decode($lay['left_layout'],true);
				if(!is_array($left)) continue;
				$new = [];
				$changed = false;
				foreach($left as $v){
					if(isset($v['name']) && $v['name']=='访问统计'){ $changed = true; continue; }
					$new[] = $v;
				}
				if($changed){
					M('layout')->update(['id'=>$lay['id']],['left_layout'=>json_encode($new,JSON_UNESCAPED_UNICODE)]);
				}
			}
		}
		//说明：为方便重装不丢数据，数据表（jz_visit_log / jz_visit_day）保留
		setCache('hook',null);
		setCache('jzvisit_config',null);
		return true;
	}

	/* ==================== 表是否存在 ==================== */
	private function tableExists($table){
		if(!preg_match('/^[a-z0-9_]+$/i',$table)) return false;
		$list = M()->findSql("SHOW TABLES LIKE '".DB_PREFIX.$table."'");
		return $list ? true : false;
	}

	/* ==================== 注册后台菜单/权限 ==================== */
	private function regRuler(){
		$parent = M('ruler')->find(['fc'=>'Visit','pid'=>0]);
		if($parent){
			$pid = $parent['id'];
			M('ruler')->update(['id'=>$pid],['name'=>'访问统计','isdesktop'=>1]);
		}else{
			$pid = M('ruler')->add(['name'=>'访问统计','fc'=>'Visit','pid'=>0,'isdesktop'=>1,'sys'=>0]);
		}
		$menus = [
			['name'=>'统计概览','fc'=>'Visit/index','isdesktop'=>1],
			['name'=>'浏览记录','fc'=>'Visit/lists','isdesktop'=>1],
			['name'=>'内容排行','fc'=>'Visit/content','isdesktop'=>1],
			['name'=>'清理记录','fc'=>'Visit/clear','isdesktop'=>0],
		];
		$ids = [];
		foreach($menus as $m){
			$exist = M('ruler')->find(['fc'=>$m['fc']]);
			if($exist){
				$ids[] = $exist['id'];
				continue;
			}
			$ids[] = M('ruler')->add([
				'name'      => $m['name'],
				'fc'        => $m['fc'],
				'pid'       => $pid,
				'isdesktop' => $m['isdesktop'],
				'sys'       => 0,
			]);
		}
		$this->regLayout($ids);
	}

	/* ==================== 注册桌面左侧菜单 ==================== */
	private function regLayout($ids){
		$layout = M('layout')->find(['isdefault'=>1]);
		if(!$layout){
			$layout = M('layout')->find();
		}
		if(!$layout) return;
		$left = json_decode($layout['left_layout'],true);
		if(!is_array($left)) $left = [];
		foreach($left as $v){
			if(isset($v['name']) && $v['name']=='访问统计') return;
		}
		$titles = ['统计概览','浏览记录','内容排行'];
		$nav = [];
		foreach($ids as $k=>$id){
			if($k>2) continue;
			$nav[] = ['key'=>(string)mt_rand(10000,99999),'title'=>$titles[$k],'value'=>(string)$id,'icon'=>''];
		}
		$left[] = ['name'=>'访问统计','icon'=>'&amp;#xe6ae;','nav'=>$nav];
		M('layout')->update(['id'=>$layout['id']],['left_layout'=>json_encode($left,JSON_UNESCAPED_UNICODE)]);
	}

	/* ==================== 注册前台钩子 ==================== */
	private function regHook(){
		$w = ['plugins_name'=>$this->plugin];
		$data = [
			'module'          => 'home',//框架内 "app\\home" == $app_home，必须是小写 home
			'namespace'       => 'app\\home\\plugins',
			'controller'      => 'Home',//前台首页/栏目/详情/自定义页都由 Home 控制器处理
			'action'          => '',
			'all_action'      => 1,//该控制器全部方法都触发
			'hook_namespace'  => 'app\\home\\plugins',
			'hook_controller' => 'Visit',//钩子控制器：app/home/plugins/VisitController.php
			'hook_action'     => 'record',
			'orders'          => 10,
			'isopen'          => 1,
			'updatetime'      => time(),
		];
		if(M('hook')->find($w)){
			M('hook')->update($w,$data);
		}else{
			$data['plugins_name'] = $this->plugin;
			$data['addtime'] = time();
			M('hook')->add($data);
		}
	}

	/* ==================== 安装说明 ==================== */
	public function desc(){
		$this->display($this->tpl.'plugins-description.html');
	}

	/* ==================== 配置页 ==================== */
	public function setconf($plugins){
		$this->plugins = $plugins;
		$config = json_decode($plugins['config'],1);
		if(!is_array($config)) $config = [];
		$this->config = array_merge(self::defaults(),$config);
		$this->display($this->tpl.'plugins-body.html');
	}

	/* ==================== 保存配置 ==================== */
	public function setconfigdata($data){
		$id = isset($data['id']) ? intval($data['id']) : 0;
		if(!$id){
			JsonReturn(['code'=>1,'msg'=>'参数错误！']);
		}
		$config = [
			'isopen'          => $this->ck($data,'isopen'),
			'record_interval' => isset($data['record_interval']) ? max(0,intval($data['record_interval'])) : 60,
			'ignore_admin'    => $this->ck($data,'ignore_admin'),
			'record_spider'   => $this->ck($data,'record_spider'),
			'sync_hits'       => $this->ck($data,'sync_hits'),
			'only_detail'     => $this->ck($data,'only_detail'),
			'keep_days'       => isset($data['keep_days']) ? max(0,intval($data['keep_days'])) : 90,
			'ignore_ip'       => isset($data['ignore_ip']) ? trim(strip_tags($data['ignore_ip'])) : '',
		];
		M('plugins')->update(['id'=>$id],['config'=>json_encode($config,JSON_UNESCAPED_UNICODE)]);
		setCache('jzvisit_config',null);
		JsonReturn(['code'=>0,'msg'=>'设置成功！']);
	}

	private function ck($data,$key){
		if(!isset($data[$key])) return 0;
		$v = $data[$key];
		return ($v==='on' || $v=='1' || $v===1 || $v===true) ? 1 : 0;
	}
}
