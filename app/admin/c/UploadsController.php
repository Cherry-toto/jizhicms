<?php


namespace app\admin\c;


class UploadsController extends CommonController
{
    function index(){
        
        $filepath = $this->webconf['admin_save_path'];
        $paths = explode('/',$filepath);
        $allowpath = (count($paths)>=2 && strpos($paths[1],'{')===false) ? '/'.$paths[0].'/'.$paths[1].'/' : '/'.$paths[0].'/';
        if(strpos($filepath,'{')===false){
            $filepath.='/{yyyy}/{mm}/{dd}';
        }
        if(strpos($filepath,'rand')===false){
            $filepath.='/{rand:8}';
        }
        //$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
        $CONFIG = [
            /* 上传图片配置项 */
            "imageActionName"=>"uploadimage", /* 执行上传图片的action名称 */
            "imageFieldName"=>"upfile", /* 提交的图片表单名称 */
            "imageMaxSize"=>512000000, /* 上传大小限制，单位B 500M*/
            "imageAllowFiles"=>[".png", ".jpg", ".jpeg", ".gif", ".bmp",".webp"], /* 上传图片格式显示 */
            "imageCompressEnable"=>true, /* 是否压缩图片,默认是true */
            "imageCompressBorder"=>1600, /* 图片压缩最长边限制 */
            "imageInsertAlign"=>"none", /* 插入的图片浮动方式 */
            "imageUrlPrefix"=>"", /* 图片访问路径前缀 */
            "imagePathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
            /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
            /* {time} 会替换成时间戳 */
            /* {yyyy} 会替换成四位年份 */
            /* {yy} 会替换成两位年份 */
            /* {mm} 会替换成两位月份 */
            /* {dd} 会替换成两位日期 */
            /* {hh} 会替换成两位小时 */
            /* {ii} 会替换成两位分钟 */
            /* {ss} 会替换成两位秒 */
            /* 非法字符 \ =>* ? " < > | */
            /* 具请体看线上文档=>fex.baidu.com/ueditor/#use-format_upload_filename */

            /* 涂鸦图片上传配置项 */
            "scrawlActionName"=>"uploadscrawl", /* 执行上传涂鸦的action名称 */
            "scrawlFieldName"=>"upfile", /* 提交的图片表单名称 */
            "scrawlPathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "scrawlMaxSize"=>2048000, /* 上传大小限制，单位B */
            "scrawlUrlPrefix"=>"", /* 图片访问路径前缀 */
            "scrawlInsertAlign"=>"none",

            /* 截图工具上传 */
            "snapscreenActionName"=>"uploadimage", /* 执行上传截图的action名称 */
            "snapscreenPathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "snapscreenUrlPrefix"=>"", /* 图片访问路径前缀 */
            "snapscreenInsertAlign"=>"none", /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            "catcherLocalDomain"=>["127.0.0.1", "localhost", "img.baidu.com"],
            "catcherActionName"=>"catchimage", /* 执行抓取远程图片的action名称 */
            "catcherFieldName"=>"source", /* 提交的图片列表表单名称 */
            "catcherPathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "catcherUrlPrefix"=>"", /* 图片访问路径前缀 */
            "catcherMaxSize"=>2048000, /* 上传大小限制，单位B */
            "catcherAllowFiles"=>[".png", ".jpg", ".jpeg", ".gif", ".bmp" ,".webp"], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            "videoActionName"=>"uploadvideo", /* 执行上传视频的action名称 */
            "videoFieldName"=>"upfile", /* 提交的视频表单名称 */
            "videoPathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "videoUrlPrefix"=>"", /* 视频访问路径前缀 */
            "videoMaxSize"=>1024000000, /* 上传大小限制，单位B，默认1000MB */
            "videoAllowFiles"=>[
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

            /* 上传文件配置 */
            "fileActionName"=>"uploadfile", /* controller里,执行上传视频的action名称 */
            "fileFieldName"=>"upfile", /* 提交的文件表单名称 */
            "filePathFormat"=>"/".$filepath, /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "fileUrlPrefix"=>"", /* 文件访问路径前缀 */
            "fileMaxSize"=>512000000, /* 上传大小限制，单位B，默认500MB */
            "fileAllowFiles"=>[
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            "imageManagerActionName"=>"listimage", /* 执行图片管理的action名称 */
            "imageManagerListPath"=>$allowpath, /* 指定要列出图片的目录 */
            "imageManagerListSize"=>20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix"=>"", /* 图片访问路径前缀 */
            "imageManagerInsertAlign"=>"none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles"=>[".png", ".jpg", ".jpeg", ".gif", ".bmp" ,".webp"], /* 列出的文件类型 */

            /* 列出指定目录下的文件 */
            "fileManagerActionName"=>"listfile", /* 执行文件管理的action名称 */
            "fileManagerListPath"=>$allowpath, /* 指定要列出文件的目录 */
            "fileManagerUrlPrefix"=>"", /* 文件访问路径前缀 */
            "fileManagerListSize"=>20, /* 每次列出文件数量 */
            "fileManagerAllowFiles"=>[
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ] /* 列出的文件类型 */
        ];
        $action = $_GET['action'];

        // ===== 安全加固:服务端配置锁定,绝对禁止从客户端请求参数读取 =====
        // CONFIG 数组已硬编码为服务端可信值, Uploader 类直接接收此数组, 不会读取 $_GET / $_POST。
        // 下面清理 $_GET / $_POST / $_REQUEST 中的同名参数是纵深防御, 防止未来代码重构时
        // 有人误将 $_GET 合并进 CONFIG 导致 fileAllowFiles / filePathFormat 等被客户端篡改。
        $lockParams = [
            'imageActionName','imageFieldName','imageMaxSize','imageAllowFiles','imageCompressEnable','imageCompressBorder','imageInsertAlign','imageUrlPrefix','imagePathFormat',
            'scrawlActionName','scrawlFieldName','scrawlPathFormat','scrawlMaxSize','scrawlUrlPrefix','scrawlInsertAlign',
            'snapscreenActionName','snapscreenPathFormat','snapscreenUrlPrefix','snapscreenInsertAlign',
            'catcherLocalDomain','catcherActionName','catcherFieldName','catcherPathFormat','catcherMaxSize','catcherAllowFiles','catcherUrlPrefix',
            'videoActionName','videoFieldName','videoPathFormat','videoMaxSize','videoAllowFiles','videoUrlPrefix',
            'fileActionName','fileFieldName','filePathFormat','fileMaxSize','fileAllowFiles','fileUrlPrefix',
            'imageManagerActionName','imageManagerListPath','imageManagerListSize','imageManagerAllowFiles','imageManagerUrlPrefix',
            'fileManagerActionName','fileManagerListPath','fileManagerListSize','fileManagerAllowFiles','fileManagerUrlPrefix'
        ];
        foreach ($lockParams as $p) {
            unset($_GET[$p]);
            unset($_POST[$p]);
            unset($_REQUEST[$p]);
        }

        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                //$result = include("action_upload.php");
                $result = $this->uploadfile($CONFIG);
                break;

            /* 列出图片 */
            case 'listimage':
                //$result = include("action_list.php");
                $result = $this->listfile($CONFIG);
                break;
            /* 列出文件 */
            case 'listfile':
                // $result = include("action_list.php");
                $result = $this->listfile($CONFIG);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                //$result = include("action_crawler.php");
                $result = $this->catchimage($CONFIG);
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            /*
            {"state":"SUCCESS","url":"\/static\/upload\/20230103\/1672756587221260.jpeg","title":"1672756587221260.jpeg","original":"6.jpeg","type":".jpeg","size":34255}
            */
            echo $result;
        }
    }

    function catchimage($CONFIG){
        set_time_limit(0);

        /* 上传配置 */
        $config = array(
            "pathFormat" => $CONFIG['catcherPathFormat'],
            "maxSize" => $CONFIG['catcherMaxSize'],
            "allowFiles" => $CONFIG['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new \Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars_decode($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));
    }

    function listfile($CONFIG){

        /* 判断类型 */
        switch ($_GET['action']) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        // 安全加固: 对 CONFIG 里的相对路径做目录穿越清理, 防止后台配置被篡改后 .. 生效
        $path = preg_replace('/\.\.[\/\\]/', '', $path);
        $path = preg_replace('/[\/\\]\.\./', '', $path);
        $path = preg_replace('/\.{2,}/', '', $path);

        /* 获取文件列表(拼接后绝对路径) */
        $absPath = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        // 安全加固: 规范化绝对路径并校验必须在 DOCUMENT_ROOT 下
        $absPath = $this->_normalizePath($absPath);
        $docRoot = $this->_normalizePath($_SERVER['DOCUMENT_ROOT']);
        if (substr($docRoot, -1) !== DIRECTORY_SEPARATOR) $docRoot .= DIRECTORY_SEPARATOR;
        if (strpos($absPath, $docRoot) !== 0) {
            return json_encode(["state" => "非法路径", "list" => [], "start" => 0, "total" => 0]);
        }

        $files = $this->getfiles($absPath, $allowFiles, $docRoot);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $files=$this->array_sort($files,'mtime','desc');
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;


    }


    /**
     * 遍历获取目录下的指定类型的文件
     * @param string $path 绝对路径(已规范化)
     * @param string $allowFiles 正则里用的扩展名片段
     * @param string $docRootNormalized 规范化后的 DOCUMENT_ROOT, 用于边界校验
     * @param array $files
     * @return array
     */
    function getfiles($path, $allowFiles, $docRootNormalized = '', &$files = array())
    {
        // 安全加固: 递归入口处做路径边界校验, 防止任何情况下穿越到 DOCUMENT_ROOT 外
        if ($docRootNormalized !== '') {
            $normalized = $this->_normalizePath($path);
            if (substr($normalized, -1) !== DIRECTORY_SEPARATOR) $normalized .= DIRECTORY_SEPARATOR;
            if (strpos($normalized, $docRootNormalized) !== 0) {
                return null;
            }
        }

        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $docRootNormalized, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    /**
     * 跨平台路径规范化: 统一分隔符 + 解析 . 和 .. + 消除重复分隔符
     */
    private function _normalizePath($path)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $path = preg_replace('/' . preg_quote(DIRECTORY_SEPARATOR, '/') . '{2,}/', DIRECTORY_SEPARATOR, $path);

        $isAbsolute = (strpos($path, DIRECTORY_SEPARATOR) === 0) ||
                      (preg_match('/^[A-Za-z]:\\\\/', $path) === 1);
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $stack = [];
        foreach ($parts as $part) {
            if ($part === '' || $part === '.') continue;
            if ($part === '..') {
                if (!empty($stack) && end($stack) !== '..') array_pop($stack);
                continue;
            }
            $stack[] = $part;
        }

        $result = ($isAbsolute ? DIRECTORY_SEPARATOR : '') . implode(DIRECTORY_SEPARATOR, $stack);
        if (preg_match('/^([A-Za-z]):$/', $result, $m)) {
            $result = $m[1] . ':' . DIRECTORY_SEPARATOR;
        }
        return $result;
    }

    function array_sort($array,$row,$type){
        $array_temp = array();
        $arr=array();
        foreach($array as $v){
            $array_temp[$v[$row]] = $v;
        }
        if($type == 'asc'){
            ksort($array_temp);
        }elseif($type='desc'){
            krsort($array_temp);
        }else{
        }
        $i=0;
        foreach ($array_temp as $vd){
            $arr[$i]=$vd;
            $i++;
        }
        return $arr;
    }

    function uploadfile($CONFIG){

        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => $CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $CONFIG['filePathFormat'],
                    "maxSize" => $CONFIG['fileMaxSize'],
                    "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new \Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
        return json_encode($up->getFileInfo());



    }
}