<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2019/09/06
// +----------------------------------------------------------------------


namespace Home\plugins;

use Home\c\CommonController;
use FrPHP\lib\Controller;
use FrPHP\Extend\Page;

class BanipController extends CommonController
{
	

	function banip(){
		//检测是否关闭插件或者卸载插件
		$w['filepath'] = 'banip';
		$w['isopen'] = 1;
		$res = M('plugins')->find($w);
		if($res){
			$ip = GetIP();
			$config = json_decode($res['config'],1);
			$ips = explode('||',$config['ips']);
			$tip = ($config['tip']=='') ? '抱歉，网站已关闭！':$config['tip'];
			if($ips){
				foreach($ips as $v){
					if(trim($v)!=''){
						//127.0.0.1  127.0.0.*
						if(strpos($v,'*')!==false){
							$d = explode('.',$ip);
							$dd = $d[0].'.'.$d[1].'.'.$d[2].'.*';
							if($dd==$v){
								exit($tip);
							}
						}else if($v==$ip){
							exit($tip);
						}
						
					}
					
				}
			}
		}
		
	}
	
}