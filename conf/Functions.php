<?php

// +----------------------------------------------------------------------
// | FrPHP { a friendly PHP Framework } 
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2099 http://frphp.jizhicms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 留恋风 <2581047041@qq.com>
// +----------------------------------------------------------------------
// | Date：2018/02
// +----------------------------------------------------------------------



/*****************
 * 项目公共函数 *
 *****************/

include(APP_PATH.'conf/FunctionsExt.php');
// 获取系统配置
if(!function_exists('webConf')){
    function webConf($str=null){
        //v1.3 取消文件存储
        //$web_config = include(APP_PATH.'Conf/webconf.php');
        $webconfig = getCache('webconfig');
        if(!$webconfig){
            $wcf = M('sysconfig')->findAll();
            $webconfig = array();
            foreach($wcf as $k=>$v){
                if($v['field']=='web_js' || $v['field']=='ueditor_config'){
                    $v['data'] = html_decode($v['data']);
                }
                $webconfig[$v['field']] = $v['data'];
            }
            setCache('webconfig',$webconfig);
        }


        if($str!=null){
            if(!array_key_exists($str,$webconfig)){
                return false;
            }
            return $webconfig[$str];
        }else{
            return $webconfig;
        }
    }
}
// 获取系统扩展配置--历史方法暂无作用
if(!function_exists('get_custom')) {
    function get_custom($str = null)
    {
        return webConf($str);
    }
}
// 获取前台模板
if(!function_exists('get_template')) {
    function get_template()
    {
        $hometpl = isMobile() ? (isWeixin() ? getCache('wxhometpl') : getCache('mobilehometpl')) : getCache('hometpl');
        if ($hometpl) {
            return $hometpl;
        }
        $webconf = webConf();
        $isgo = true;
        //检测是否安装插件
        $res = M('plugins')->find(['filepath' => 'website', 'isopen' => 1]);
        if ($res && $res['config']) {
            $website = $_SERVER['HTTP_HOST'];
            $config = json_decode($res['config'], 1);
            $pc = $webconf['pc_template'];
            $wap = $webconf['wap_template'];
            $wechat = $webconf['weixin_template'];
            foreach ($config as $v) {
                if ($v['website'] == $website) {
                    $isgo = false;
                    $v['model'] = (int)$v['model'];
                    switch ($v['model']) {
                        case 0:
                            $pc = $wap = $wechat = $v['tpl'];
                            break;
                        case 1:
                            $pc = $v['tpl'];
                            break;
                        case 2:
                            $wap = $v['tpl'];
                            break;
                        case 3:
                            $wechat = $v['tpl'];
                            break;
                    }
                }
            }
            //当前端口检测
            if ($webconf['iswap'] == 1 && isMobile()) {
                $template = $wap;
                //wap
                if (isWeixin()) {
                    //wechat
                    $template = $wechat;
                }


            } else {
                //pc
                $template = $pc;
            }


            if ($template == '') {
                //全局
                $isgo = true;//直接跳转下面进行默认设置
            }

        }
        if ($isgo) {
            if ($webconf['iswap'] == 1 && isMobile()) {
                if (isWeixin()) {
                    $template = ($webconf['weixin_template'] != '') ? $webconf['weixin_template'] : $webconf['wap_template'];
                } else {
                    $template = $webconf['wap_template'];
                }

            } else {
                $template = $webconf['pc_template'];
            }

        }
        if (isMobile()) {
            if (isWeixin()) {
                setCache('wxhometpl', $template);
            } else {
                setCache('mobilehometpl', $template);
            }
        } else {
            setCache('hometpl', $template);
        }

        return $template;
    }
}
// 发送http请求
if(!function_exists('curl_http')) {
    function curl_http($url, $data = null, $method = 'GET')
    {
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if ($method != 'GET') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if ($data != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //结果是否显示出来，1不显示，0显示
        //判断是否https
        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
            curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
        }


        $data = curl_exec($ch);
        curl_close($ch);
        if ($data === FALSE) {
            $data = "curl Error:" . curl_error($ch);
        }
        return $data;
    }
}
// 分页处理
if(!function_exists('get_all_page')) {
    function get_all_page($url, $start = 5, $end = 0, $match = '{$}')
    {
        $urls = array();
        if ($end == 0) {
            for ($i = 1; $i <= $start; $i++) {
                $urls[] = str_ireplace($match, $i, $url);
            }
        } else {

            for ($i = $start; $i <= $end; $i++) {
                $urls[] = str_ireplace($match, $i, $url);
            }
        }

        return $urls;

    }
}
// 获取管理员信息
if(!function_exists('adminInfo')) {
    function adminInfo($id, $str = null)
    {
        $user = M('level')->find('id=' . $id);
        if ($str != null) {
            return $user[$str];
        }
        return $user;

    }
}
// 检查是否有路由权限
if(!function_exists('checkAction')) {
    function checkAction($action)
    {
        if (!isset($_SESSION['admin'])) {
            Error('登录超时,请重新登录！');
        }
        $action = ucfirst($action);
        $paction = $_SESSION['admin']['paction'];
        if ($_SESSION['admin']['isadmin'] != 1) {

            if (strpos($action, '/') !== false) {
                if (strpos($paction, ',' . $action . ',') !== false) {
                    return true;
                } else {
                    $d = explode('/', $action);
                    if (strpos($paction, ',' . $d[0] . ',') !== false) {
                        return true;
                    } else {
                        return false;
                    }

                }
            } else {
                if (strpos($paction, ',' . $action . ',') !== false) {
                    return true;
                } else {
                    return false;
                }
            }


        } else {
            return true;
        }


    }
}
/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */
if(!function_exists('getTree')) {
    function getTree($array, $pid = 0, $level = 0)
    {

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        if ($level == 0) {
            $list = [];
        }
        foreach ($array as $key => $value) {

            //判断是否有下级---存在多次处理的bug v1.3已解决
            //$value['haschild'] = haschild($array,$value['id']);
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid) {
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                getTree($array, $value['id'], $level + 1);

            }
        }
        return $list;
    }
}
// 栏目是否有下级
if(!function_exists('haschild')) {
    function haschild($array, $pid)
    {
        $n = false;
        foreach ($array as $v) {
            if ($v['pid'] == $pid) {
                $n = true;
                break;
            }
        }
        return $n;
    }
}
// 栏目格式化树状结构
if(!function_exists('show_tree')) {
    function show_tree($array)
    {
        foreach ($array as $value) {
            echo str_repeat('--', $value['level']), $value['classname'] . '<br />';
        }
    }
}
// 子栏目处理
if(!function_exists('set_class_haschild')) {
    function set_class_haschild($classtype = null)
    {

        $newarray = [];//组建新栏目数组
        foreach ($classtype as $k => $v) {

            $v['haschild'] = false;//默认所有都没有下级
            $newarray[$v['id']] = $v;


        }
        foreach ($newarray as $k => $v) {
            if ($v['pid'] != 0) {
                //找到有上级的栏目，那么上级栏目就有下级了。。。。
                $newarray[$v['pid']]['haschild'] = true;
            }

        }
        return $newarray;

    }
}
// 获取栏目树状结构
if(!function_exists('get_classtype_tree')) {
    function get_classtype_tree()
    {

        $classtypetree = getCache('classtypetree');
        if (!$classtypetree) {

            $classtype = M('classtype')->findAll(['isclose' => 0], 'orders desc');
            $classtype = set_class_haschild($classtype);
            $classtypetree = getTree($classtype);
            setCache('classtypetree', $classtypetree);
        }

        return $classtypetree;

    }
}
// 获取栏目数据
if(!function_exists('classTypeData')) {
    function classTypeData()
    {
        $res = getCache('classtype');
        $cache_time = (int)webConf('cache_time');
        if (!$res || !$cache_time) {
            $classtypedata = get_classtype_tree();
            $d = array();
            $www = webConf('domain') ? webConf('domain') : get_domain();
            $htmlpath = webConf('pc_html');
            $htmlpath = ($htmlpath == '' || $htmlpath == '/') ? '' : '/' . $htmlpath;
            foreach ($classtypedata as $k => $v) {
                $d[$v['id']] = $v;
                if ($v['gourl'] != '') {
                    $d[$v['id']]['url'] = $v['gourl'];
                } else {
                    $file_txt = File_TXT_HIDE ? '' : '.html';
                    if ($file_txt == '') {
                        $file_txt = CLASS_HIDE_SLASH ? $file_txt : $file_txt . '/';
                    }
                    $d[$v['id']]['url'] = $www . $htmlpath . '/' . $v['htmlurl'] . $file_txt;
                }

            }

            setCache('classtype', $d, $cache_time);
            return $d;
        }
        return $res;


    }
}
// 获取手机端栏目数据
if(!function_exists('classTypeDataMobile')) {
    function classTypeDataMobile()
    {
        $res = getCache('mobileclasstype');
        $cache_time = (int)webConf('cache_time');
        if (!$res || !$cache_time) {
            $classtypedata = get_classtype_tree();
            $d = array();
            $www = webConf('domain') ? webConf('domain') : get_domain();
            $htmlpath = webConf('mobile_html');
            $htmlpath = ($htmlpath == '' || $htmlpath == '/') ? '' : '/' . $htmlpath;
            foreach ($classtypedata as $k => $v) {
                $d[$v['id']] = $v;
                if ($v['gourl'] != '') {
                    $d[$v['id']]['url'] = $v['gourl'];
                } else {
                    $file_txt = File_TXT_HIDE ? '' : '.html';
                    if ($file_txt == '') {
                        $file_txt = CLASS_HIDE_SLASH ? $file_txt : $file_txt . '/';
                    }
                    $d[$v['id']]['url'] = $www . $htmlpath . '/' . $v['htmlurl'] . $file_txt;
                }

            }

            setCache('mobileclasstype', $d, $cache_time);
            return $d;
        }
        return $res;


    }
}
// 检查两个栏目id是否为子栏目
if(!function_exists('checkClass')) {
    function checkClass($pid, $tid){

        $class = M('classtype')->find(array('id' => $pid));

        if ($class['pid'] == $tid) {
            return true;
        }


        if ($class['pid'] == 0) {
            return false;
        } else {
            checkClass($class['pid'], $tid);
        }
    }
}
//获取栏目的所有下级
/*
@param type				当前栏目数组
@param classtype  已被getTree格式化数组
@param code       获取内容类型
	1输出所有数组
	2输出直系子类id
	3输出全系子类ids
	4输出直系子类数组children
	5输出全系子类数组childrens
*/
if(!function_exists('get_children')) {
    function get_children($type, $classtype = null, $code = 1)
    {
        if ($type == null || $classtype == null) {
            Error_msg('参数错误！');
        }

        $children = array();
        $childrens = array();
        $alldata = array();
        $go = false;
        $children_id = [];
        $children_ids[] = $type['id'];

        foreach ($classtype as $v) {
            if ($v['id'] == $type['id']) {
                $go = true;
                continue;
            }
            if ($v['level'] == $type['level']) {
                $go = false;
                continue;
            }
            //所有下级
            if ($v['level'] >= $type['level'] && $go) {
                $childrens[] = $v;
                $children_ids[] = $v['id'];

            }
            //直系下级
            if ($v['pid'] == $type['id']) {
                $children[] = $v;
                $children_id[] = $v['id'];
            }


        }
        //直系属性
        $alldata['id'] = $children_id;
        $alldata['list'] = $children;
        //全系属性
        $alldata['ids'] = $children_ids;
        $alldata['lists'] = $childrens;
        switch ($code) {
            case 1:
                return $alldata;
                break;
            case 2:
                return $children_id;
                break;
            case 3:
                return $children_ids;
                break;
            case 4:
                return $children;
                break;
            case 5:
                return $childrens;
                break;
        }


    }
}
// 获取一个条数据
if(!function_exists('get_info_table')) {
    function get_info_table($table, $where = null, $str = null)
    {

        $data = M($table)->find($where, null, $str);
        if ($str != null) {
            return $data[$str];
        }
        return $data;

    }
}
// 获取多条数据
if(!function_exists('get_all_info_table')) {
    function get_all_info_table($table, $where = null, $order = null, $limit = null, $field = null)
    {
        $data = M($table)->findAll($where, $order, $field, $limit);
        return $data;
    }
}
//后台方法-获取表单提交的扩展字段的内容
/**
@param data   表单提交的内容
@param molds  模块标识
@param isadmin是否后台
**/
if(!function_exists('get_fields_data')) {
    function get_fields_data($data, $molds, $isadmin = 1)
    {
        if ($isadmin) {
            $fields = M('fields')->findAll(['molds' => $molds, 'isadmin' => 1], 'orders desc,id asc');
        } else {
            //前台需要判断是否前台显示
            $fields = M('fields')->findAll(['molds' => $molds, 'isshow' => 1, 'ishome' => 1], 'orders desc,id asc');
        }
        $newdata = [];
        foreach ($fields as $v) {
            if (array_key_exists($v['field'], $data)) {
                switch ($v['fieldtype']) {
                    case 1:
                    case 2:
                    case 5:
                    case 7:
                    case 9:
                    case 12:
                    case 18:
                    case 21:
                        $data[$v['field']] = format_param($data[$v['field']], 1);
                        break;
                    case 11:
                        $data[$v['field']] = strtotime(format_param($data[$v['field']], 1));
                        break;
                    case 3:
                        if ($isadmin) {
                            $text = $data[$v['field']];
                            $text = remote_data_local($text, $data['tid'], $data['molds']);
                            $data[$v['field']] = format_param($text, 4);
                        }else{
                            $text = $data[$v['field']];
                            $text = remote_data_local($text, $data['tid'], $data['molds']);
                            $data[$v['field']] = format_param($text, 6);
                        }
                        
                        break;
                    case 4:
                    case 13:
                    case 17:
                        $data[$v['field']] = format_param($data[$v['field']]);
                        break;
                    case 14:
                        $data[$v['field']] = format_param($data[$v['field']], 3);
                        break;
                    case 8:
                        $r = implode(',', format_param($data[$v['field']], 2));
                        if ($r) {
                            $r = ',' . $r . ',';
                        }
                        $data[$v['field']] = $r;
                        break;
                    case 16:
                    case 20:
                        if(is_array($data[$v['field']])){
                            $data[$v['field']] = $data[$v['field']] ? ',' . implode(',',format_param($data[$v['field']], 2)) . ',' : '';
                        }else{
                            $data[$v['field']] = $data[$v['field']] ? ',' . format_param($data[$v['field']], 1) . ',' : '';
                        }
                        break;
                    case 15:
                        $r = implode('||', format_param($data[$v['field']], 2));
                        $data[$v['field']] = $r;
                        break;
                    case 19:
                        $data[$v['field']] = format_param($data[$v['field']], 1);
                        $data[$v['field']] = $data[$v['field']] ? ',' . $data[$v['field']] . ',' : '';
                        break;

                }
                $newdata[$v['field']] = $data[$v['field']];
            } else if (array_key_exists($v['field'] . '_urls', $data)) {
                switch ($v['fieldtype']) {
                    case 6:
                    case 10:
                        if (array_key_exists($v['field'] . '_des', $data)) {
                            $pics = format_param($data[$v['field'] . '_urls'], 2);
                            $pics_des = format_param($data[$v['field'] . '_des'], 2);
                            foreach ($pics as $k => $vv) {
                                if ($pics_des[$k]) {
                                    $pics[$k] = $vv . '|' . $pics_des[$k];
                                }
                            }
                            $data[$v['field']] = implode('||', $pics);

                        } else {
                            $data[$v['field']] = implode('||', format_param($data[$v['field'] . '_urls'], 2));
                        }

                        break;
                }
                $newdata[$v['field']] = $data[$v['field']];
            } else {
                $data[$v['field']] = '';

            }
            if (isset($data['id'])) {
                $data['id'] = format_param($data['id']);
            }

        }

        if ($isadmin) {
            return $data;
        } else {
            //前台只返回允许的字段
            return $newdata;
        }


    }
}

