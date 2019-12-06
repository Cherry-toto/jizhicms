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


namespace FrPHP\Extend;

	class Page {
		//分页表
		public $table = '';
		//总条数
		public $sum = 0;
		//总页数
		public $allpage = 0;
		//上一页
		public $prevpage = '';
		//下一页
		public $nextpage = '';
		//每页条数
		public $limit = 10;
		//分页从第几开始
		public $limit_t = 0;
		//当前页码
		public $currentPage = 1;
		//间隔条数
		public $pv = 3;
		//系统分页链接
		public $url = '';
		//分页分隔符
		public $sep = '/page/';
		//SQL
		public $sql = null;
		//排序
		public $order = null;
		//字段
		public $fields = null;
		//当前分页数据
		public $datalist = array();
		//当前分页数组
		public $listpage = array();
		//分页url设置
		public $typeurl = '';
		//是否需要后缀File_TXT
		public $file_ext = File_TXT;
		
		
		
		public function __construct($table){
			
			$this->table = $table;
			if(!$table){
				Error_msg('缺少表对象！');
			}
		}
		
		
		public function getUrl(){
			$request_uri = $_SERVER["REQUEST_URI"];    
            if(strpos($request_uri,APP_URL)!==false){
				//后台
				$this->file_ext = '';
				$this->sep = '?page=';
				if(isset($_GET['page'])){
					unset($_GET['page']);
				}
				$url = get_domain().APP_URL.'/'.APP_CONTROLLER.'/'.APP_ACTION;
				if(count($_GET)>0){
					$this->sep = '&page=';
					$url = get_domain().APP_URL.'/'.APP_CONTROLLER.'/'.APP_ACTION.'?'.http_build_query($_GET);
				}
			}else{
				//$url = str_ireplace('.html','',$request_uri);
				//$position = strpos($url, '-');
				//$url = $position === false ? $url : substr($url, 0, $position);
				
				switch($this->typeurl){
					case 'screen':
						//$url = str_ireplace('.html','',$request_uri);
					$url = get_domain().'/screen-'.$_GET['molds'].'-'.$_GET['tid'].'-'.$_GET['jz_screen']; 
						if(strpos($url,'page')!==false){
							$urls = explode('-page-',$url);
							$url = $urls[0];
						}
					break;
					case 'tpl':
						$this->file_ext = '';
						$url = str_ireplace('.html','',$request_uri);
						if(strpos($url,'?')!==false){
							$urls = explode('?',$url);
							$url = $urls[0];
						}
						
					break;
					case 'search':
						$param = $_REQUEST;
						if(isset($param['page'])){
							unset($param['page']);
						}
						
						$url = get_domain().'/search?'.http_build_query($param);
						
					break;
					default:
						$url = str_ireplace('.html','',$request_uri);
						
					
					break;
					
				}
				
				
			}
			return $url;
            
		}
		
		public function pageList($pv=3,$sep=false){
			/**
				首页url  		home
				上一页url		prev
				下一页url       next
				当前页url       current
				总页数  	    allpage
				当前页码		current_num
				普通页码组      list
				最后一页url		last
			
			
			**/
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
			$this->sep = ($sep==false) ? ($this->sep) : $sep;
			$url = $this->getUrl();
			if(strpos($url,$this->sep)!==false){
				$urls = explode($this->sep,$url);
				$num = array_pop($urls);
				if(is_numeric($num)){
					  $url = implode($this->sep,$urls);
				}

			}
			$this->url = $url;
			$list = '';
			$file_ext = $this->file_ext;
			$listpage['home'] = $this->url.$file_ext;
			for($i=1;$i<=$this->allpage;$i++){
				if($this->allpage >= 2*$this->pv){
					//需要间隔
					$start = $this->currentPage+$this->pv;
					$end = $this->currentPage-$this->pv;
					if($i>=$end && $i<=$start){
						if($i==$this->currentPage){
							$list.='<li class="active" ><a >'.$this->currentPage.'</a></li>';
							$listpage['current'] = $this->url.$this->sep.$i.$file_ext;
							$listpage['current_num'] = $this->currentPage;
						}else{
							$list .= '<li><a href="'.$this->url.$this->sep.$i.$file_ext.'" data-page="'.$i.'">'.$i.'</a></li>';
							
						}
						
						$listpage['list'][] = array('url'=>$this->url.$this->sep.$i.$file_ext,'num'=>$i);
					}
				}else{
					if($i==$this->currentPage){
						$list.='<li class="active" ><a >'.$this->currentPage.'</a></li>';
						$listpage['current'] = $this->url.$this->sep.$i.$file_ext;
						$listpage['current_num'] = $this->currentPage;
					}else{
						$list .= '<li><a href="'.$this->url.$this->sep.$i.$file_ext.'" data-page="'.$i.'">'.$i.'</a></li>';
						
					}
					$listpage['list'][] = array('url'=>$this->url.$this->sep.$i.$file_ext,'num'=>$i);
				}
			}
			$listpage['allpage'] = $this->allpage;
			
			$prev = '<li><a href="'.$this->url.$this->sep.($this->currentPage-1).$file_ext.'" class="layui-laypage-prev" data-page="'.($this->currentPage-1).'"><em>&lt;</em></a></li>';
			
			if($this->currentPage!=1){
				$this->prevpage = $this->url.$this->sep.($this->currentPage-1).$file_ext;
			}
			$next = '<li><a href="'.$this->url.$this->sep.($this->currentPage+1).$file_ext.'" class="layui-laypage-next" data-page="'.($this->currentPage+1).'"><em>&gt;</em></a></li>';
			
			if($this->currentPage != $this->allpage && $this->allpage>1){
			$this->nextpage = $this->url.$this->sep.($this->currentPage+1).$file_ext;	
			}
			
			$all = '<li><a href="javascript:;" data-page="'.$this->currentPage.'">总共'.$this->currentPage.'/'.$this->allpage.'</a></li>';
			
			$last = '<li><a href="'.$this->url.$this->sep.$this->allpage.$file_ext.'" class="layui-laypage-prev" data-page="'.$this->allpage.'"><em>尾页</em></a></li>';
			
			$ext = '<div class="pagination"><ul>';
			$list = $all.$list;
			
			if($this->currentPage!=1){
				$list = $prev.$list;
				$listpage['prev'] = $this->prevpage;
			}
			
			if($this->currentPage<$this->allpage){
				$list .= $next;
				$listpage['next'] = $this->nextpage;
			}
			
			if($this->allpage > $this->pv){
				$list .= $last;
				$listpage['last'] = $this->url.$this->sep.$this->allpage.$file_ext;
			}
			
			$list = $ext.$list.'</ul></div>';
			$this->listpage = $listpage;
			return $list;
			
		}
		
		public function where($sql=null){
			$this->sql = $sql;
			return $this;
		}
		public function orderby($orders=null){
			$this->order = $orders;
			return $this;
		}
		public function limit($limit=null){
			if($limit==null){
				$this->limit = $this->limit;
			}else{
				if(strpos($limit,',')!==false){
					$limit_t = explode(',',$limit);
					$this->limit = (int)$limit_t[1];
					$this->limit_t = (int)$limit_t[0];
				}else{
					$this->limit = $limit;
				}

			}

			return $this;
		}
		public function fields($fields=null){
			$this->fields = $fields;
			return $this;
		}
		public function page($p=1){
			$this->currentPage = (int)$p;
			return $this;
		}
		
		
		public function setPage($config){
			if(isset($config['order'])){
				$this->order = $config['order'];
			}
			if(isset($config['fields'])){
				$this->fields = $config['fields'];
			}
			if(isset($config['limit'])){
				$this->limit = $config['limit'];
			}
			if(isset($config['page'])){
				$this->currentPage = $config['page'];
			}
			
			return $this;
		}
		
		public function go(){
			if($this->currentPage!=1){
				$limitsql = (($this->limit*($this->currentPage-1)) - ($this->limit_t)).','.$this->limit;
				//1-0:1  2-2:3
			}else{
				if($this->limit_t!=0){
					$limitsql = $this->limit_t.','.$this->limit;
				}else{
					$limitsql = $this->limit;
				}
				
			}
			
			$this->datalist = M($this->table)->findAll($this->sql,$this->order,$this->fields,$limitsql);
			
			$this->sum = M($this->table)->getCount($this->sql);
			$this->limit = $this->limit;

			$allpage = ceil($this->sum/$this->limit);
			if($allpage==0){$allpage=1;}
			$this->allpage = $allpage;
			return $this->datalist;
		}
		
		//一步到位
		public function goPage($sql=null,$order=null,$fields=null,$limit=10){
			$this->sql = $sql;
			$this->order = $order;
			$this->fields = $fields;
			if(strpos($limit,',')!==false){
				$limit_t = explode(',',$limit);
				$this->limit = (int)$limit_t[1];
				$this->limit_t = (int)$limit_t[0];
			}else{
				$this->limit = $limit;
			}
			
			if($this->currentPage!=1){
				$limitsql = (($this->limit*($this->currentPage-1)) - ($this->limit_t)).','.$this->limit;
				//1-0:1  2-2:3
			}else{
				if($this->limit_t!=0){
					$limitsql = $this->limit_t.','.$this->limit;
				}else{
					$limitsql = $this->limit;
				}
				
			}
			
			$this->datalist = M($this->table)->findAll($this->sql,$this->order,$this->fields,$limitsql);
			$this->sum = M($this->table)->getCount($sql);
			$this->limit = $limit;
			$this->allpage = ceil($this->sum/$this->limit);
			return $this->datalist;
		}
		
		
		
		
		
		
		
		
		
		
	}

















?>