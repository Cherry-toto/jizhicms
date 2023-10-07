<?php

/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class Uploader
{
    private $fileField; //文件域名
    private $file; //文件上传对象
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($fileField, $config, $type = "upload")
    {
        $this->webconf = webConf();
        $this->fileField = $fileField;
        $this->config = $config;
        $this->type = $type;
        if ($type == "remote") {
            $this->saveRemote();
        } else if($type == "base64") {
            $this->upBase64();
        } else {
            $this->upFile();
        }

    }
    /**
     * 图片加水印
     * $source  string  图片资源
     * $target  string  添加水印后的名字
     * $w_pos   int     水印位置安排（1-10）【1:左头顶；2:中间头顶；3:右头顶...值空:随机位置】
     * $w_img   string  水印图片路径
     * $w_text  string  显示的文字
     * $w_font  int     字体大小
     * $w_color string  字体颜色
     */
    public function watermarkImg($source, $target = '', $w_pos = '', $w_img = '', $w_text = '',$w_font = 10, $w_color = '#CC0000') {
        $this->w_img = '../watermark.png';//水印图片
        $this->w_pos = 8;
        $this->w_minwidth = 400;//最少宽度
        $this->w_minheight = 200;//最少高度
        $this->w_quality = 80;//图像质量
        $this->w_pct = 60;//透明度
        
        $w_pos = $w_pos ? $w_pos : $this->w_pos;
        $w_img = $w_img ? APP_PATH.$w_img : APP_PATH.$this->w_img;
        if(!$this->check($source)) return false;
        if(!$target) $target = $source;
        $source_info = getimagesize($source);//图片信息
        $source_w  = $source_info[0];//图片宽度
        $source_h  = $source_info[1];//图片高度
        if($source_w < $this->w_minwidth || $source_h < $this->w_minheight) return false;
        switch($source_info[2]) { //图片类型
            case 1 : //GIF格式
                $source_img = imagecreatefromgif($source);
                break;
            case 2 : //JPG格式
                $source_img = imagecreatefromjpeg($source);
                break;
            case 3 : //PNG格式
                $source_img = imagecreatefrompng($source);
                //imagealphablending($source_img,false); //关闭混色模式
                imagesavealpha($source_img,true); //设置标记以在保存 PNG 图像时保存完整的 alpha 通道信息（与单一透明色相反）
                break;
            default :
                return false;
        }
        if(!empty($w_img) && file_exists($w_img)) { //水印图片有效
            $ifwaterimage = 1; //标记
            $water_info  = getimagesize($w_img);
            $width    = $water_info[0];
            $height    = $water_info[1];
            switch($water_info[2]) {
                case 1 :
                    $water_img = imagecreatefromgif($w_img);
                    break;
                case 2 :
                    $water_img = imagecreatefromjpeg($w_img);
                    break;
                case 3 :
                    $water_img = imagecreatefrompng($w_img);
                    imagealphablending($w_img,false);
                    imagesavealpha($w_img,true);
                    break;
                default :
                    return;
            }
        }else{
            $ifwaterimage = 0;
            $temp = imagettfbbox(ceil($w_font*2.5), 0, '../../texb.ttf', $w_text); //imagettfbbox返回一个含有 8 个单元的数组表示了文本外框的四个角
            $width = $temp[2] - $temp[6];
            $height = $temp[3] - $temp[7];
            unset($temp);
        }
        
        switch($w_pos) {
            case 1:
                $wx = 5;
                $wy = 5;
                break;
            case 2:
                $wx = ($source_w - $width) / 2;
                $wy = 0;
                break;
            case 3:
                $wx = $source_w - $width;
                $wy = 0;
                break;
            case 4:
                $wx = 0;
                $wy = ($source_h - $height) / 2;
                break;
            case 5:
                $wx = ($source_w - $width) / 2;
                $wy = ($source_h - $height) / 2;
                break;
            case 6:
                $wx = $source_w - $width;
                $wy = ($source_h - $height) / 2;
                break;
            case 7:
                $wx = 0;
                $wy = $source_h - $height;
                break;
            case 8:
                $wx = ($source_w - $width) / 2;
                $wy = $source_h - $height;
                break;
            case 9:
                $wx = $source_w - ($width+5);
                $wy = $source_h - ($height+5);
                break;
            case 10:
                $wx = rand(0,($source_w - $width));
                $wy = rand(0,($source_h - $height));
                break;
            default:
                $wx = rand(0,($source_w - $width));
                $wy = rand(0,($source_h - $height));
                break;
        }
        
        if($ifwaterimage) {
            if($water_info[2] == 3) {
                imagecopy($source_img, $water_img, $wx, $wy, 0, 0, $width, $height);
            }else{
                imagecopymerge($source_img, $water_img, $wx, $wy, 0, 0, $width, $height, $this->w_pct);
            }
        }else{
            if(!empty($w_color) && (strlen($w_color)==7)) {
                $r = hexdec(substr($w_color,1,2));
                $g = hexdec(substr($w_color,3,2));
                $b = hexdec(substr($w_color,5));
            }else{
                return;
            }
            imagestring($source_img,$w_font,$wx,$wy,$w_text,imagecolorallocate($source_img,$r,$g,$b));
        }
        
        switch($source_info[2]) {
            case 1 :
                imagegif($source_img, $target);
                //GIF 格式将图像输出到浏览器或文件(欲输出的图像资源, 指定输出图像的文件名)
                break;
            case 2 :
                imagejpeg($source_img, $target, $this->w_quality);
                break;
            case 3 :
                imagepng($source_img, $target);
                break;
            default :
                return;
        }
        
        if(isset($water_info)){
            unset($water_info);
        }
        if(isset($water_img)) {
            imagedestroy($water_img);
        }
        unset($source_info);
        imagedestroy($source_img);
        return true;
    }
    public function watermark($title,$path){
        
        // 图片路径
        $imagePath = $path;
        // 文字水印内容
        $text = $title;
        // 每行文字数
        $charsPerLine = $this->webconf['text_num'] ?: 10;
        // 文字大小
        $fontSize = $this->webconf['text_size'] ?: 24;
        // 文字行高
        $lineHeight = $this->webconf['text_h'] ?: 34;
        // 文字间距
        $letterSpacing = $this->webconf['text_m'] ?: 2;
        // 文字颜色（RGB格式）
        $color = [$this->webconf['text_rgb1'] ?: 255, $this->webconf['text_rgb2'] ?: 255, $this->webconf['text_rgb3'] ?: 255];
        // 文字字体路径
        $fontPath = $this->webconf['text_font'] ? APP_PATH.'static/common/'.$this->webconf['text_font']:APP_PATH.'static/common/simsun.ttf';
        // 文字水印位置（1-9，左上到右下）
        $position = $this->webconf['text_wz'] ?: 5;
        
        // 创建图像资源
        //$image = imagecreatefromjpeg($imagePath);
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
        //echo $imageWidth.'-'.$imageHeight.'<br>';
        // 计算文字宽度和高度
        $textBoundingBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        $textHeight = $textBoundingBox[1] - $textBoundingBox[7];
        //echo $textWidth.'-'.$textHeight.'<br>';
        
        
        // 处理文字水印内容并自动换行
        $lines = [];
        $line = '';
        //$chars = mb_str_split($text);
        $chars = $this->smb_str_split($text);
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
        $x = $x + $this->webconf['text_x'];
        $y = $y + $this->webconf['text_y'];
        
        foreach ($newlines as $line) {
            ///echo '('.$x.','.$y.')<br>';
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $line);
            $y += $lineHeight;
            
            
        }
        //var_dump($newlines);
        
        // 输出图像
        //header('Content-Type: image/jpeg');
        //imagejpeg($image);
        $p = explode('.',$imagePath);
        $pic = end($p);
        // 生成新的图像文件名
        
        $source = 'static/upload/images/'.date('YmdHis').rand(1000,9999).'.'.$pic; // 替换为你想要保存的图像文件路径和文件名
        $source = $imagePath; // 替换为你想要保存的图像文件路径和文件名
        $newImagePath = $source;
        
        // 保存图像到文件
        //imagejpeg($image, $newImagePath);
        //imagepng($image, $newImagePath);
        //imagegif($image, $newImagePath);
        
        if(stripos($imagePath,'.png')!==false){
            imagepng($image, $newImagePath);
        }else if(stripos($imagePath,'.gif')!==false){
            imagegif($image, $newImagePath);
        }else{
            imagejpeg($image, $newImagePath);
        }
        
        
        // 释放资源
        imagedestroy($image);
        
        //return '/'.$source;
    }
    
    
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

    public function check($image){
        return extension_loaded('gd') && preg_match("/\.(jpg|jpeg|gif|png)/i", $image, $m) && file_exists($image) && function_exists('imagecreatefrom'.($m[1] == 'jpg' ? 'jpeg' : $m[1]));
    }
    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile()
    {
        $file = $this->file = $_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }

        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }
        
        if(stripos($this->oriName,'.php')!==false || stripos($this->oriName,'.phtml')!==false){
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
            $this->save_files($this->filePath);
        }
    }

    /**
     * 处理base64编码的图片上传
     * @return mixed
     */
    private function upBase64()
    {
        $base64Data = $_POST[$this->fileField];
        $img = base64_decode($base64Data);

        $this->oriName = $this->config['oriName'];
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);


        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }
        if(stripos($this->oriName,'.php')!==false || stripos($this->oriName,'.phtml')!==false){
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }
        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
            $this->save_files($this->filePath);
        }

    }

    /**
     * 拉取远程图片
     * @return mixed
     */
    private function saveRemote()
    {
        $imgUrl = htmlspecialchars($this->fileField);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        //http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
            return;
        }
        if(stripos($imgUrl,'.php')!==false || stripos($imgUrl,'.phtml')!==false){
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }
        preg_match('/(^https*:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            $this->stateInfo = $this->getStateInfo("INVALID_URL");
            return;
        }

        preg_match('/^https*:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 ip 也有可能是域名，先获取 ip
        $ip = gethostbyname($host_without_protocol);
        // 判断是否是私有 ip
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            $this->stateInfo = $this->getStateInfo("INVALID_IP");
            return;
        }
        $fix = strtolower(strrchr($imgUrl, '.'));
        $fix = $fix ?: '.png';
        //检查是否不允许的文件格式
        if (!in_array($fix, $this->config["allowFiles"])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
            return;
        }

        if (!isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
            return;
        }

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

        $this->oriName = $m ? $m[1]:"";
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
            $this->save_files($this->filePath);
        }

    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode)
    {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        $fix = strtolower(strrchr($this->oriName, '.'));
        return $fix ? $fix : '.png';
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->config["pathFormat"];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = mt_rand(1, 100000) . mt_rand(1, 100000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $this->getFileExt();
        return $format . $ext;
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName () {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 获取文件完整路径
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;
        $rootPath = $_SERVER['DOCUMENT_ROOT'];

        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }

        return $rootPath . $fullname;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array($this->getFileExt(), $this->config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ($this->config["maxSize"]);
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "state" => $this->stateInfo,
            "url" => $this->fullName,
            "title" => $this->fileName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize
        );
    }

    //引入系统，并存入数据库
    public function save_files($filename){
        
        if($this->webconf['iswatermark']){
            if($this->webconf['watermark_file']){
                $this->watermarkImg($this->filePath,$this->filePath,$this->webconf['watermark_t'],$this->webconf['watermark_file']);
            }else{
                $this->watermark($this->webconf['watermark_word'],$this->filePath);
            }
            
        }
        //新增一条数据
        $filesize = round(filesize($filename)/1024,2);
        $file_url = str_replace($_SERVER['DOCUMENT_ROOT'],'',$filename);
        $userid = $_SESSION['member'] ? $_SESSION['member']['id'] : ($_SESSION['admin'] ? $_SESSION['admin']['id'] : 0);
        M('pictures')->add(['litpic'=>$file_url,'addtime'=>time(),'userid'=>$userid,'size'=>$filesize,'filetype'=>str_replace('.','',$this->fileType)]);

    }

}