// 新增字段-后台列表搜索获取
if(!function_exists('molds_search')) {
    function molds_search($molds = null, $data = null)
    {
        if ($molds == null) {
            Error('缺少模块标识！');
        }
        $lists = M('Fields')->findAll(array('molds' => $molds, 'issearch' => 1), 'orders desc,id asc');
        $fields_search = '';
        $fields_search_check = array();
        foreach ($lists as $v) {
            $data[$v['field']] = array_key_exists($v['field'], $data) ? $data[$v['field']] : '';
            switch ($v['fieldtype']) {
                case 1:
                case 2:
                case 3:
                case 5:
                case 6:
                case 9:
                case 10:
                case 14:
                case 15:
                    $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $v['fieldname'] . '" autocomplete="off" class="layui-input">';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = "  " . $v['field'] . " like '%" . format_param($data[$v['field']], 1) . "%'";
                        }

                    }
                    break;
                case 4:
                    $fields_search .= '<input type="number" name="' . $v['field'] . '" value="' . format_param($data[$v['field']]) . '" placeholder="请输入' . $v['fieldname'] . '" autocomplete="off" class="layui-input">';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = "  " . $v['field'] . " = '" . format_param($data[$v['field']], 1) . "'";
                        }

                    }
                    break;
                case 7:
                case 12:
                    $fields_search .= '<div class="layui-input-inline">
		  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
		  <option value="">请选择' . $v['fieldname'] . '</option>';
                    foreach (explode(',', $v['body']) as $vv) {
                        $s = explode('=', $vv);
                        $fields_search .= '<option ';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) == $s[1]) {
                                $fields_search .= 'selected="selected"';
                            }
                        }
                        $fields_search .= 'value="' . $s[1] . '">' . $s[0] . '</option>';
                    }

                    $fields_search .= '</select>
		 </div>';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = "  " . $v['field'] . " = '" . format_param($data[$v['field']], 1) . "'";
                        }

                    }
                    break;
                case 8:
                    $fields_search .= '<div class="layui-input-inline">
		  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
		  <option value="">请选择' . $v['fieldname'] . '</option>';
                    foreach (explode(',', $v['body']) as $vv) {
                        $s = explode('=', $vv);
                        $fields_search .= '<option ';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) == $s[1]) {
                                $fields_search .= 'selected="selected"';
                            }
                        }
                        $fields_search .= 'value="' . $s[1] . '">' . $s[0] . '</option>';
                    }

                    $fields_search .= '</select>
		 </div>';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = " " . $v['field'] . " like '%," . format_param($data[$v['field']], 1) . ",%'";
                        }

                    }

                    break;
                case 11:
                    $laydate = ($data[$v['field']] == '' || $data[$v['field']] == 0) ? '' : date('Y-m-d', strtotime($data[$v['field']]));
                    $laytime = ($data[$v['field']] == '' || $data[$v['field']] == 0) ? 0 : strtotime($laydate);
                    $fields_search .= '<input name="' . $v['field'] . '" value="' . $laydate . '" placeholder="请选择' . $v['fieldname'] . '" id="laydate_' . $v['field'] . '" autocomplete="off" class="layui-input"><script>
