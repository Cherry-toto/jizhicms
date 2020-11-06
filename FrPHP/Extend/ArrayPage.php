<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/04/20
// +----------------------------------------------------------------------



	class ArrayPage {
		//分页数组
		public $array = '';
		//总条数
		public $sum = 0;
		//上一页
		public $prevpage = '';
		//下一页
		public $nextpage = '';
		//总页数
		public $allpage = 0;
		//每页条数
		public $limit = 10;
		//当前页码
		public $currentPage = 1;
		//间隔条数
		public $pv = 3;
		//系统分页链接
		public $url = '';
		//SQL
		public $sql = null;
		//排序
		public $order = null;
		//字段
		public $fields = null;
		//当前页数据
		public $datalist = array();
		//当前分页数组
		public $listpage = array();
		//分页标识
		public $pagetype = 'page';
		
		
		
		public function __construct($array){
			
			$this->datalist = $array;
			if(!is_array($array)){
				exit('不是数组！');
			}
			
			
			
		}
		
		public function query($param){
			$this->currentPage = !isset($_GET[$this->pagetype]) ? 1 : $_GET[$this->pagetype];
			if(is_array($param)){
				if(isset($param[$this->pagetype])){
					unset($param[$this->pagetype]);
				}
				$url = http_build_query($param);
			}else{
				$url = '';
			}
			
			$this->url = $url;
			return $this;
		}

		public function pageList($pv=5){
			$listpage = array(
				'home' => null,
				'prev' => null,
				'next' => null,
				'current' => null,
				'allpage' => 0,
				'current_num' => 0,
				'list' => null,
				'last' => null,
			);
	
	
			$this->pv = $pv;
			$list = '';
			$listpage['home'] = $this->url;	
			$listpage['current_num'] = $this->currentPage;	
			$listpage['allpage'] = $this->allpage;	

			if($this->url==''){
				$this->url.='?'.$this->pagetype.'=';
			}else{
				$this->url = '?'.$this->url.'&'.$this->pagetype.'=';
			}
			$listpage['current'] = $this->url.$this->currentPage;	
			$start = $this->currentPage-$this->pv;
			$start = $start<1 ? 1 : $start;
			$end = $this->currentPage+$this->pv;
			$end = $end>$this->allpage ? $this->allpage : $end;
			while($start<=$end){
				if($start==$this->currentPage){
					$list.='<li class="active" ><a >'.$this->currentPage.'</a></li>';
					$listpage['current'] = $this->url.$start;
					$listpage['current_num'] = $this->currentPage;
				}else{
					$list .= '<li><a href="'.$this->url.$start.'" data-page="'.$start.'">'.$start.'</a></li>';
				}
				$listpage['list'][] = array('url'=>$this->url.$start,'num'=>$start);
				$start++;
			}
			
			$prev = '<li><a href="'.$this->url.($this->currentPage-1).'" class="layui-laypage-prev" data-page="'.($this->currentPage-1).'"><em>&lt;</em></a></li>';
			
			$next = '<li><a href="'.$this->url.($this->currentPage+1).'" class="layui-laypage-next" data-page="'.($this->currentPage+1).'"><em>&gt;</em></a></li>';
			
			$all = '<li><a href="javascript:;" data-page="'.$this->currentPage.'">总共'.$this->currentPage.'/'.$this->allpage.'页 '.$this->sum.'条数据</a></li>';
			
			
			$last = '<li><a href="'.$this->url.$this->allpage.'" class="layui-laypage-prev" data-page="'.$this->allpage.'"><em>尾页</em></a></li>';
			
			$ext = '<div class="pagination"><ul>';
			$list = $list;
			
			if($this->currentPage!=1){
				$list = $prev.'<li><a href="'.$this->url.'1" data-page="1">首页</a></li>'.$list;
				$listpage['prev'] = $this->url.($this->currentPage-1);
			}
			if($this->currentPage<$this->allpage){
				$list .= $next;
				$listpage['next'] = $this->url.($this->currentPage+1);
			}
			
			if($this->allpage > $this->pv){
				$list .= $last;
			}
			$listpage['last'] = $this->url.$this->allpage;
			$list.=$all;
			$list = $ext.$list.'</ul></div>';
			$this->listpage = $listpage;
			$this->prevpage = $listpage['prev'];
			$this->nextpage = $listpage['next'];
			return $list;
			
		}
		
		public function setPage($config){
			
			if(isset($config['page'])){
				$this->currentPage = $config['page'];
			}
			if(isset($config['limit'])){
				$this->limit = $config['limit'];
			}
			
			return $this;
		}
		
		public function go(){
			
			
			$this->sum = count($this->datalist);
			$lists = array();
            $start = $this->limit * ($this->currentPage-1);
			
            $end = $this->limit * $this->currentPage;
			$i = 0;
			foreach($this->datalist as $v){
				if($end>$i && $start<=$i){
					$lists[]=$v;
				}
				$i++;
			}
			$allpage = ceil($this->sum/$this->limit);
			if($allpage==0){$allpage=1;}
			$this->allpage = $allpage;
			return $lists;
		}
	
		
		
		
		
		
		
		
		
		
		
	}

















?>