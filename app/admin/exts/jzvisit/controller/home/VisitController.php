<?php

/**
 * 访问统计插件 —— 前台采集器
 * 由 jz_hook 表注册为 Home 控制器的钩子（all_action=1），
 * 每次前台页面（首页/栏目/详情/自定义页）被访问时先执行 record()，再输出页面。
 *
 * 该文件会被安装程序复制到：app/home/plugins/VisitController.php
 */

namespace app\home\plugins;

use frphp\lib\Controller;

class VisitController extends Controller
{
	const PLUGIN = 'jzvisit';

	/** 表是否就绪（同一次请求内缓存） */
	private static $tableCache = [];

	/** 蜘蛛 UA 关键字 */
	private $spiders = [
		'baiduspider','googlebot','bingbot','sogou','360spider','haosouspider','yisouspider',
		'bytespider','toutiaospider','yandexbot','duckduckbot','exabot','ia_archiver',
		'ahrefsbot','semrushbot','mj12bot','dotbot','petalbot','applebot','facebookexternalhit','twitterbot',
	];

	public function _init(){
	}

	/* =========================================================
	 * 钩子入口：记录一次浏览
	 * 说明：本方法在前台页面渲染之前执行，任何异常都不能影响前台输出
	 * ========================================================= */
	public function record($param = null){
		try{
			$this->doRecord();
		}catch(\Exception $e){
			return;
		}catch(\Throwable $e){
			return;
		}
	}