layui.use("laydate", function(){
var laydate = layui.laydate;
laydate.render({elem: "#laydate_' . $v['field'] . '" });});</script>';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']]) != 0) {
                            $fields_search_check[] = "  (" . $v['field'] . " >= " . $laytime . " and " . $v['field'] . " < " . ($laytime + 86400) . ") ";
                        }

                    }
                    break;
                case 13:
                    $body = explode(',', $v['body']);
                    $moldsdata = M('molds')->find(['id' => $body[0]]);
                    $num = M($moldsdata['biaoshi'])->getCount();

                    if ($num > 500) {
                        $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $moldsdata['name'] . 'ID" autocomplete="off" class="layui-input">';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) != '') {
                                $fields_search_check[] = "  " . $v['field'] . " like '%" . format_param($data[$v['field']], 1) . "%'";
                            }

                        }
                    } else {
                        $datalist = M($moldsdata['biaoshi'])->findAll(null, 'id desc', 'id,' . $body[1]);
                        $fields_search .= '<div class="layui-input-inline">
			  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
			  <option value="">请选择' . $v['fieldname'] . '</option>';
                        foreach ($datalist as $vv) {
                            $fields_search .= '<option ';
                            if (array_key_exists($v['field'], $data)) {
                                if (format_param($data[$v['field']]) == $vv['id']) {
                                    $fields_search .= 'selected="selected"';
                                }
                            }
                            $fields_search .= 'value="' . $vv['id'] . '">' . $vv[$body[1]] . '</option>';
                        }

                        $fields_search .= '</select>
			 </div>';
                    }

                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = " " . $v['field'] . " =" . format_param($data[$v['field']]) . " ";
                        }

                    }

                    break;
                case 16:
                    $body = explode(',', $v['body']);
                    $moldsdata = M('molds')->find(['id' => $body[0]]);
                    $num = M($moldsdata['biaoshi'])->getCount();

                    if ($num > 500) {
                        $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $moldsdata['name'] . 'ID" autocomplete="off" class="layui-input">';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) != '') {
                                $fields_search_check[] = "  " . $v['field'] . " like '%," . format_param($data[$v['field']], 1) . ",%'";
                            }

                        }
                    } else {
                        $datalist = M($moldsdata['biaoshi'])->findAll(['isshow' => 1], 'id desc', 'id,' . $body[1]);
                        $fields_search .= '<div class="layui-input-inline">
			  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
			  <option value="">请选择' . $v['fieldname'] . '</option>';
                        $d = format_param($data[$v['field']]);
                        foreach ($datalist as $vv) {
                            $fields_search .= '<option ';
                            if (array_key_exists($v['field'], $data)) {
                                if ($d == $vv['id']) {
                                    $fields_search .= 'selected="selected"';
                                }
                            }
                            $fields_search .= 'value="' . $vv['id'] . '">' . $vv[$body[1]] . '</option>';
                        }

                        $fields_search .= '</select>
			 </div>';
                    }

                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = " " . $v['field'] . " like '%," . format_param($data[$v['field']]) . ",%' ";
                        }

                    }
                    break;
                case 17:
                    $classtypedata = getclasstypedata(classTypeData());
                    $classtypetree = get_classtype_tree();
                    $fields_search .= '<div class="layui-input-inline"><select name="tid" lay-filter="tid" lay-search="" class="layui-inline autosubmit">
				  <option value="">请选择栏目</option>';
                    if ($_SESSION['admin']['isadmin'] != 1) {
                        $tids = $_SESSION['admin']['tids'];
                        foreach ($classtypedata as $k => $vs) {
                            if ($vs['pid'] == 0) {
                                if (strpos($_SESSION['admin']['tids'], ',' . $vs['id'] . ',') !== false) {
                                    $children = get_children($vs, $classtypetree, 5);
                                    foreach ($children as $vv) {
                                        if (strpos($_SESSION['admin']['tids'], ',' . $vv['id'] . ',') === false) {
                                            $tids .= $tids ? $vv['id'] . ',' : ',' . $vv['id'] . ',';
                                        }
                                    }
                                }
                            }

                        }

                    } else {
                        $tids = '0';
                    }
                    $admin = $_SESSION['admin'];
                    $moldsdata = M('molds')->find(['biaoshi' => $molds]);
                    $d = format_param($data[$v['field']]);
                    foreach ($classtypedata as $vs) {
                        if ($vs['molds'] == $molds) {
                            if ($admin['classcontrol'] == 0 || $admin['isadmin'] == 1 || strpos($tids, ',' . $vs['id'] . ',') !== false || $moldsdata['iscontrol'] == 0) {
                                if ($d == $vs['id']) {
                                    $fields_search .= '<option selected="selected" value="' . $vs['id'] . '">' . str_repeat('--', $vs['level']) . $vs['classname'] . '</option>';
                                } else {
                                    $fields_search .= '<option  value="' . $vs['id'] . '">' . str_repeat('--', $vs['level']) . $vs['classname'] . '</option>';
                                }

                            }

                        }

                    }

                    $fields_search .= '</select>
				</div>';
                    if (array_key_exists($v['field'], $data)) {
                        if ($d) {
                            $fields_search_check[] = ' tid in(' . implode(",", $classtypedata[$d]["children"]["ids"]) . ') ';
                        }

                    }

                    break;
                case 18:

                    break;
                case 19:
                    $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $v['fieldname'] . '" autocomplete="off" class="layui-input">';
                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = "  " . $v['field'] . " like '%," . format_param($data[$v['field']], 1) . "%,'";
                        }

                    }
                    break;
                case 20://栏目绑定多选
                    $body = explode(',', $v['body']);
                    $tid = (int)$body[0];
                    $classtypedata = classTypeData();
                    $molds = $classtypedata[$tid]['molds'];
                    $moldsdata = M('molds')->find(['biaoshi' => $molds]);
                    $num = M($molds)->getCount();

                    if ($num > 500) {
                        $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $moldsdata['name'] . 'ID" autocomplete="off" class="layui-input">';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) != '') {
                                $fields_search_check[] = "  " . $v['field'] . " like '%," . format_param($data[$v['field']], 1) . ",%'";
                            }

                        }
                    } else {
                        $tids = array_column($classtypedata[$tid]['children']['lists'], 'id');
                        $tids[] = $tid;
                        $sql = " tid in(" . implode(',', $tids) . ") and isshow=1 ";
                        $datalist = M($molds)->findAll($sql, 'id desc', 'id,' . $body[1]);
                        $fields_search .= '<div class="layui-input-inline">
			  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
			  <option value="">请选择' . $v['fieldname'] . '</option>';
                        $d = format_param($data[$v['field']]);
                        foreach ($datalist as $vv) {
                            $fields_search .= '<option ';
                            if (array_key_exists($v['field'], $data)) {
                                if ($d == $vv['id']) {
                                    $fields_search .= 'selected="selected"';
                                }
                            }
                            $fields_search .= 'value="' . $vv['id'] . '">' . $vv[$body[1]] . '</option>';
                        }

                        $fields_search .= '</select>
			 </div>';
                    }

                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = " " . $v['field'] . " like '%," . format_param($data[$v['field']]) . ",%' ";
                        }

                    }
                    break;
                case 21://栏目绑定单选
                    $body = explode(',', $v['body']);
                    $tid = (int)$body[0];
                    $classtypedata = classTypeData();
                    $molds = $classtypedata[$tid]['molds'];
                    $num = M($molds)->getCount();
                    $moldsdata = M('molds')->find(['biaoshi' => $molds]);

                    if ($num > 500) {
                        $fields_search .= '<input type="text" name="' . $v['field'] . '" value="' . format_param($data[$v['field']], 1) . '" placeholder="请输入' . $moldsdata['name'] . 'ID" autocomplete="off" class="layui-input">';
                        if (array_key_exists($v['field'], $data)) {
                            if (format_param($data[$v['field']], 1) != '') {
                                $fields_search_check[] = "  " . $v['field'] . " like '%" . format_param($data[$v['field']], 1) . "%'";
                            }

                        }
                    } else {
                        $tids = array_column($classtypedata[$tid]['children']['lists'], 'id');
                        $tids[] = $tid;
                        $sql = " tid in(" . implode(',', $tids) . ") and isshow=1 ";
                        $datalist = M($molds)->findAll($sql, 'id desc', 'id,' . $body[1]);
                        $fields_search .= '<div class="layui-input-inline">
			  <select name="' . $v['field'] . '" lay-search="" class="layui-inline">
			  <option value="">请选择' . $v['fieldname'] . '</option>';
                        foreach ($datalist as $vv) {
                            $fields_search .= '<option ';
                            if (array_key_exists($v['field'], $data)) {
                                if (format_param($data[$v['field']]) == $vv['id']) {
                                    $fields_search .= 'selected="selected"';
                                }
                            }
                            $fields_search .= 'value="' . $vv['id'] . '">' . $vv[$body[1]] . '</option>';
                        }

                        $fields_search .= '</select>
			 </div>';
                    }

                    if (array_key_exists($v['field'], $data)) {
                        if (format_param($data[$v['field']], 1) != '') {
                            $fields_search_check[] = " " . $v['field'] . " =" . format_param($data[$v['field']]) . " ";
                        }

                    }

                    break;


            }


        }
        if (count($fields_search_check) > 0) {
            $fields_search_check = implode(' and ', $fields_search_check);
        } else {
            $fields_search_check = '';
        }
        return array('fields_search' => $fields_search, 'fields_search_check' => $fields_search_check);
    }
}
if(!function_exists('get_classtype')){
    function get_classtype(){
        $classtypedata = getCache('jzclasstypedata');
        if(!$classtypedata){
            $classtype = M('classtype')->findAll();
            $classtypedata = [];
            foreach ($classtype as $v){
                $classtypedata[$v['id']] = $v;
            }
            setCache('jzclasstypedata',$classtypedata);
        }
        return $classtypedata;
    }
}
// 后台格式化类型显示
if(!function_exists('format_fields')) {
    function format_fields($fields = null, $data = null)
    {
        $classtypedata = get_classtype();
        if ($fields == null) {
            $list = array(

                'string_10' => '截取10个字',
                'string_15' => '截取15个字',
                'date_1' => '日期(Y-m-d)',
                'date_2' => '日期(Y-m-d H:i:s)',
            );
            return $list;
        } else {
            switch ($fields['format']) {
                case 'string_10':
                    return newstr($data, 10);
                    break;
                case 'string_15':
                    return newstr($data, 15);
                    break;
                case 'date_1':
                    return "\t" . date('Y-m-d', $data) . "\t";
                    break;
                case 'date_2':
                    return "\t" . date('Y-m-d H:i:s', $data) . "\t";
                    break;
                default:
                    if ($fields['fieldtype'] == 7 || $fields['fieldtype'] == 12) {
                        $r = explode(',', $fields['body']);
                        foreach ($r as $v) {
                            $d = explode('=', $v);
                            if ($d[1] == $data) {
                                return $d[0];
                                exit;
                            }
                        }
                    } else if ($fields['fieldtype'] == 8) {
                        $r = explode(',', $fields['body']);
                        $rr = array();
                        foreach ($r as $v) {
                            $d = explode('=', $v);
                            if (strpos($data, ',' . $d[1] . ',') !== false) {
                                $rr[] = $d[0];
                            }
                        }
                        return implode(',', $rr);
                    } else if ($fields['fieldtype'] == 5) {
                        $vdata = $data != '' ? '<a href="' . $data . '" target="_blank"><img src="' . $data . '" width="100px"  /></a>' : '';
                        return $vdata;
                    } else if ($fields['fieldtype'] == 6) {
                        //图集
                        if ($data != '') {
                            $vdata = explode('||', $data);
                            $res = '';
                            foreach ($data as $s) {
                                if ($s != '') {
                                    $res .= '<a href="' . $s . '" target="_blank"><img src="' . $s . '" width="50px" /></a>';
                                }
                            }
                            return $res;
                        } else {
                            return '';
                        }
                    } else if ($fields['fieldtype'] == 9) {
                        $vdata = $data != '' ? '<a href="' . $data . '" target="_blank">[查看]</a>' : '';
                        return $vdata;
                    } else if ($fields['fieldtype'] == 10) {
                        if ($data != '') {
                            $vdata = explode('||', $data);
                            $res = '';
                            foreach ($data as $s) {
                                if ($s != '') {
                                    $res .= '<a href="' . $s . '" target="_blank">[查看]</a>';
                                }
                            }
                            return $res;
                        } else {
                            return '';
                        }
                    } else if ($fields['fieldtype'] == 11) {
                        $vdata = $data == 0 ? '-' : "\t" . date('Y-m-d H:i:s', $data) . "\t";
                        return $vdata;
                    } else if ($fields['fieldtype'] == 13) {
                        $body = explode(',', $fields['body']);
                        $biaoshi = M('molds')->getField(['id' => $body[0]], 'biaoshi');
                        $res = M($biaoshi)->getField(['id' => $data], $body[1]);
                        if (!$res) {
                            return '[ 空 ]';
                        }
                        return $res;


                    } else if ($fields['fieldtype'] == 16) {
                        //多选关联
                        if (trim($data, ',')) {
                            $res = trim($data, ',');
                            $body = explode(',', $fields['body']);
                            $biaoshi = M('molds')->getField(['id' => $body[0]], 'biaoshi');
                            $all = M($biaoshi)->findAll('id in(' . $res . ')', null, $body[1]);
                            $ss = '[' . implode(',', array_column($all, $body[1])) . ']';
                            return $ss;
                        }
                    } else if ($fields['fieldtype'] == 17) {
                        $ids = explode(',', $data);
                        $name = [];
                        foreach ($ids as $v) {
                            $name[] = $classtypedata[$v]['classname'];
                        }
                        return implode(',', $name);
                    } else if ($fields['fieldtype'] == 18) {

                        return $data == 0 ? '[未绑定栏目]' : $classtypedata[$data]['classname'];

                    } else if ($fields['fieldtype'] == 19) {
                        if ($data) {
                            return trim($data, ',');
                        }
                    } else if ($fields['fieldtype'] == 21) {
                        $body = explode(',', $fields['body']);
                        $tid = (int)$body[0];
                        $molds = $classtypedata[$tid]['molds'];
                        $res = M($molds)->getField(['id' => $data], $body[1]);
                        if (!$res) {
                            return '[ 空 ]';
                        }
                        return $res;


                    } else if ($fields['fieldtype'] == 20) {
                        //栏目关联多选
                        if (trim($data, ',')) {
                            $res = trim($data, ',');
                            $body = explode(',', $fields['body']);
                            $tid = (int)$body[0];
                            $molds = $classtypedata[$tid]['molds'];
                            $tids = array_column($classtypedata[$tid]['children']['lists'], 'id');
                            $tids[] = $tid;
                            $all = M($molds)->findAll('id in(' . $res . ') and tid in(' . implode(',', $tids) . ')', null, $body[1]);
                            $ss = '[' . implode(',', array_column($all, $body[1])) . ']';

                            return $ss;
                        }
                    }
                    return $data;
                    break;
            }
        }

    }
}
// 图形验证码--旧版本使用-无作用
if(!function_exists('frvercode')) {
    function frvercode($num = 4, $str = 'frcode')
    {
        //创建随机码
        $_nmsg = '';
        for ($i = 0; $i < $num; $i++) {
            $_nmsg .= dechex(mt_rand(0, 15));
        }
        //保存在session里
        $_SESSION[$str] = md5(md5($_nmsg));
        //长和高
        $_width = 75;
        $_height = 25;
        //创建图像
        $_img = imagecreatetruecolor($_width, $_height);
        $_white = imagecolorallocate($_img, 255, 255, 255);
        imagefill($_img, 0, 0, $_white);
        /*
        //创建黑色边框
        $_black = imagecolorallocate($_img, 100, 100, 100);
        imagerectangle($_img, 0, 0, $_width-1, $_height-1, $_black);
        //随机划线条
        for ($i=0;$i<6;$i++) {
        $_rnd_color= imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255)
        ,mt_rand(0,255));
        imageline($_img,mt_rand(0,75),mt_rand(0,25),mt_rand(0,75),mt_rand(0,25)
        ,$_rnd_color);
        }
        //随机打雪花
        for ($i=1;$i<100;$i++) {
        imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),"*",
        imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255)));
        }
        */
        //输出验证码
        for ($i = 0; $i < strlen($_nmsg); $i++) {
            imagestring($_img, mt_rand(3, 5), $i * $_width / $num + mt_rand(1, 10),
                mt_rand(1, $_height / 2), $_nmsg[$i],
                imagecolorallocate($_img, mt_rand(0, 150), mt_rand(0, 100), mt_rand(0, 150)));
        }
        //输出图像
        //ob_clean();
        header('Content-Type:image/png');
        imagepng($_img);
        //销毁
        imagedestroy($_img);

    }
}
// 判断是否包含
if(!function_exists('aCheckSubstrs')) {
    function aCheckSubstrs($substrs, $text)
    {
        foreach ($substrs as $substr)
            if (false !== strpos($text, $substr)) {
                return true;
            }
        return false;
    }
}
// 判断是否为手机端
if(!function_exists('isMobile')) {
    function isMobile()
    {
        $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';

        $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
        $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod', 'iPad');

        $found_mobile = aCheckSubstrs($mobile_os_list, $useragent_commentsblock) ||
            aCheckSubstrs($mobile_token_list, $useragent);

        if ($found_mobile) {
            return true;
        } else {
            return false;
        }
    }
}
// 判断是否微信端
if(!function_exists('isWeixin')) {
    function isWeixin()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}
