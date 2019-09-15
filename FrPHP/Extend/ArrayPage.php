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
		
		
		
		public function __construct($array){
			
			$this->datalist = $array;
			if(!is_array($array)){
				exit('不是数组！');
			}
			
			$this->currentPage = !isset($_GET['page']) ? 1 : $_GET['page'];
			$param = $_REQUEST;
			if(isset($param['page'])){
				unset($param['page']);
			}
			$this->url = http_build_query($param);
			
			
		}
		
	
		
		/**
	
		格式化数组为pathinfo格式

		**/

		function array_pathInfo($url){
			$url = http_build_query(array_filter($url));
			$url = str_ireplace(array('&','='),'/',$url);
			return $url;
		}
		/*
		
		<div class="pagination">
		  <ul>
			<li><a href="#">Prev</a></li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li class="active" ><a href="#">5</a></li>
			<li><a href="#">Next</a></li>
		  </ul>
		</div>
		
		*/
		public function pageList($pv=3){
			/**
			分页样式
			
			左边框  $padding-left-f
			右边框  $padding-right-f
			
			上一页  $prev-f
			下一页  $next-f
			
			当前页  $current_f
			
			普通页  $public_f
			
			
			**/
			
	
	
			$this->pv = $pv;
			$list = '<style>li {
					float: left;
					list-style: none;
					padding: 10px;
				}
				a{text-decoration:none;}
				li.active {
					background: #f00;
					color: #fff;
				}
				</style>';
			if($this->url==''){
				$this->url.='?page=';
			}else{
				$this->url = '?'.$this->url.'&page=';
			}
			for($i=1;$i<=$this->allpage;$i++){
				if($this->allpage >= 2*$this->pv){
					//需要间隔
					$start = $this->currentPage+$this->pv;
					$end = $this->currentPage-$this->pv;
					if($i>=$end && $i<=$start){
						if($i==$this->currentPage){
				
						$list.='<li class="active" ><a >'.$this->currentPage.'</a></li>';
						}else{
							
							$list .= '<li><a href="'.$this->url.$i.'" data-page="'.$i.'">'.$i.'</a></li>';
						}
					}
				}else{
					if($i==$this->currentPage){
					
					$list.='<li class="active" ><a >'.$this->currentPage.'</a></li>';
					}else{
						
						$list .= '<li><a href="'.$this->url.$i.'" data-page="'.$i.'">'.$i.'</a></li>';
					}
				}
			}
			
			
			$prev = '<li><a href="'.$this->url.($this->currentPage-1).'" class="layui-laypage-prev" data-page="'.($this->currentPage-1).'"><em>&lt;</em></a></li>';
			
			$next = '<li><a href="'.$this->url.($this->currentPage+1).'" class="layui-laypage-next" data-page="'.($this->currentPage+1).'"><em>&gt;</em></a></li>';
			
			$all = '<li><a href="javascript:;" data-page="'.$this->currentPage.'">总共'.$this->currentPage.'/'.$this->allpage.'页 '.$this->sum.'条数据</a></li>';
			
			
			$last = '<li><a href="'.$this->url.$this->allpage.'" class="layui-laypage-prev" data-page="'.$this->allpage.'"><em>尾页</em></a></li>';
			
			$ext = '<div class="pagination"><ul>';
			$list = $list;
			
			if($this->currentPage!=1){
				$list = $prev.'<li><a href="'.$this->url.'1" data-page="1">首页</a></li>'.$list;
			}
			
			if($this->currentPage<$this->allpage){
				$list .= $next;
			}
			
			if($this->allpage > $this->pv){
				$list .= $last;
			}
			$list.=$all;
			$list = $ext.$list.'</ul></div>';
			
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
			foreach($this->datalist as $k=>$v){
				if($end>$k && $start<=$k){
					$lists[]=$v;
				}
			}
			
			
			$allpage = ceil($this->sum/$this->limit);
			if($allpage==0){$allpage=1;}
			$this->allpage = $allpage;
			return $lists;
		}
	
		
		
		
		
		
		
		
		
		
		
	}

















?>