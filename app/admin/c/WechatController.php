<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://www.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2024/06
// +----------------------------------------------------------------------


namespace app\admin\c;



class WechatController extends CommonController
{
    public function wxcaidan(){
        if($this->frparam('go')==1){
            $caidan = $this->frparam('caidan_name',2);
            $caidan_type = $this->frparam('caidan_type',2);
            $caidan_key = $this->frparam('caidan_key',2);
            $caidan_url = $this->frparam('caidan_url',2);
            $caidan_media_id = $this->frparam('caidan_media_id',2);
            $caidan_sort = $this->frparam('caidan_sort',2);
            
            //格式化数据
            asort($caidan_sort);
            $i = 0;
            foreach ($caidan_sort as $k=>$v){
                $new_caidan[$i] = $caidan[$k];
                $new_caidan_type[$i] = $caidan_type[$k];
                $new_caidan_key[$i] = $caidan_key[$k];
                $new_caidan_url[$i] = $caidan_url[$k];
                $new_caidan_media_id[$i] = $caidan_media_id[$k];
                //  $caidan_sort[$i] = $caidan_sort[$k];
                
                $i+=1;
                
                
            }
            //dump($new_caidan);exit;
            $caidan = $new_caidan;
            $caidan_type = $new_caidan_type;
            $caidan_key = $new_caidan_key;
            $caidan_url = $new_caidan_url;
            $caidan_media_id = $new_caidan_media_id;
            
            
            $datas = $this->frparam();
            foreach($caidan as $k=>$v){
                if($v!=''){
                    $data['button'][$k] = array(
                        "type"=>$caidan_type[$k],
                        "name"=>$v,
                    
                    );
                    if($caidan_type[$k]=='miniprogram'){
                        
                        $data['button'][$k]['appid'] =  $caidan_key[$k];
                        $data['button'][$k]['pagepath'] =  $caidan_url[$k];
                        $data['button'][$k]['url'] = 'https://mp.weixin.qq.com/';
                        
                    }else{
                        if($caidan_key[$k]!=''){
                            $data['button'][$k]['key'] = $caidan_key[$k];
                        }
                        if($caidan_url[$k]!=''){
                            $data['button'][$k]['url'] = $caidan_url[$k];
                        }
                        if($caidan_media_id[$k]!=''){
                            $data['button'][$k]['media_id'] = $caidan_media_id[$k];
                        }
                    }
                    
                    $ks = 'caidan_name_'.($k+1);
                    if(array_key_exists($ks,$datas)){
                        $sub_button_name = $this->frparam($ks,2);
                        $sub_button_type = $this->frparam('caidan_type_'.($k+1),2);
                        $sub_button_key = $this->frparam('caidan_key_'.($k+1),2);
                        $sub_button_url = $this->frparam('caidan_url_'.($k+1),2);
                        $sub_button_media_id = $this->frparam('caidan_media_id_'.($k+1),2);
                        $sub_button_sort = $this->frparam('caidan_sort_'.($k+1),2);
                        
                        
                        asort($sub_button_sort);
                        $i = 0;
                        $new_sub_button_name = [];
                        $new_sub_button_type = [];
                        $new_sub_button_url = [];
                        $new_sub_button_media_id = [];
                        foreach ($sub_button_sort as $xk=>$xx){
                            
                            $new_sub_button_name[$i] = $sub_button_name[$xk];
                            $new_sub_button_type[$i] = $sub_button_type[$xk];
                            $new_sub_button_key[$i] = $sub_button_key[$xk];
                            $new_sub_button_url[$i] = $sub_button_url[$xk];
                            $new_sub_button_media_id[$i] = $sub_button_media_id[$xk];
                            // echo $i;
                            $i+=1;
                            
                            
                        }
                        
                        
                        $sub_button_name = $new_sub_button_name;
                        $sub_button_type = $new_sub_button_type;
                        $sub_button_key = $new_sub_button_key;
                        $sub_button_url = $new_sub_button_url;
                        $sub_button_media_id = $new_sub_button_media_id;
                        
                        
                        
                        foreach($sub_button_name as $kk=>$vv){
                            $data['button'][$k]['sub_button'][$kk] = array(
                                "type"=>$sub_button_type[$kk],
                                "name"=>$vv
                            
                            );
                            if($sub_button_type[$kk]=='miniprogram'){
                                
                                $data['button'][$k]['sub_button'][$kk]['appid'] = $sub_button_key[$kk];
                                $data['button'][$k]['sub_button'][$kk]['pagepath'] = $sub_button_url[$kk];
                                $data['button'][$k]['sub_button'][$kk]['url'] = 'https://mp.weixin.qq.com/';
                            }else{
                                if($sub_button_key[$kk]!=''){
                                    $data['button'][$k]['sub_button'][$kk]['key'] = $sub_button_key[$kk];
                                }
                                if($sub_button_url[$kk]!=''){
                                    $data['button'][$k]['sub_button'][$kk]['url'] = $sub_button_url[$kk];
                                }
                                if($sub_button_media_id[$kk]!=''){
                                    $data['button'][$k]['sub_button'][$kk]['media_id'] = $sub_button_media_id[$kk];
                                }
                            }
                            
                            
                        }
                    }
                }
            }
            //dump($data);exit;
            
            $api = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken();
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            //echo $data;exit;
            $res = curl_http($api,$data,'post');
            $re = json_decode($res,1);
            if($re['errcode']==0){
                Success(JZLANG('更新成功！'),U('wxcaidan'));
            }else{
                exit($re['errcode'].':'.$re['errmsg']);
            }
            
            
        }
        $api  = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->getAccessToken();
        $weixincaidan_json = file_get_contents($api);
        if(strpos($weixincaidan_json,'errcode')!==false){
            //创建菜单
            $api = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken();
            //自定根据栏目创建
            
            $a1 = array(
                "type"=>"view",
                "name"=>JZLANG("网站首页"),
                "url"=>get_domain(),
                "sub_button"=>[
                
                ]
            
            );
            $a2 = array('type'=>"view","name"=>JZLANG('个人中心'),'url'=>get_domain().'/login.html',"sub_button"=>[]
            );
            $a3 =  array('type'=>"","name"=>JZLANG('测试1'),'url'=>'',"sub_button"=>[
                ['type'=>'view','name'=>JZLANG('测试2'),'url'=>'xxxx'],
                ['type'=>'view','name'=>JZLANG('测试3'),'url'=>'xxxx']]
            );
            
            $data['button'][] = $a1;
            //$data['button'][] = $a2;
            //$data['button'][] = $a3;
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            $res = curl_http($api,$data,'POST');
            //var_dump($res);
            $api  = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->getAccessToken();
            $weixincaidan_json = file_get_contents($api);
        }
        //echo $weixincaidan_json;exit;
        $lists = json_decode($weixincaidan_json,true);
        
        $buttons = [];
        foreach ($lists['menu']['button'] as $k=>$v){
            
            if(count($v['sub_button'])){
                
                foreach($v['sub_button'] as $kk=>$vv){
                    $v['sub_button'][$kk]['sort'] = $kk+1;
                }
                
            }
            $v['sort'] = $k+1;
            
            $buttons[] = $v;
            
            
        }
        
        $lists['menu']['button'] = $buttons;
        //	dump($buttons);exit;
        
        
        $this->lists = $lists;
        ///dump($this->lists);exit;
        $this->display('wechat-caidan');
        
    }
    public function sucai(){
        $api = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getAccessToken();
        $data['type'] = ($this->frparam('type',1))?($this->frparam('type',1)):'news';
        $data['offset'] = ($this->frparam('offset'))?($this->frparam('offset')):0;
        $data['count'] = 20;
        $this->data = $data;
        //var_dump($data);exit;
        $data =  json_encode($data,JSON_UNESCAPED_UNICODE);
        $res = curl_http($api,$data,'post');
        //var_dump($res);
        $lists = json_decode($res,true);
        //var_dump($lists);
        if(isset($lists['errcode'])){
            echo JZLANG('接口报错').'['.$res.']';exit;
        }
        $this->total_count = $lists['total_count'];
        $this->item_count = $lists['item_count'];
        $clists = array();
        if($this->data['type']=='news'){
            foreach($lists['item'] as $k=>$v){
                $clists[$k]=$v;
                $clists[$k]['count'] = count($v['content']['news_item']);
                $clists[$k]['news_item'] = $v['content']['news_item'];
            }
        }else{
            
            foreach($lists as $k=>$v){
                $clists[$k] = $v;
                $clists[$k]['count'] = count($v['item']);
            }
        }
        
        $this->lists = $clists;
        $this->display('wechat-sucai');
        
    }
    public function getAccessToken(){
        
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->webconf['wx_login_appid']."&secret=".$this->webconf['wx_login_appsecret'];
        $json = file_get_contents($url);
        //解析json
        //var_dump($json);
        $obj = json_decode($json,1);
        if(isset($obj['errcode'])){
            exit(JZLANG('微信配置错误！').$obj['errcode'].$obj['errmsg']);
        }
        return  $obj['access_token'];
    }
    
    
}