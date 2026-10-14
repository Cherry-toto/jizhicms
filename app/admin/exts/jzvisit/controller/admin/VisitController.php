<?php

/**
 * 访问统计插件 —— 后台管理
 * 安装后会被复制到：app/admin/plugins/VisitController.php
 * 访问入口：后台 → 访问统计 → 统计概览 / 浏览记录 / 内容排行 / 清理记录
 */

namespace app\admin\plugins;

use app\admin\c\CommonController;

class VisitController extends CommonController
{
	/* 插件模板目录（模板放在插件安装包里，不污染系统模板目录） */
	private function tplDir(){
		$base = APP_PATH.'app/admin/exts/jzvisit/tpl/';
		if(!is_file($base.'visit-index.html')){
			//兼容修改过插件目录名的情况，自动查找同结构插件包
			$files = glob(APP_PATH.'app/admin/exts/*/tpl/visit-index.html');
			if($files && isset($files[0])){
				$base = str_replace('\\','/',dirname($files[0])).'/';
			}
		}
		return '@'.$base;
	}

	/* =========================================================
	 * 统计概览
	 * ========================================================= */
	public function index(){
		if(!$this->hasTable('visit_log')){
			Error(JZLANG('数据表不存在，请到【插件管理】重新安装本插件！'),U('Plugins/index'));
		}
		$this->display($this->tplDir().'visit-index.html');
	}