// 获取前台链接
if(!function_exists('gourl')) {
    function gourl($id, $htmlurl = null, $molds = 'article')
    {
        $www = webConf('domain') ? webConf('domain') : get_domain();
        if (is_array($id)) {
            /**
             * ownurl target id
             **/
            $value = $id;
            if ($value['target']) {
                return $value['target'];
            } else {
                if ($value['ownurl']) {
                    return $www . '/' . $value['ownurl'];

                }
            }
            $id = $value['id'];
            $htmlurl = $value['htmlurl'];
        }
        if (!$id) {
            Error_msg('缺少ID！');
        }
        $htmlpath = (isMobile() && webConf('iswap') == 1) ? webConf('mobile_html') : webConf('pc_html');
        $htmlpath = ($htmlpath == '' || $htmlpath == '/') ? '' : '/' . $htmlpath;
        if ($htmlurl != null) {
            return $www . $htmlpath . '/' . $htmlurl . '/' . $id . '.html';
        }

        $tid = M($molds)->getField(array('id' => $id), 'tid');
        $htmlurl = M('classtype')->getField(array('id' => $tid), 'htmlurl');
        return $www . $htmlpath . '/' . $htmlurl . '/' . $id . '.html';
    }
}
// 获取前台链接--同上
if(!function_exists('all_url')) {
    function all_url($id, $molds = 'article', $htmlurl = null){
        $www = webConf('domain') ? webConf('domain') : get_domain();
        if (is_array($id)) {
            /**
             * ownurl target id
             **/
            $value = $id;
            if ($value['target']) {
                return $value['target'];
            } else {
                if ($value['ownurl']) {
                    return $www . '/' . $value['ownurl'];
                }
            }
            $id = $value['id'];
        }
        if (!$id) {
            Error_msg('缺少ID！');
        }
        $htmlpath = isMobile() && webConf('isopen') ? webConf('mobile_html') : webConf('pc_html');
        $htmlpath = ($htmlpath == '' || $htmlpath == '/') ? '' : '/' . $htmlpath;
        if ($htmlurl != null) {
            $file_txt = File_TXT_HIDE ? '' : '.html';
            return $www . $htmlpath . '/' . $htmlurl . '/' . $id . $file_txt;
        }
        $tid = M($molds)->getField(array('id' => $id), 'tid');
        $htmlurl = M('classtype')->getField(array('id' => $tid), 'htmlurl');
        $file_txt = File_TXT_HIDE ? '' : '.html';
        return $www . $htmlpath . '/' . $htmlurl . '/' . $id . $file_txt;
    }
}
// 自定义递增函数
if(!function_exists('incrData')) {
    function incrData($table = null, $id = 0, $field = 'hits', $num = 1)
    {

        if (!format_param($table, 1)) {
            Error_msg($table . '表不存在！');
        }
        if (!format_param($id)) {
            Error_msg('缺少ID！');
        }
        if (!format_param($field, 1)) {
            Error_msg('递增字段缺少！');
        }
        if (!format_param($num)) {
            Error_msg('递增数据格式错误！');
        }

        $r = M($table)->goInc(array('id' => $id), $field, $num);
        if (!$r) {
            return '递增失败！';
        }
        return M($table)->getField(array('id' => $id), $field);
    }
}
//自定义字段单项/多项选择获取
if(!function_exists('get_key_field_select')) {
    function get_key_field_select($key = 0, $molds = null, $field = null)
    {
        if ($molds == null || $field == null) {
            echo '参数molds或field缺少';
            exit;
        }
        $res = M('Fields')->find(array('molds' => $molds, 'field' => $field), null, 'body,fieldtype');
        if ($res) {
            $value = explode(',', $res['body']);
            if ($res['fieldtype'] == 7 || $res['fieldtype'] == 12) {
                //单选
                foreach ($value as $v) {
                    $d = explode('=', $v);
                    if ($d[1] == $key) {
                        return $d[0];
                    }
                }
                return false;
            } else if ($res['fieldtype'] == 13) {
                $biaoshi = M('molds')->getField(['id' => $value[0]], 'biaoshi');
                $data = M($biaoshi)->getField(['id' => $key], $value[1]);
                return $data;
            } else if ($res['fieldtype'] == 20) {
                $classtypedata = classTypeData();
                $tid = (int)$value[0];
                $biaoshi = $classtypedata[$tid]['molds'];
                $tids = array_column($classtypedata[$tid]['children']['lists'], 'id');
                $tids[] = $tid;
                $sql = "id in(" . implode(',', trim($key, ',')) . ") and tid in(" . implode(',', $tids) . ") ";
                $data = M($biaoshi)->findAll($sql, null, $value[1]);
                return array_column($data, $value[1]);
            } else if ($res['fieldtype'] == 21) {
                $classtypedata = classTypeData();
                $tid = (int)$value[0];
                $biaoshi = $classtypedata[$tid]['molds'];
                $data = M($biaoshi)->getField(['id' => $key], $value[1]);
                return $data;
            } else {
                $s = array();
                foreach ($value as $v) {
                    $d = explode('=', $v);
                    if (strpos($key, ',' . $d[1] . ',') !== false) {
                        $s[] = $d[0];
                    }
                }
                return $s;
            }


        } else {
            return '没有查询到该字段内容！';
        }

    }
}
//根据模型[$molds]、字段[$field]获取并输出内容选项
if(!function_exists('get_field_select')) {
    function get_field_select($molds = null, $field = null)
    {
        if ($molds == null || $field == null) {
            echo '参数molds或field缺少';
            exit;
        }
        $res = M('Fields')->find(array('molds' => $molds, 'field' => $field), null, 'body,fieldtype');
        if ($res) {
            $value = explode(',', $res['body']);
            $s = array();
            foreach ($value as $v) {
                $s[] = explode('=', $v);

            }
            return $s;

        } else {
            return '没有查询到该字段内容！';
        }

    }
}
//获取文件大小
if(!function_exists('get_file_byte')) {
    function get_file_byte($file)
    {
        $byte = filesize($file);

        $KB = 1024;

        $MB = 1024 * $KB;

        $GB = 1024 * $MB;

        $TB = 1024 * $GB;

        if ($byte < $KB) {

            return $byte . "B";

        } elseif ($byte < $MB) {

            return round($byte / $KB, 2) . "KB";

        } elseif ($byte < $GB) {

            return round($byte / $MB, 2) . "MB";

        } elseif ($byte < $TB) {

            return round($byte / $GB, 2) . "GB";

        } else {

            return round($byte / $TB, 2) . "TB";

        }

    }
}
//获取文章评论
if(!function_exists('show_comment')) {
    function show_comment($tid = 0, $id = 0, $str = null)
    {
        if ($tid == 0 || $id == 0) {
            return false;
        }

        $lists = M('comment')->findAll(['tid' => $tid, 'aid' => $id, 'isshow' => 1], 'addtime asc');
        $star_num = 0;
        $count = 0;
        if ($lists) {
            foreach ($lists as $k => $v) {
                $star_num += $v['likes'];
                $lists[$k]['userinfo'] = M('member')->find(['id' => $v['userid']]);
                if ($v['likes'] > 0) {
                    $count += 1;
                }
            }
            $lists = set_class_haschild($lists);
            $lists = getTree($lists);
        }
        if ($count != 0) {
            $average = round($star_num / $count, 1);
        } else {
            $average = 0;
        }
        $res = array('data' => $lists, 'star' => $star_num, 'count' => $count, 'average' => $average);
        if ($str != null) {
            return $res[$str];
        }
        return $res;
    }
}
//获取指定评论用户姓名
if(!function_exists('get_comment_user')) {
    function get_comment_user($id)
    {
        $userid = M('comment')->getField(['id' => $id], 'userid');
        if (!$userid) {
            return '';
        } else {
            return M('member')->getField(['id' => $userid], 'username');
        }
    }
}
//计算评论数量---或者直接comment_num显示
if(!function_exists('get_comment_num')) {
    function get_comment_num($tid, $id = 0)
    {
        if ($id == 0) {
            return '缺少ID！';
        }
        $count = M('comment')->getCount(['aid' => $id, 'tid' => $tid, 'isshow' => 1]);
        return $count;
    }
}
//处理数组拼接--Screen筛选功能有使用
if(!function_exists('change_parse_url')) {
    function change_parse_url($arr, $str)
    {
        if (count($arr) == 0) {
            return '';
        }
        unset($arr[$str]);
        if (count($arr) == 0) {
            return '';
        }
        $url = str_replace('=', '-', http_build_query($arr, false, '-'));
        return '-' . $url;
    }
}
//获取扩展字段内容输出
if(!function_exists('get_fields_show')) {
    function get_fields_show($tid, $molds)
    {
        $sql = array();
        if ($tid != 0) {
            $sql[] = " tids like '%," . $tid . ",%' ";
        }

        $sql[] = " molds = '" . $molds . "' and isshow=1 ";
        $sql = implode(' and ', $sql);
        $fields_list = M('Fields')->findAll($sql, 'orders desc,id asc');
        return $fields_list;
    }
}
//输出指定字段的标题和内容
if(!function_exists('jz_show_fields')) {
    function jz_show_fields($data = array(), $fields = null)
    {
        $sql = array();
        if ($data['tid'] != 0) {
            $sql[] = " tids like '%," . $data['tid'] . ",%' ";
        }
        if ($fields) {
            $arr = explode(',', $fields);
            $r = [];
            foreach ($arr as $v) {
                $r[] = " field='" . $v . "' ";
            }
            $sql[] = " (" . implode(' or ', $r) . ") ";
        }
        $sql[] = " molds = '" . $data['molds'] . "' and isshow=1 ";
        $sql = implode(' and ', $sql);
        $fields_list = M('Fields')->findAll($sql, 'orders desc,id asc');
        $new = [];
        foreach ($fields_list as $k => $v) {
            $new[$k]['title'] = $v['fieldname'];
            $new[$k]['field'] = $v['field'];

            switch ($v['fieldtype']) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 9:
                case 14:
                    $new[$k]['data'] = $data[$v['field']];
                    break;
                case 6:
                case 10:
                case 15:
                    $new[$k]['data'] = explode('||', $data[$v['field']]);
                    break;
                case 7:
                case 12:
                    $value = explode(',', $v['body']);
                    foreach ($value as $vv) {
                        $d = explode('=', $vv);
                        if ($d[1] == $data[$v['field']]) {
                            $new[$k]['data'] = $d[0];
                        }

                    }

                    break;
                case 8:
                    $r = [];
                    $value = explode(',', $v['body']);
                    foreach ($value as $vv) {
                        $d = explode('=', $vv);
                        if (stripos($data[$v['field']], ',' . $d[1] . ',') !== false) {
                            $r[] = $d[0];
                        }

                    }
                    $new[$k]['data'] = implode(',', $r);

                    break;
                case 11:
                    $new[$k]['data'] = date('Y-m-d H:i:s', $data[$v['field']]);
                    break;
                case 13:
                    $body = explode(',', $v['body']);
                    $biaoshi = M('molds')->getField(['id' => $body[0]], 'biaoshi');
                    $new[$k]['data'] = M($biaoshi)->getField(['id' => $data[$v['field']]], $body[1]);
                    break;
                case 16:
                    $body = explode(',', $v['body']);
                    $biaoshi = M('molds')->getField(['id' => $body[0]], 'biaoshi');
                    $s = trim($data[$v['field']], ',');
                    $datalist = M($biaoshi)->findAll('id in(' . $s . ')', null, $body[1]);
                    $r = [];
                    foreach ($datalist as $vv) {
                        $r[] = $vv[$body[1]];
                    }
                    $new[$k]['data'] = implode(',', $r);
                    break;
                case 17:
                    $classtypedata = classTypeData();
                    $new[$k]['data'] = $classtypedata[$data[$v['field']]]['classname'];
                    break;
                case 18:
                    $s = trim($data[$v['field']], ',');
                    $arr = explode(',', $s);
                    $r = [];
                    $classtypedata = classTypeData();
                    foreach ($arr as $vv) {
                        $r[] = $classtypedata[$vv]['classname'];
                    }
                    $new[$k]['data'] = implode(',', $r);
                    break;
                case 19:
                    $new[$k]['data'] = trim($data[$v['field']], ',');
                    break;
                case 20://绑定栏目多选
                    $body = explode(',', $v['body']);
                    $classtypedata = classTypeData();
                    $tid = (int)$body[0];
                    $molds = $classtypedata[$tid]['molds'];
                    $s = trim($data[$v['field']], ',');
                    $tids = array_column($classtypedata[$tid]['children']['lists'], 'id');
                    $tids[] = $tid;
                    $datalist = M($molds)->findAll('id in(' . $s . ') and tid in(' . implode(',', $tids) . ')', null, $body[1]);

                    $new[$k]['data'] = implode(',', array_column($datalist, $body[1]));
                    break;
                case 21://绑定栏目单选
                    $body = explode(',', $v['body']);
                    $classtypedata = classTypeData();
                    $tid = (int)$body[0];
                    $molds = $classtypedata[$tid]['molds'];
                    $new[$k]['data'] = M($molds)->getField(['id' => $data[$v['field']]], $body[1]);
                    break;
                default:
                    $new[$k]['data'] = $data[$v['field']];
                    break;
            }
            $new[$k]['type'] = $v['fieldtype'];
        }

        return $new;

    }
}
//发送邮件处理
if(!function_exists('send_mail')) {
    function send_mail($send_mail, $password, $send_name, $to_mail, $title, $body, $email_ext = '')
    {

        require_once(APP_PATH . 'frphp/extend/PHPMailer/PHPMailerAutoload.php');
        require_once(APP_PATH . 'frphp/extend/PHPMailer/class.phpmailer.php');
        require_once(APP_PATH . "frphp/extend/PHPMailer/class.smtp.php");

        $mail = new PHPMailer();

        $host = webConf('email_server');
        $port = (int)webConf('email_port');
        if (!$host || !$port) {
            exit('邮件服务器未配置完成');
        }

        if (strpos($host, 'qq') !== false) {
            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $send_mail;
            $mail->Password = $password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $port;
            $mail->SetFrom($send_mail, $send_name);
            $address = $to_mail;

            if (is_array($email_ext)) {
                foreach ($email_ext as $v) {
                    $mail->AddAddress($v, $send_name);
                }
            } else if ($email_ext != '') {
                $mail->AddAddress($email_ext, $send_name);
            }
            $mail->AddAddress($address, $send_name);
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;
        } else {
            $mail->IsSMTP(); // telling the class to use SMTP

            $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)

            $mail->SMTPAuth = true; // enable SMTP authentication

            $mail->SMTPSecure = "ssl"; // sets the prefix to the servier

            //$mail->SMTPSecure = false; // sets the prefix to the servier

            $mail->Host = $host; // sets GMAIL as the SMTP server

            $mail->Port = $port; // set the SMTP port for the GMAIL server

            $mail->Username = $send_mail; // GMAIL username

            $mail->Password = $password; // GMAIL password

            $mail->SetFrom($send_mail, $send_name);

            //$mail->AddReplyTo("xxx@xxx.com","First Last");

            $mail->Subject = $title;

            $mail->AltBody = $title; // optional, comment out and test

            $mail->MsgHTML($body);

            $mail->CharSet = "utf-8"; // 这里指定字符集！

            $address = $to_mail;

            if (is_array($email_ext)) {
                foreach ($email_ext as $v) {
                    $mail->AddAddress($v, $send_name);
                }
            } else if ($email_ext != '') {
                $email_ext = explode(',', $email_ext);
                foreach ($email_ext as $v) {
                    $mail->AddAddress($v, $send_name);
                }
            }

            $mail->AddAddress($address, $send_name);

        }
        if (!$mail->Send()) {

            // echo "Mailer Error: " . $mail->ErrorInfo;
            return false;

        } else {

            //echo "Message sent!";
            return true;

        }

    }
}
//检测是否收藏
if(!function_exists('checkCollect')) {
    function checkCollect($tid = 0, $id = 0)
    {
        if ($tid && $id && isset($_SESSION['member'])) {
            $isok = M('shouchang')->getCount(['tid' => $tid, 'aid' => $id, 'userid' => $_SESSION['member']['id']]);
            return $isok;


        } else {
            return false;
        }

    }
}
//检测是否点赞
if(!function_exists('checkLikes')) {
    function checkLikes($tid = 0, $id = 0)
    {
        if (isset($_SESSION['member']) && $_SESSION['member']['id']) {
            if ($tid && $id) {
                $isok = M('likes')->getCount(['tid' => $tid, 'aid' => $id, 'userid' => $_SESSION['member']['id']]);
                return $isok;

            } else {
                return false;
            }
        } else {
            if ($tid && $id && isset($_SESSION['likes'])) {
                if (in_array($tid . '-' . $id, $_SESSION['likes'])) {
                    return true;
                } else {
                    return false;
                }


            } else {
                return false;
            }
        }


    }
}
//检查多少未读评论
if(!function_exists('has_no_read_comment')) {
    function has_no_read_comment()
    {
        if (!isset($_SESSION['member'])) {
            return 0;
        }
        $sql = 'userid=' . $_SESSION['member']['id'] . " and isshow=1 and (type = 'comment' or type = 'reply') and isread=0 ";
        $count = M('task')->getCount($sql);
        return $count;
    }
}
//检查多少未读消息
if(!function_exists('has_no_read_msg')) {
    function has_no_read_msg()
    {
        if (!isset($_SESSION['member'])) {
            return 0;
        }
        //增对个人用户是否关闭提醒
        $sql = 'userid=' . $_SESSION['member']['id'] . " and isshow=1  and isread=0 ";
        if (!$_SESSION['member']['ismsg']) {
            $sql .= " and type = '0' ";//只接收交易提醒
        }
        if (!$_SESSION['member']['iscomment']) {
            $sql .= " and type != 'comment' and type != 'reply'  ";
        }
        if (!$_SESSION['member']['iscollect']) {
            $sql .= " and type != 'collect'  ";
        }
        if (!$_SESSION['member']['islikes']) {
            $sql .= " and type != 'likes'  ";
        }
        if (!$_SESSION['member']['isat']) {
            $sql .= " and type != 'at'  ";
        }
        if (!$_SESSION['member']['isrechange']) {
            $sql .= " and type != 'rechange'  ";
        }

        $count = M('task')->getCount($sql);
        return $count;
    }
}
//数据库html反转义
if(!function_exists('html_decode')) {
    function html_decode($data = null)
    {
        if (!$data) return '';
        $data = str_replace('&#039;', "'", htmlspecialchars_decode($data));
        return $data;

    }
}
//字符串替换
if(!function_exists('str_replace_limit')) {
    function str_replace_limit($search, $replace, $subject, $limit = -1)
    {
        if (is_array($search)) {
            foreach ($search as $k => $v) {
                $search[$k] = '`' . preg_quote($search[$k], '`') . '`';
            }
        } else {
            $search = '`' . preg_quote($search, '`') . '`';
        }
        return preg_replace($search, $replace, $subject, $limit);
    }
}
//人性化时间显示
if(!function_exists('formatTime')) {
    function formatTime($sTime, $formt = 'Y-m-d')
    {

        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = intval($dTime / 86400);
        $dYear = intval(date('Y', $cTime)) - intval(date('Y', $sTime));

        //n秒前，n分钟前，n小时前，日期
        if ($dTime < 60) {
            if ($dTime < 10) {

                if ($dTime < 0) {
                    return date($formt, $sTime);
                } else {
                    return '刚刚';
                }
            } else {
                return intval(floor($dTime / 10) * 10) . '秒前';
            }
        } else if ($dTime < 3600) {
            return intval($dTime / 60) . '分钟前';
        } else if ($dTime >= 3600 && $dDay == 0) {
            return intval($dTime / 3600) . '小时前';
        } else if ($dDay > 0 && $dDay <= 7) {
            return intval($dDay) . '天前';
        } else if ($dDay > 7 && $dDay <= 30) {
            return intval($dDay / 7) . '周前';
        } else if ($dDay > 30 && $dDay < 365) {
            return intval($dDay / 30) . '个月前';
        } else {
            return date($formt, $sTime);
        }
    }
}
//过滤HTML代码函数
if(!function_exists('htmldecode')) {
    function htmldecode($data)
    {
        $data = strip_tags($data);
        $data = str_replace('&nbsp;', '', $data);
        return $data;
    }
}
//计算点赞数
if(!function_exists('jz_zan')) {
    function jz_zan($tid, $id)
    {

        $count = M('likes')->getCount(['tid' => $tid, 'aid' => $id]);

        return $count;

    }
}
//计算收藏数
if(!function_exists('jz_collect')) {
    function jz_collect($tid, $id)
    {

        $count = M('shouchang')->getCount(['tid' => $tid, 'aid' => $id]);
        return $count;

    }
}
//用户详情
if(!function_exists('memberInfo')) {
    function memberInfo($id, $str = null)
    {
        $user = M('member')->find('id=' . $id);
        if ($str != null) {
            return $user[$str];
        }
        return $user;

    }
}
//图片水印
if(!function_exists('watermark')) {
    function watermark($img, $water, $pos = 9, $tm = 100, $word = '')
    {
        $tm = $tm ?: 100;
        if(file_exists($water)){
            $info = getImageInfo($img);
    
            $logo = getImageInfo($water);
    
            $dst = openImg($img, $info['type']);
            $src = openImg($water, $logo['type']);
    
    
            switch ($pos) {
                case 1:
                    $x = 0;
                    $y = 0;
                    break;
                case 2:
                    $x = ceil(($info['width'] - $logo['width']) / 2);
                    $y = 0;
                    break;
                case 3:
                    $x = $info['width'] - $logo['width'];
                    $y = 0;
                    break;
                case 4:
                    $x = 0;
                    $y = ceil(($info['height'] - $logo['height']) / 2);
                    break;
                case 5:
                    $x = ceil(($info['width'] - $logo['width']) / 2);
                    $y = ceil(($info['height'] - $logo['height']) / 2);
                    break;
                case 6:
                    $x = $info['width'] - $logo['width'];
                    $y = ceil(($info['height'] - $logo['height']) / 2);
                    break;
        
                case 7:
                    $x = 0;
                    $y = $info['height'] - $logo['height'];
                    break;
                case 8:
                    $x = ceil(($info['width'] - $logo['width']) / 2);
                    $y = $info['height'] - $logo['height'];
                    break;
                case 9:
                    $x = $info['width'] - $logo['width'];
                    $y = $info['height'] - $logo['height'];
                    break;
                case 0:
                default:
                    $x = mt_rand(0, $info['width'] - $logo['width']);
                    $y = mt_rand(0, $y = $info['height'] - $logo['height']);
                    break;
        
            }
            imagecopymerge($dst, $src, $x, $y, 0, 0, $logo['width'], $logo['height'], $tm);
    
    
            imagejpeg($dst, $img);
    
            imagedestroy($dst);
            imagedestroy($src);
            return $img;
        }else if($word){
    
            $webconf = webConf();
            // 图片路径
            $imagePath = $img;
            // 文字水印内容
            $text = $word;
            // 每行文字数
            $charsPerLine = 10;
            // 文字大小
            $fontSize = 24;
            // 文字行高
            $lineHeight = 34;
            // 文字颜色（RGB格式）
            if(!empty($webconf['watermark_rgb']) && (strlen($webconf['watermark_rgb'])==7)) {
                $r = hexdec(substr($webconf['watermark_rgb'],1,2));
                $g = hexdec(substr($webconf['watermark_rgb'],3,2));
                $b = hexdec(substr($webconf['watermark_rgb'],5));
            }else{
                $r = $g = $b = 255;
            }
            $color = [$r, $g, $b];
            // 文字字体路径
            $fontPath = $webconf['watermark_font'] ? APP_PATH.'static/common/'.$webconf['watermark_font']:APP_PATH.'static/common/simsun.ttf';
            // 文字水印位置（1-9，左上到右下）
            $position = $webconf['watermark_wz'] ?: 5;
    
            // 创建图像资源
            if(stripos($imagePath,'.png')!==false){
                $image = imagecreatefrompng($imagePath);
            }else if(stripos($imagePath,'.gif')!==false){
                $image = imagecreatefromgif($imagePath);
            }else{
                $image = imagecreatefromjpeg($imagePath);
            }
            // 设置字体文件路径 ---高版本已经废弃
            //putenv('GDFONTPATH=' . realpath('.'));
            // 设置文字颜色
            $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
    
            // 获取图像尺寸
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);
            // 计算文字宽度和高度
            $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
            $textHeight = $textBoundingBox[1] - $textBoundingBox[7];
    
            // 处理文字水印内容并自动换行
            $lines = [];
            $line = '';
            //$chars = mb_str_split($text);
            $chars = smb_str_split($text);
            $newlines = [];
            $l = '';
            $n = 1;//行数
            foreach($chars as $k=>$v){
                $l.=$v;
                if( ($k+1)%$charsPerLine==0){
                    $newlines[] = $l;
                    $l = '';
                    $n += 1;
                }
            }
            $newlines[] = $l;
            //var_dump($newlines);exit;
            //计算文字真实和宽度
            $old = $textHeight+2;
            $textHeight = count($newlines) * $old;
            if($n==1){
                $textWidth = $old * count($chars);
            }else{
                $textWidth = $old * $charsPerLine;
            }
    
    
            // 计算水印位置
            switch ($position) {
                case 1: // 左上
                    $x = 0;
                    $y = 0;
                    break;
                case 2: // 上
                    $x = ($imageWidth - $textWidth) / 2;
                    $y = 0;
                    break;
                case 3: // 右上
                    $x = $imageWidth - $textWidth;
                    $y = 0;
                    break;
                case 4: // 左
                    $x = 0;
                    $y = ($imageHeight - $textHeight) / 2;
                    break;
                case 5: // 居中
                    $x = ($imageWidth - $textWidth) / 2;
                    $y = ($imageHeight - $textHeight) / 2;
                    break;
                case 6: // 右
                    $x = $imageWidth - $textWidth;
                    $y = ($imageHeight - $textHeight) / 2;
                    break;
                case 7: // 左下
                    $x = 0;
                    $y = $imageHeight - $textHeight;
                    break;
                case 8: // 下
                    $x = ($imageWidth - $textWidth) / 2;
                    $y = $imageHeight - $textHeight;
                    break;
                case 9: // 右下
                    $x = $imageWidth - $textWidth;
                    $y = $imageHeight - $textHeight;
                    break;
                default: // 默认为右下
                    $x = $imageWidth - $textWidth;
                    $y = $imageHeight - $textHeight;
                    break;
            }
    
            // 添加文字水印
            $y = $y + $fontSize;
    
            //微调
            $x = $x + $webconf['watermark_x'];
            $y = $y + $webconf['watermark_y'];
    
            foreach ($newlines as $line) {
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $line);
                $y += $lineHeight;
        
        
            }
    
            // 生成新的图像文件名
    
            $source = $imagePath; // 替换为你想要保存的图像文件路径和文件名
            $newImagePath = $source;
    
            // 保存图像到文件
            if(stripos($imagePath,'.png')!==false){
                imagepng($image, $newImagePath);
            }else if(stripos($imagePath,'.gif')!==false){
                imagegif($image, $newImagePath);
            }else{
                imagejpeg($image, $newImagePath);
            }
    
    
            // 释放资源
            imagedestroy($image);
            return str_replace(APP_PATH,'',$source);
        
        }
        
        return $img;

    }
}
if(!function_exists('getImageInfo')) {
    function getImageInfo($path)
    {
        $info = getimagesize($path);
        $data['width'] = $info[0];
        $data['height'] = $info[1];
        $data['type'] = $info['mime'];
        return $data;
    }
}
//打开图片
if(!function_exists('openImg')) {
    function openImg($path, $type)
    {
        switch ($type) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                $img = imagecreatefromjpeg($path);
                break;
            case 'image/png':
            case 'image/x-png':
                $img = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($path);
                break;
            case 'image/wbmp':
                $img = imagecreatefromwbmp($path);
                break;
            default:
                exit('图片类型不支持');


        }
        return $img;
    }
}
//检查是否关注
if(!function_exists('isfollow')) {
    function isfollow($id, $other)
    {
        $follow = M('member')->getField(['id' => $id], 'follow');
        if (strpos($follow, ',' . $other . ',') !== false) {
            return true;
        } else {
            return false;
        }
    }
}
//计算粉丝数
if(!function_exists('jz_fans')) {
    function jz_fans($id = 0)
    {
        if ($id) {
            $user_num = M('member')->getCount(" follow like '%," . $id . ",%'");
            return $user_num;
        } else {
            return 0;
        }
    }
}
//计算关注数
if(!function_exists('jz_follow')) {
    function jz_follow($id = 0)
    {
        if ($id) {
            //,1,2,2,4,
            $follow = M('member')->getField(['id' => $id], 'follow');
            if ($follow != '') {
                $follow = trim($follow, ',');
                $num = substr_count($follow, ',');
                return $num + 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
//删除文件目录
if(!function_exists('deldir')) {
    function deldir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * 图片压缩裁剪
 * src_image 原图链接  根目录绝对链接，支持远程图片
 * out_image 生成图链接  写文件名即可
 * mode=1 按尺寸裁剪 mode=0 按比例裁剪
 * out_width 生成的宽（比例）
 * out_height 生成的高（比例）
 * img_quality 压缩比例（PNG无法压缩）
 * direct=1 中间开始裁剪  direct=0 左上角开始裁剪
 * debug=1 调试状态，每次请求都生成缓存 debug=0 会直接调用已生成的缩略图
 */
if(!function_exists('jzresize')) {
    function jzresize($src_image, $out_width = NULL, $out_height = NULL, $mode = 1, $out_image = NULL, $direct = 1, $debug = 0, $img_quality = 90)
    {
        if (!file_exists('.' . $src_image)) {
            if (strpos($src_image, 'http') !== false) {
                return $src_image;
            }
        } else {
            list($width, $height, $type, $attr) = getimagesize('.' . $src_image);
            if ($width == $out_width && $height == $out_height) {
                return $src_image;
            }
            if (!is_dir(APP_PATH . 'cache/image')) {
                if (!mkdir(APP_PATH . 'cache/image', 0777)) {
                    exit('没有权限[cache/image]');
                }
            }
            if (!$out_image) {
                $imageinfo = pathinfo($src_image);
                $filename = str_replace('.' . $imageinfo['extension'], '_' . $out_width . 'x' . $out_height . '.' . $imageinfo['extension'], $imageinfo['basename']);
                $out_image = 'cache/image/' . $filename;
            }
            if (file_exists(APP_PATH . $out_image) && !$debug) {
                return '/' . $out_image;
            } else {
                if (!copy(APP_PATH . $src_image, $out_image)) {
                    return '';
                }
                list($width, $height, $type, $attr) = getimagesize($out_image);
                switch ($type) {
                    case 1:
                        $img = imagecreatefromgif($out_image);
                        break;
                    case 2:
                        $img = imagecreatefromjpeg($out_image);
                        break;
                    case 3:
                        $img = imagecreatefrompng($out_image);
                        break;
                }
                $out_scale = $out_height / $out_width;
                $src_scale = $height / $width;
                if ($mode == 1) {
                    $w = $out_width;
                    $h = $out_height;
                } else {
                    $w = $width;
                    $h = intval($out_scale * $w);
                    if ($h > $height) {
                        $h = $height;
                        $w = intval($h / $out_scale);
                    }
                }

                if ($direct == 1) {
                    if ($src_scale >= $out_scale) {
                        $w = intval($width);
                        $h = intval($out_scale * $w);
                        $start_x = 0;
                        $start_y = ($height - $h) / 2;
                    } else {
                        $h = intval($height);
                        $w = intval($h / $out_scale);
                        $start_x = ($width - $w) / 2;
                        $start_y = 0;
                    }
                } else {
                    $w = intval($width);
                    $h = intval($height);
                    $start_x = 0;
                    $start_y = 0;
                }

                $scale = $out_width / $w;
                $new_img = imagecreatetruecolor($out_width, $out_height);
                // 根据图像类型处理透明度
                switch ($type) {
                    case 1: // GIF
                        $transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
                        imagefill($new_img, 0, 0, $transparent);
                        imagecolortransparent($new_img, $transparent); // 使背景透明
                        break;
                    case 3: // PNG
                        imagesavealpha($new_img, true); // 保存透明通道信息
                        $transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
                        imagefill($new_img, 0, 0, $transparent);
                        break;
                    default: // 对于不支持透明度的图像类型（如 JPEG），使用白色背景
                        $white = imagecolorallocate($new_img, 255, 255, 255);
                        imagefill($new_img, 0, 0, $white);
                        break;
                }
                imagecopyresampled($new_img, $img, 0, 0, $start_x, $start_y, $out_width, $out_height, $w, $h);
                switch ($type) {
                    case 1: // GIF
                        imagegif($new_img, $out_image); // 保存GIF图像，不需要质量参数
                        break;
                    case 2: // JPEG
                        imagejpeg($new_img, $out_image, $img_quality); // 保存JPEG图像，需要质量参数
                        break;
                    case 3: // PNG
                        imagesavealpha($new_img, true); // 确保保存透明度信息
                        imagepng($new_img, $out_image); // 保存PNG图像，不需要质量参数，但可以设置压缩级别
                        break;
                }
                imagedestroy($img);
                imagedestroy($new_img);
                return '/' . $out_image;
            }
        }
    }
}
if(!function_exists('jzcachedata')) {
    function jzcachedata($field)
    {
        $result = getCache('jzcache_' . $field);
        if (!$result) {
            $res = M('cachedata')->find(['field' => $field]);

            if ($res['isall'] && $res['tid']) {
                $classtypedata = (isMobile() && webConf('iswap') == 1) ? classTypeDataMobile() : classTypeData();
                foreach ($classtypedata as $k => $v) {
                    $classtypedata[$k]['children'] = get_children($v, $classtypedata);
                }
            }


            $tid = $res['tid'] ? ($res['isall'] == 1 ? ' and tid in (' . implode(',', $classtypedata[$res['tid']]['children']['ids']) . ') ' : ' and tid=' . $res['tid']) : '';
            $sqls = $res['sqls'] ? ' and ' . $res['sqls'] : '';
            $orderby = $res['orders'] ? ' order by ' . $res['orders'] : '';
            $limit = $res['limits'] ? ' limit ' . $res['limits'] : '';
            if ($tid || $sqls) {
                $where = ' where 1=1 ' . $tid . htmlspecialchars_decode($sqls, ENT_QUOTES);
            } else {
                $where = '';
            }
            $sql = "select * from " . DB_PREFIX . $res['molds'] . $where . $orderby . $limit;
            $result = M()->findSql($sql);
            if ($result) {
                foreach ($result as $kk => $vv) {
                    if (isset($vv['htmlurl'])) {
                        $result[$kk]['url'] = gourl($vv, $vv['htmlurl']);
                    }
                }
            }
            $time = $res['times'] * 60;
            setCache('jzcache_' . $res['field'], $result, $time);
        }
        return $result;
    }
}
// 增加classtypedata缓存
if(!function_exists('getclasstypedata')) {
    function getclasstypedata($array, $m = 1)
    {
        if ($m) {
            $s = 'classtypedatamobile';
        } else {
            $s = 'classtypedatapc';
        }
        $classtypedata = getCache($s);
        if (!$classtypedata) {
            $classtypedata = $array;
            $classtypemaxlevel = webConf('classtypemaxlevel');
            foreach ($classtypedata as $k => $v) {
                if ($classtypemaxlevel) {
                    $classtypedata[$k]['children'] = get_all_children($v, $classtypedata);
                } else {
                    $classtypedata[$k]['children'] = get_children($v, $classtypedata);
                }

            }
            setCache($s, $classtypedata);
        }
        return $classtypedata;
    }
}
//递归获取全部格式化子类
if(!function_exists('get_all_children')) {
    function get_all_children($type, $classtypedata)
    {
        $res = get_children($type, $classtypedata);
        if ($type['haschild']) {
            foreach ($res['list'] as $k => $v) {
                $res['list'][$k]['children'] = get_all_children($v, $classtypedata);
            }
        }
        return $res;
    }
}
if(!function_exists('jztpldata')) {
    function jztpldata()
    {
        $tpldata = getCache('tpldata');
        if (!$tpldata) {
            $webconf = webConf();
            $m = (isMobile() && $webconf['iswap'] == 1) ? 1 : 0;
            if ($m) {
                $classtypedata = classTypeDataMobile();
            } else {
                $classtypedata = classTypeData();
            }
            $tpldata = [];
            $tpl_data = M('tplfields')->findAll();
            if ($tpl_data) {

                foreach ($tpl_data as $v) {
                    if ($v['tid']) {
                        $v['url'] = $classtypedata[$v['tid']]['url'];
                    }
                    if ($v['orders']) {
                        switch ($v['orders']) {
                            case 1:
                                $v['orders'] = ' addtime desc ';
                                break;
                            case 2:
                                $v['orders'] = ' addtime asc ';
                                break;
                            case 3:
                                $v['orders'] = ' orders desc ';
                                break;
                            case 4:
                                $v['orders'] = ' hits desc ';
                                break;
                            case 5:
                                $v['orders'] = ' id asc ';
                                break;
                            case 6:
                                $v['orders'] = ' id desc ';
                                break;
                        }
                    }
                    switch ($v['fieldtype']) {
                        case 4:
                        case 11:
                            $data = explode('||', $v['data']);
                            $newdata = [];
                            foreach ($data as $kk => $vv) {
                                $pic = explode('|', $vv);
                                $newdata[$kk] = ['url' => $pic[0], 'title' => $pic[1]];
                            }
                            $v['filedata'] = $newdata;

                            break;

                        case 8:
                        case 9:
                            $v['sdata'] = explode("\n", $v['sdata']);

                            break;
                    }
                    $tpldata[$v['pid']][$v['field']] = $v;
                }
            }

            setCache('tpldata', $tpldata);

        }
        return $tpldata;
    }
}
if(!function_exists('jztpldatafield')) {
    function jztpldatafield()
    {
        $tpldata = getCache('tpldata');
        if (!$tpldata) {
            $webconf = webConf();
            $m = (isMobile() && $webconf['iswap'] == 1) ? 1 : 0;
            if ($m) {
                $classtypedata = classTypeDataMobile();
            } else {
                $classtypedata = classTypeData();
            }
            $tpldata = [];
            $tpls = M('tpl')->findAll();
            $tplarr = [];
            foreach ($tpls as $v) {
                $tplarr[$v['id']] = $v;
            }
            $tpl_data = M('tplfields')->findAll();
            if ($tpl_data) {

                foreach ($tpl_data as $v) {
                    if ($v['tid']) {
                        $v['url'] = $classtypedata[$v['tid']]['url'];
                    }
                    if ($v['orders']) {
                        switch ($v['orders']) {
                            case 1:
                                $v['orders'] = ' addtime desc ';
                                break;
                            case 2:
                                $v['orders'] = ' addtime asc ';
                                break;
                            case 3:
                                $v['orders'] = ' orders desc ';
                                break;
                            case 4:
                                $v['orders'] = ' hits desc ';
                                break;
                            case 5:
                                $v['orders'] = ' id asc ';
                                break;
                            case 6:
                                $v['orders'] = ' id desc ';
                                break;
                        }
                    }
                    switch ($v['fieldtype']) {
                        case 4:
                        case 11:
                            $data = explode('||', $v['data']);
                            $newdata = [];
                            foreach ($data as $kk => $vv) {
                                $pic = explode('|', $vv);
                                $newdata[$kk] = ['url' => $pic[0], 'title' => $pic[1]];
                            }
                            $v['filedata'] = $newdata;

                            break;

                        case 8:
                        case 9:
                            $v['sdata'] = explode("\n", $v['sdata']);

                            break;
                    }
                    $tpldata[$tplarr[$v['pid']]['field']][$v['field']] = $v;
                }
            }

            setCache('tpldata2', $tpldata);

        } else {
            $tpldata = getCache('tpldata2');
        }
        return $tpldata;
    }
}
if(!function_exists('waterwordmark')) {
    function waterwordmark($title,$path,$isnew = 1){
        $webconf = webConf();
        // 图片路径
        $imagePath = $path;
        // 文字水印内容
        $text = $title;
        // 每行文字数
        $charsPerLine = $webconf['text_num'] ?: 10;
        // 文字大小
        $fontSize = $webconf['text_size'] ?: 24;
        // 文字行高
        $lineHeight = $webconf['text_h'] ?: 34;
        // 文字间距
        $letterSpacing = $webconf['text_m'] ?: 2;
        // 文字颜色（RGB格式）
        if(!empty($webconf['text_rgb']) && (strlen($webconf['text_rgb'])==7)) {
            $r = hexdec(substr($webconf['text_rgb'],1,2));
            $g = hexdec(substr($webconf['text_rgb'],3,2));
            $b = hexdec(substr($webconf['text_rgb'],5));
        }else{
            $r = $g = $b = 255;
        }
        $color = [$r, $g, $b];
        // 文字字体路径
        $fontPath = $webconf['text_font'] ? APP_PATH.'static/common/'.$webconf['text_font']:APP_PATH.'static/common/simsun.ttf';
        // 文字水印位置（1-9，左上到右下）
        $position = $webconf['text_wz'] ?: 5;
    
        // 创建图像资源
        if(stripos($imagePath,'.png')!==false){
            $image = imagecreatefrompng($imagePath);
        }else if(stripos($imagePath,'.gif')!==false){
            $image = imagecreatefromgif($imagePath);
        }else{
            $image = imagecreatefromjpeg($imagePath);
        }
        // 设置字体文件路径 ---高版本已经废弃
        //putenv('GDFONTPATH=' . realpath('.'));
        // 设置文字颜色
        $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
    
        // 获取图像尺寸
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        // 计算文字宽度和高度
        $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        $textHeight = $textBoundingBox[1] - $textBoundingBox[7];
        
        // 处理文字水印内容并自动换行
        $lines = [];
        $line = '';
        //$chars = mb_str_split($text);
        $chars = smb_str_split($text);
        $newlines = [];
        $l = '';
        $n = 1;//行数
        foreach($chars as $k=>$v){
            $l.=$v;
            if( ($k+1)%$charsPerLine==0){
                $newlines[] = $l;
                $l = '';
                $n += 1;
            }
        }
        $newlines[] = $l;
        //var_dump($newlines);exit;
        //计算文字真实和宽度
        $old = $textHeight+2;
        $textHeight = count($newlines) * $old;
        if($n==1){
            $textWidth = $old * count($chars);
        }else{
            $textWidth = $old * $charsPerLine;
        }
    
    
        // 计算水印位置
        switch ($position) {
            case 1: // 左上
                $x = 0;
                $y = 0;
                break;
            case 2: // 上
                $x = ($imageWidth - $textWidth) / 2;
                $y = 0;
                break;
            case 3: // 右上
                $x = $imageWidth - $textWidth;
                $y = 0;
                break;
            case 4: // 左
                $x = 0;
                $y = ($imageHeight - $textHeight) / 2;
                break;
            case 5: // 居中
                $x = ($imageWidth - $textWidth) / 2;
                $y = ($imageHeight - $textHeight) / 2;
                break;
            case 6: // 右
                $x = $imageWidth - $textWidth;
                $y = ($imageHeight - $textHeight) / 2;
                break;
            case 7: // 左下
                $x = 0;
                $y = $imageHeight - $textHeight;
                break;
            case 8: // 下
                $x = ($imageWidth - $textWidth) / 2;
                $y = $imageHeight - $textHeight;
                break;
            case 9: // 右下
                $x = $imageWidth - $textWidth;
                $y = $imageHeight - $textHeight;
                break;
            default: // 默认为右下
                $x = $imageWidth - $textWidth;
                $y = $imageHeight - $textHeight;
                break;
        }
    
        // 添加文字水印
        $y = $y + $fontSize;
    
        //微调
        $x = $x + $webconf['text_x'];
        $y = $y + $webconf['text_y'];
    
        foreach ($newlines as $line) {
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $line);
            $y += $lineHeight;
        
        
        }
        
        // 生成新的图像文件名
        if($isnew){
            $pic_arr = explode('.',$imagePath);
            $pix = end($pic_arr);
            //$source = $imagePath; // 替换为你想要保存的图像文件路径和文件名
            if(isset($webconf['admin_save_path'])){
                //替换日期事件
                $t = time();
                $d = explode('-', date("Y-y-m-d-H-i-s"));
                $format = $webconf['admin_save_path'];
                $format = str_replace("{yyyy}", $d[0], $format);
                $format = str_replace("{yy}", $d[1], $format);
                $format = str_replace("{mm}", $d[2], $format);
                $format = str_replace("{dd}", $d[3], $format);
                $format = str_replace("{hh}", $d[4], $format);
                $format = str_replace("{ii}", $d[5], $format);
                $format = str_replace("{ss}", $d[6], $format);
                $format = str_replace("{time}", $t, $format);
                if($format!=''){
                    //检查文件是否存在
                    if(strpos($format,'/')!==false && !file_exists(APP_PATH.$format)){
                        $path = explode('/',$format);
                        $path1 = APP_PATH;
                        foreach($path as $v){
                            if($path1==APP_PATH){
                                if(!file_exists($path1.$v)){
                                    mkdir($path1.$v,0777);
                                }
                                $path1.=$v;
                            }else{
                                if(!file_exists($path1.'/'.$v)){
                                    mkdir($path1.'/'.$v,0777);
                                }
                                $path1.='/'.$v;
                            }
                        }
                    }else if(!file_exists(APP_PATH.$format)){
                        mkdir(APP_PATH.$format,0777);
                    }
                    $admin_save_path = $format;
            
                }else{
                    $admin_save_path = 'static/upload';
                }
        
        
            }else{
                $admin_save_path = 'static/upload';
            }
            $source =  APP_PATH.'/'.$admin_save_path.'/'.date('Ymd').rand(1000,9999).'.'.$pix;
            
        }else{
            $source = $imagePath; // 替换为你想要保存的图像文件路径和文件名
        }
        
        $newImagePath = $source;
    
        // 保存图像到文件
        if(stripos($imagePath,'.png')!==false){
            imagepng($image, $newImagePath);
        }else if(stripos($imagePath,'.gif')!==false){
            imagegif($image, $newImagePath);
        }else{
            imagejpeg($image, $newImagePath);
        }
    
    
        // 释放资源
        imagedestroy($image);
    
        return str_replace(APP_PATH,'',$newImagePath);
    }
    
}
if(!function_exists('smb_str_split')) {
    // 将字符串拆分为单个字符
    function smb_str_split($string, $split_length = 1, $encoding = null) {
        if ($split_length < 1) {
            return false;
        }
        if ($encoding === null) {
            $encoding = mb_internal_encoding();
        }
        $result = [];
        $length = mb_strlen($string, $encoding);
        for ($i = 0; $i < $length; $i += $split_length) {
            $result[] = mb_substr($string, $i, $split_length, $encoding);
        }
        return $result;
    }
}

if(!function_exists('check_field_must')){
    function check_field_must($data,$molds){
        // 不为空检测
        $sql = " molds='{$molds}' and isshow=1 ";
        $fields_list = M('Fields')->findAll($sql,'orders desc,id asc');
        if($fields_list){
            foreach($fields_list as $v){
                if($v['ismust']==1){
                    if($data[$v['field']]===''|| $data[$v['field']]===null){
                        if(in_array($v['fieldtype'],array(6,10))){
                            if($data[$v['field'].'_urls']==''){

                                JsonReturn(['code'=>1,'msg'=>$v['fieldname'].JZLANG('不能为空！')]);
                            }
                        }else{
                            JsonReturn(['code'=>1,'msg'=>$v['fieldname'].JZLANG('不能为空！')]);
                        }

                    }
                }
            }
        }
    }
}

if(!function_exists('remote_data_local')){
    function remote_data_local($body = '', $tid=0, $molds=null)
    {
        $webconfig = getCache('webconfig');
        $web_basehost    = get_domain();

        $img_array = array();
        preg_match_all('/<img.*?src="(.*?)".*?>/is', $body, $img_array);



        //preg_match_all('/http(s?):\/\/(.*?).*?\\"/is', $body, $img_array);  //img 被转义的数据

        $img_array = array_unique($img_array[1]);


        if(isset($webconfig['admin_save_path'])){
            //替换日期事件
            $t = time();
            $d = explode('-', date("Y-y-m-d-H-i-s"));
            $format = $webconfig['admin_save_path'];
            $format = str_replace("{yyyy}", $d[0], $format);
            $format = str_replace("{yy}", $d[1], $format);
            $format = str_replace("{mm}", $d[2], $format);
            $format = str_replace("{dd}", $d[3], $format);
            $format = str_replace("{hh}", $d[4], $format);
            $format = str_replace("{ii}", $d[5], $format);
            $format = str_replace("{ss}", $d[6], $format);
            $format = str_replace("{time}", $t, $format);
            if($format!=''){
                //检查文件是否存在
                if(strpos($format,'/')!==false && !file_exists(APP_PATH.$format)){
                    $path = explode('/',$format);
                    $path1 = APP_PATH;
                    foreach($path as $v){
                        if($path1==APP_PATH){
                            if(!file_exists($path1.$v)){
                                mkdir($path1.$v,0777);
                            }
                            $path1.=$v;
                        }else{
                            if(!file_exists($path1.'/'.$v)){
                                mkdir($path1.'/'.$v,0777);
                            }
                            $path1.='/'.$v;
                        }
                    }
                }else if(!file_exists(APP_PATH.$format)){
                    mkdir(APP_PATH.$format,0777);
                }
                $admin_save_path = $format;

            }else{
                $admin_save_path = 'public/Admin';
            }


        }else{
            $admin_save_path = 'public/Admin';
        }
        $dirname =  $admin_save_path.'/';


        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            return $body;
        } else if (!is_writeable($dirname)) {
            return $body;
        }



        foreach ($img_array as $key => $value) {
            $imgUrl = trim($value);
            //  $imgUrl = preg_replace('/\\\"/', '', $imgUrl); //img 被转义的数据
            $imgUrl = preg_replace('/#/', '', $imgUrl);

            // 本站图片 / 根网址图片 / 第三方存储插件的图片
            if (preg_match("/\/\/('.$web_basehost.')\//i", $imgUrl)) {
                continue;
            }
            // 不是合法链接
            if (!preg_match("#^http(s?):\/\/#i", $imgUrl)) {
                continue;
            }

            $heads = @get_headers($imgUrl, 1);

            // 获取请求头并检测死链
            if (empty($heads)) {
                continue;
            } else if (!(stristr($heads[0], "200") && !stristr($heads[0], "304"))) {
                continue;
            }
            // 图片扩展名
            $fileType = substr($heads['Content-Type'], -4, 4);
            if (!preg_match("#\.(jpg|jpeg|gif|png|ico|bmp|webp|svg)#i", $fileType)) {
                if ($fileType == 'image/gif') {
                    $pix = "gif";
                } else if ($fileType == 'image/png') {
                    $pix = "png";
                } else if ($fileType == 'image/x-icon') {
                    $pix = "ico";
                } else if ($fileType == 'image/bmp') {
                    $pix = "bmp";
                }  else if ($fileType == 'image/webp') {
                    $pix = "webp";
                } else if ($heads['Content-Type'] == 'image/svg+xml') {
                    $pix = "svg";
                } else {
                    $pix = 'jpg';
                }
            }
            $pix = strtolower($pix);

            //打开输出缓冲区并获取远程图片
            ob_start();
            $context = stream_context_create(
                array('http' => array(
                    'follow_location' => false // don't follow redirects
                ))
            );
            readfile($imgUrl, false, $context);
            $img = ob_get_contents();
            ob_end_clean();
            preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

            $file             = [];
            $file['oriName']  = $m ? $m[1] : "";
            $file['filesize'] = strlen($img);
            $file['ext']      = $pix;
            $file['name']     = date("ymdHis") . mt_rand(100, 999) .'.'. $file['ext'];
            $file['fullName'] = $dirname . $file['name'];
            $fullName         = $file['fullName'];

            //检查文件大小是否超出限制
            if ($file['filesize'] >= 20480000) {
                continue;
            }

            //移动文件
            if (!(file_put_contents($fullName, $img) && file_exists($fullName))) { //移动失败
                continue;
            }
            //处理水印
            if( (strtolower($pix)=='png' || strtolower($pix)=='jpg' || strtolower($pix)=='jpeg') && $webconfig['iswatermark']==1 ){
                watermark($file['fullName'],APP_PATH.$webconfig['watermark_file'],$webconfig['watermark_t'],$webconfig['watermark_tm'],$webconfig['text_word']);
            }


            $fileurl = '/'.$file['fullName'];

            $body = str_replace($imgUrl, $fileurl, $body);
            //添加图片进数据库
            $filesize = round(filesize(APP_PATH.$file['fullName'])/1024,2);
            M('pictures')->add(['litpic'=>'/'.$file['fullName'],'addtime'=>time(),'userid'=>$_SESSION['admin']['id'],'size'=>$filesize,'filetype'=>strtolower($pix),'tid'=>$tid,'molds'=>$molds]);

        }


        return $body;
    }
}