	private function doRecord(){
		if(!$this->tableOk('visit_log')) return;

		$conf = $this->conf();
		if(empty($conf['isopen'])) return;

		//只统计 GET 的前台页面，跳过 ajax 局部请求
		if(isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])!='GET') return;
		if(isset($_GET['ajax']) || isset($_POST['ajax'])) return;
		if(!isset($_SERVER['REQUEST_URI'])) return;

		//已登录后台的管理员（默认不统计自己）
		if(!empty($conf['ignore_admin']) && isset($_SESSION['admin']) && !empty($_SESSION['admin']['id'])) return;

		$ip = GetIP();

		//不统计的 IP
		if(isset($conf['ignore_ip']) && $conf['ignore_ip']!=''){
			$ips = array_filter(array_map('trim',explode(',',$conf['ignore_ip'])));
			if($ips && in_array($ip,$ips)) return;
		}

		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$is_spider = $this->isSpider($ua);
		if($is_spider && empty($conf['record_spider'])) return;

		//解析当前地址
		$info = $this->parseUrl();
		if(!empty($conf['only_detail']) && empty($info['aid'])) return;

		$url = $this->cut($info['url'],250);

		//同 IP 同地址在设定间隔内只记一次（防刷新灌水）
		$interval = isset($conf['record_interval']) ? (int)$conf['record_interval'] : 0;
		if($interval>0){
			$last = M('visit_log')->getField(
				" ip='".addslashes($ip)."' and url='".addslashes($url)."' and addtime>".(time()-$interval),
				'id'
			);
			if($last) return;
		}

		$title = $info['title'];
		if($title==='' && !empty($info['aid'])){
			$title = $this->getTitle($info['molds'],$info['aid']);
		}

		$day = strtotime(date('Y-m-d'));
		$data = [
			'aid'        => (int)$info['aid'],
			'molds'      => $info['molds'],
			'tid'        => (int)$info['tid'],
			'classname'  => $this->cut($info['classname'],50),
			'title'      => $this->cut($title,120),
			'url'        => $url,
			'referer'    => $this->cut($this->referer(),250),
			'ip'         => $ip,
			'os'         => $this->os($ua),
			'browser'    => $this->browser($ua),
			'user_agent' => $this->cut($ua,250),
			'ismobile'   => $this->isMobile($ua) ? 1 : 0,
			'is_spider'  => $is_spider ? 1 : 0,
			'member_id'  => (isset($_SESSION['member']['id']) ? (int)$_SESSION['member']['id'] : 0),
			'day'        => $day,
			'addtime'    => time(),
		];
		M('visit_log')->add($data);

		//按天 + 栏目汇总（蜘蛛只计入 spider，不计 PV）
		if($this->tableOk('visit_day')){
			$row = M('visit_day')->find(['day'=>$day,'tid'=>(int)$data['tid']],null,'id');
			if($row){
				if($is_spider){
					M('visit_day')->goInc(['id'=>$row['id']],'spider',1);
				}else{
					M('visit_day')->goInc(['id'=>$row['id']],'pv',1);
				}
			}else{
				M('visit_day')->add([
					'day'    => $day,
					'tid'    => (int)$data['tid'],
					'molds'  => $data['molds'],
					'pv'     => $is_spider ? 0 : 1,
					'spider' => $is_spider ? 1 : 0,
				]);
			}
		}

		//可选：把浏览量写回内容表 hits（默认关闭，避免与模板里的 /common/gohits 重复计数）
		if(!empty($conf['sync_hits']) && $data['aid'] && $data['molds'] && !$is_spider){
			if(M('molds')->find(['biaoshi'=>$data['molds']])){
				M($data['molds'])->goInc(['id'=>$data['aid']],'hits',1);
			}
		}
		return;
	}

	/* =========================================================
	 * 对外接口：/visit/num/molds/article/id/12.html
	 * 返回某篇内容的浏览量（纯数字，便于模板 AJAX 调用）
	 * ========================================================= */
	public function num($param = null){
		$molds = strtolower($this->frparam('molds',1,'article'));
		$id    = (int)$this->frparam('id');
		$n = 0;
		if($id && $molds && preg_match('/^[a-z0-9_]+$/',$molds)){
			if(M('molds')->find(['biaoshi'=>$molds])){
				$n = (int)M($molds)->getField(['id'=>$id],'hits');
			}else if($this->tableOk('visit_log')){
				$n = (int)M('visit_log')->getCount(['aid'=>$id,'molds'=>$molds,'is_spider'=>0]);
			}
		}
		if($this->frparam('ajax')){
			JsonReturn(['code'=>0,'msg'=>'success','data'=>$n]);
		}
		echo (int)$n;
		exit;
	}

	/* =========================================================
	 * 数据表是否就绪
	 * ========================================================= */
	private function tableOk($table = 'visit_log'){
		if(!preg_match('/^[a-z0-9_]+$/i',$table)) return false;
		if(isset(self::$tableCache[$table])) return self::$tableCache[$table];
		$res = M()->findSql("SHOW TABLES LIKE '".DB_PREFIX.$table."'");
		self::$tableCache[$table] = $res ? true : false;
		return self::$tableCache[$table];
	}

	/* =========================================================
	 * 配置读取（带缓存）
	 * ========================================================= */
	private function conf(){
		$conf = getCache('jzvisit_config');
		if(!is_array($conf)){
			$conf = [];
			$p = M('plugins')->find(['filepath'=>self::PLUGIN]);
			if($p && isset($p['config']) && $p['config']){
				$c = json_decode($p['config'],1);
				if(is_array($c)) $conf = $c;
			}
			$defaults = [
				'isopen'=>1,'record_interval'=>60,'ignore_admin'=>1,'record_spider'=>1,
				'sync_hits'=>0,'only_detail'=>0,'keep_days'=>90,'ignore_ip'=>'',
			];
			$conf = array_merge($defaults,$conf);
			setCache('jzvisit_config',$conf);
		}
		return $conf;
	}

	/* =========================================================
	 * 解析当前访问地址，判断页面类型
	 * 返回：url / aid / molds / tid / classname / title
	 * ========================================================= */
	private function parseUrl(){
		$out = ['url'=>'/','aid'=>0,'molds'=>'','tid'=>0,'classname'=>'首页','title'=>'首页'];

		$uri  = $_SERVER['REQUEST_URI'];
		$path = parse_url($uri,PHP_URL_PATH);
		if($path===null || $path==='') $path = '/';
		$out['url'] = $path;

		//去掉入口文件与后台入口
		$path = str_replace(['/index.php','/'.ADMIN_MODEL],['',''],$path);
		if(defined('ROOT') && ROOT && ROOT!='/'){
			$path = preg_replace('#^'.preg_quote(ROOT,'#').'#','/',$path);
		}
		$path = preg_replace('/\?.*$/','',$path);
		$path = trim($path,'/');
		$path = str_ireplace('.html','',$path);

		if($path=='' || $path=='index.php' || $path=='index'){
			return $out;
		}

		//1、栏目列表页（含分页 news-2）
		$cls = M('classtype')->find(['htmlurl'=>$path,'isclose'=>0],null,'id,classname,molds,htmlurl');
		if(!$cls){
			$segments = array_values(array_filter(explode('/',$path)));
			$last = end($segments);
			if($last && strpos($last,'-')!==false){
				$arr = explode('-',$last);
				array_pop($arr);
				if($arr){
					$segments[count($segments)-1] = implode('-',$arr);
					$cls = M('classtype')->find(['htmlurl'=>implode('/',$segments),'isclose'=>0],null,'id,classname,molds,htmlurl');
				}
			}
		}
		if($cls){
			$out['tid']       = (int)$cls['id'];
			$out['molds']     = strtolower($cls['molds']);
			$out['classname'] = $cls['classname'];
			$out['title']     = $cls['classname'];
			return $out;
		}

		//2、内容详情页：最后一段是数字ID（如 /news/123.html）
		$segments = array_values(array_filter(explode('/',$path)));
		$last = end($segments);
		if(is_numeric($last) && count($segments)>1){
			$aid = (int)$last;
			if($aid>0){
				array_pop($segments);
				$classpath = implode('/',$segments);
				$cls = M('classtype')->find(['htmlurl'=>$classpath,'isclose'=>0],null,'id,classname,molds,htmlurl');
				if($cls && in_array(strtolower($cls['molds']),$this->moldsList())){
					$out['aid']       = $aid;
					$out['tid']       = (int)$cls['id'];
					$out['molds']     = strtolower($cls['molds']);
					$out['classname'] = $cls['classname'];
					$out['title']     = $this->getTitle($out['molds'],$aid);
					return $out;
				}
			}
		}

		//3、其它（搜索页 / 自定义页面）
		$out['classname'] = (stripos($path,'search')!==false) ? '搜索页' : '自定义页面';
		$out['title'] = $path;
		return $out;
	}

	/* 内容标题 */
	private function getTitle($molds,$aid){
		if(!$molds || !$aid) return '';
		if(!in_array($molds,$this->moldsList())) return '';
		$t = M($molds)->getField(['id'=>$aid],'title');
		return $t ? strip_tags($t) : '';
	}

	/* 可用模型标识 */
	private function moldsList(){
		$list = getCache('moldslist');
		if(!is_array($list) || !$list){
			$all = M('molds')->findAll();
			$list = [];
			foreach($all as $v){ $list[] = strtolower($v['biaoshi']); }
			setCache('moldslist',$list);
		}
		return $list;
	}

	/* 来源地址 */
	private function referer(){
		$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		if($ref=='') return '';
		if($ref===current_url()) return '';
		return $ref;
	}

	/* 蜘蛛识别 */
	private function isSpider($ua){
		if($ua=='') return false;
		$ua = strtolower($ua);
		foreach($this->spiders as $s){
			if(strpos($ua,$s)!==false) return true;
		}
		return false;
	}

	/* 移动端识别 */
	private function isMobile($ua){
		if($ua=='') return false;
		return (bool)preg_match('/(android|iphone|ipad|ipod|windows phone|mobile|blackberry|micromessenger)/i',$ua);
	}

	/* 操作系统 */
	private function os($ua){
		if($ua=='') return '未知';
		$list = [
			'Windows NT 10'=>'Windows 10','Windows NT 6.3'=>'Windows 8.1','Windows NT 6.1'=>'Windows 7',
			'Windows NT 6.0'=>'Windows Vista','Windows NT 5'=>'Windows XP','Windows'=>'Windows',
			'Android'=>'Android','iPhone'=>'iOS','iPad'=>'iOS','Mac OS X'=>'Mac OS','Macintosh'=>'Mac OS',
			'Ubuntu'=>'Ubuntu','Linux'=>'Linux','FreeBSD'=>'FreeBSD','OpenBSD'=>'OpenBSD',
		];
		foreach($list as $k=>$v){ if(stripos($ua,$k)!==false) return $v; }
		return '未知';
	}

	/* 浏览器 */
	private function browser($ua){
		if($ua=='') return '未知';
		$list = [
			'MicroMessenger'=>'微信','QQBrowser'=>'QQ浏览器','Edg/'=>'Edge','Edge'=>'Edge',
			'OPR/'=>'Opera','Opera'=>'Opera','Firefox'=>'Firefox','Chrome'=>'Chrome','CriOS'=>'Chrome',
			'Safari'=>'Safari','MSIE'=>'IE','Trident'=>'IE','UCBrowser'=>'UC','Quark'=>'夸克',
		];
		foreach($list as $k=>$v){ if(stripos($ua,$k)!==false) return $v; }
		return '其他';
	}

	/* 字符串截断（按字符数，避免字段溢出） */
	private function cut($str,$len){
		$str = str_replace(["\r","\n","\t"],' ',strip_tags((string)$str));
		$str = trim(preg_replace('/\s+/',' ',$str));
		if(function_exists('mb_substr')){
			return mb_substr($str,0,$len,'utf-8');
		}
		return substr($str,0,$len);
	}
}