	/* 概览数据接口 */
	public function indexdata(){
		$days = (int)$this->frparam('days',0,7);
		$days = ($days>0 && $days<=90) ? $days : 7;

		$today = strtotime(date('Y-m-d'));
		$yest  = $today - 86400;

		$todayPv     = (int)M('visit_log')->getCount(['day'=>$today,'is_spider'=>0]);
		$todayUv     = $this->uv($today,$today+86399);
		$todaySpider = (int)M('visit_log')->getCount(['day'=>$today,'is_spider'=>1]);
		$yestPv      = (int)M('visit_log')->getCount(['day'=>$yest,'is_spider'=>0]);
		$yestUv      = $this->uv($yest,$yest+86399);
		$allPv       = (int)M('visit_log')->getCount(['is_spider'=>0]);
		$allUv       = (int)$this->one("SELECT COUNT(DISTINCT ip) AS n FROM `".DB_PREFIX."visit_log` WHERE is_spider=0");

		$trend = [];
		for($i=$days-1;$i>=0;$i--){
			$d = $today - $i*86400;
			$trend[] = [
				'day' => date('m-d',$d),
				'pv'  => (int)M('visit_log')->getCount(['day'=>$d,'is_spider'=>0]),
				'uv'  => $this->uv($d,$d+86399),
			];
		}

		$rows = M()->findSql("SELECT tid,classname,COUNT(*) AS pv FROM `".DB_PREFIX."visit_log`
			WHERE is_spider=0 AND addtime>=".($today-($days-1)*86400)."
			GROUP BY tid,classname ORDER BY pv DESC LIMIT 10");
		$class = [];
		foreach($rows as $v){
			$class[] = [
				'tid'       => (int)$v['tid'],
				'classname' => $v['classname'] ? $v['classname'] : JZLANG('其他'),
				'pv'        => (int)$v['pv'],
			];
		}

		JsonReturn([
			'code'  => 0,
			'msg'   => 'success',
			'today' => ['pv'=>$todayPv,'uv'=>$todayUv,'spider'=>$todaySpider],
			'yest'  => ['pv'=>$yestPv,'uv'=>$yestUv],
			'all'   => ['pv'=>$allPv,'uv'=>$allUv],
			'trend' => $trend,
			'class' => $class,
			'days'  => $days,
		]);
	}

	/* =========================================================
	 * 浏览记录列表
	 * ========================================================= */
	public function lists(){
		if(!$this->hasTable('visit_log')){
			Error(JZLANG('数据表不存在，请到【插件管理】重新安装本插件！'),U('Plugins/index'));
		}
		if($this->frparam('ajax')){
			$where = [];
			$tid = (int)$this->frparam('tid');
			if($tid) $where[] = " tid=".$tid." ";

			$molds = $this->frparam('molds',1);
			if($molds) $where[] = " molds='".addslashes($molds)."' ";

			$ip = $this->frparam('ip',1);
			if($ip) $where[] = " ip like '%".addslashes($ip)."%' ";

			$word = $this->frparam('word',1);
			if($word) $where[] = " (title like '%".addslashes($word)."%' or url like '%".addslashes($word)."%') ";

			$stype = (int)$this->frparam('stype');
			if($stype==1) $where[] = " is_spider=1 ";
			if($stype==2) $where[] = " is_spider=0 and aid>0 ";
			if($stype==3) $where[] = " is_spider=0 and aid=0 ";
			if($stype==4) $where[] = " is_spider=0 and ismobile=1 ";
			if($stype==5) $where[] = " is_spider=0 and referer is not null and referer<>'' ";

			$start = $this->frparam('start',1);
			$end   = $this->frparam('end',1);
			if($start) $where[] = " addtime>=".strtotime($start.' 00:00:00')." ";
			if($end)   $where[] = " addtime<=".strtotime($end.' 23:59:59')." ";

			$where = $where ? implode(' and ',$where) : null;

			$page  = (int)$this->frparam('page',0,1);
			$limit = (int)$this->frparam('limit',0,20);
			$limit = ($limit>0 && $limit<=200) ? $limit : 20;
			if($page<1) $page = 1;
			$start_limit = ($page-1)*$limit;

			$sum   = (int)M('visit_log')->getCount($where);
			$lists = M('visit_log')->findAll($where,'id desc',null,$start_limit.','.$limit);
			$data  = [];
			foreach($lists as $v){
				$u = $v['url'] ? $v['url'] : '/';
				$data[] = [
					'id'        => (int)$v['id'],
					'time'      => $v['addtime'] ? date('Y-m-d H:i:s',$v['addtime']) : '',
					'title'     => htmlspecialchars($v['title'] ? $v['title'] : $u,ENT_QUOTES),
					'url'       => htmlspecialchars($u,ENT_QUOTES),
					'classname' => $v['classname'] ? $v['classname'] : JZLANG('其他'),
					'molds'     => $v['molds'],
					'aid'       => (int)$v['aid'],
					'ip'        => htmlspecialchars($v['ip'],ENT_QUOTES),
					'dev'       => ($v['is_spider'] ? JZLANG('蜘蛛') : ($v['ismobile'] ? JZLANG('手机') : JZLANG('电脑'))).' / '.$v['os'].' / '.$v['browser'],
					'referer'   => $v['referer'] ? htmlspecialchars($v['referer'],ENT_QUOTES) : JZLANG('直接访问'),
					'member_id' => (int)$v['member_id'],
				];
			}
			JsonReturn(['code'=>0,'msg'=>'success','count'=>$sum,'data'=>$data]);
		}

		$this->display($this->tplDir().'visit-lists.html');
	}

	/* =========================================================
	 * 内容排行
	 * ========================================================= */
	public function content(){
		if(!$this->hasTable('visit_log')){
			Error(JZLANG('数据表不存在，请到【插件管理】重新安装本插件！'),U('Plugins/index'));
		}
		if($this->frparam('ajax')){
			$where = [];
			$days = (int)$this->frparam('days',0,30);
			if($days>0){
				$where[] = " addtime>=".(strtotime(date('Y-m-d'))-($days-1)*86400)." ";
			}
			$tid = (int)$this->frparam('tid');
			if($tid){
				$ids = $this->childrenIds($tid);
				$ids[] = $tid;
				$where[] = " tid in (".implode(',',array_unique(array_map('intval',$ids))).") ";
			}
			$word = $this->frparam('word',1);
			if($word) $where[] = " title like '%".addslashes($word)."%' ";
			$where[] = " is_spider=0 and aid>0 ";
			$where = implode(' and ',$where);

			$page  = (int)$this->frparam('page',0,1);
			$limit = (int)$this->frparam('limit',0,20);
			$limit = ($limit>0 && $limit<=200) ? $limit : 20;
			if($page<1) $page = 1;
			$start_limit = ($page-1)*$limit;

			$sum = (int)$this->one("SELECT COUNT(*) AS n FROM (
				SELECT aid FROM `".DB_PREFIX."visit_log` WHERE ".$where." GROUP BY molds,aid) t");

			$rows = M()->findSql("SELECT molds,aid,tid,MAX(classname) AS classname,MAX(title) AS title,
				COUNT(*) AS pv,COUNT(DISTINCT ip) AS uv,MAX(addtime) AS lasttime
				FROM `".DB_PREFIX."visit_log` WHERE ".$where."
				GROUP BY molds,aid,tid
				ORDER BY pv DESC LIMIT ".$start_limit.",".$limit);

			$data = [];
			foreach($rows as $v){
				$data[] = [
					'molds'     => $v['molds'],
					'aid'       => (int)$v['aid'],
					'tid'       => (int)$v['tid'],
					'classname' => $v['classname'] ? $v['classname'] : JZLANG('其他'),
					'title'     => htmlspecialchars($v['title'] ? $v['title'] : ('ID:'.$v['aid']),ENT_QUOTES),
					'pv'        => (int)$v['pv'],
					'uv'        => (int)$v['uv'],
					'lasttime'  => $v['lasttime'] ? date('Y-m-d H:i:s',$v['lasttime']) : '',
					'url'       => $this->contentUrl($v['molds'],$v['tid'],$v['aid']),
				];
			}
			JsonReturn(['code'=>0,'msg'=>'success','count'=>$sum,'data'=>$data]);
		}

		$this->display($this->tplDir().'visit-content.html');
	}

	/* =========================================================
	 * 清理记录
	 * ========================================================= */
	public function clear(){
		if(!$this->hasTable('visit_log')){
			Error(JZLANG('数据表不存在，请到【插件管理】重新安装本插件！'),U('Plugins/index'));
		}
		if($this->frparam('ajax')){
			//按天数保留
			if($this->frparam('isgo')){
				$day = (int)$this->frparam('day');
				if($day<=0){
					JsonReturn(['code'=>1,'msg'=>JZLANG('请输入要保留的天数！')]);
				}
				$time = strtotime(date('Y-m-d'))-($day-1)*86400;
				$n1 = (int)M()->runSql("DELETE FROM `".DB_PREFIX."visit_log` WHERE addtime<".$time);
				M()->runSql("DELETE FROM `".DB_PREFIX."visit_day` WHERE day<".$time);
				JsonReturn(['code'=>0,'msg'=>JZLANG('清理完成，共删除').' '.$n1.' '.JZLANG('条浏览记录')]);
			}
			//清空全部
			if($this->frparam('clearall')){
				if($this->admin['isadmin']!=1){
					JsonReturn(['code'=>1,'msg'=>JZLANG('非超级管理员无法操作！')]);
				}
				M()->runSql("TRUNCATE TABLE `".DB_PREFIX."visit_log`");
				M()->runSql("TRUNCATE TABLE `".DB_PREFIX."visit_day`");
				JsonReturn(['code'=>0,'msg'=>JZLANG('已清空全部浏览记录！')]);
			}
		}
		$this->sum    = (int)M('visit_log')->getCount();
		$this->sumday = (int)M('visit_day')->getCount();
		$this->first  = M('visit_log')->find(null,'addtime asc',null,1);
		$this->display($this->tplDir().'visit-clear.html');
	}

	/* =========================================================
	 * 辅助方法
	 * ========================================================= */
	private function hasTable($table){
		if(!preg_match('/^[a-z0-9_]+$/i',$table)) return false;
		$list = M()->findSql("SHOW TABLES LIKE '".DB_PREFIX.$table."'");
		return $list ? true : false;
	}

	/* 取查询结果第一行第一个字段 */
	private function one($sql){
		$rows = M()->findSql($sql);
		if(!$rows) return 0;
		$row = array_shift($rows);
		return $row ? array_shift($row) : 0;
	}

	/* 独立访客 */
	private function uv($start,$end){
		return (int)$this->one("SELECT COUNT(DISTINCT ip) AS n FROM `".DB_PREFIX."visit_log`
			WHERE is_spider=0 AND addtime BETWEEN ".(int)$start." AND ".(int)$end);
	}

	/* 栏目及其所有下级ID */
	private function childrenIds($tid){
		$all = M('classtype')->findAll(null,'id asc',null,1000);
		$ids = [];
		$pids = [(int)$tid];
		$found = true;
		while($found && count($ids)<500){
			$found = false;
			foreach($all as $v){
				$id  = (int)$v['id'];
				$pid = (int)$v['pid'];
				if(in_array($pid,$pids) && !in_array($id,$ids) && $id!=(int)$tid){
					$ids[] = $id;
					$pids[] = $id;
					$found = true;
				}
			}
		}
		return $ids;
	}

	/* 内容前台地址 */
	private function contentUrl($molds,$tid,$aid){
		if(!$molds || !preg_match('/^[a-zA-Z0-9_]+$/',$molds)) return '';
		if(!M('molds')->find(['biaoshi'=>$molds])) return '';
		$detail = M($molds)->find(['id'=>$aid],null,'id,htmlurl,ownurl,target');
		if(!$detail) return '';
		$cls = M('classtype')->find(['id'=>$tid],null,'id,htmlurl');
		return gourl(
			['id'=>$detail['id'],'htmlurl'=>$detail['htmlurl'],'ownurl'=>$detail['ownurl'],'target'=>$detail['target']],
			$cls ? $cls['htmlurl'] : '',
			$molds
		);
	}
}
