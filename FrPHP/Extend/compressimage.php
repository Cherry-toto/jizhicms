<?php

class compressimage{
private $src;
private $imageinfo;
private $image;
public $percent = 0.1;
public $ispngcompress = 0;
public $quality = 75;
public function __construct($src){
$this->src = $src;
}
/**打开图片*/
public function openImage(){
list($width, $height, $type, $attr) = getimagesize($this->src);
$this->imageinfo = array(
'width'=>$width,
'height'=>$height,
'type'=>image_type_to_extension($type,false),
'attr'=>$attr
);
$fun = "imagecreatefrom".$this->imageinfo['type'];
$this->image = $fun($this->src);
}
/**操作图片*/
public function thumpImage(){
$new_width = $this->imageinfo['width'] * $this->percent;
$new_height = $this->imageinfo['height'] * $this->percent;
$image_thump = imagecreatetruecolor($new_width,$new_height);//将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
imagecopyresampled($image_thump,$this->image,0,0,0,0,$new_width,$new_height,$this->imageinfo['width'],$this->imageinfo['height']);
imagedestroy($this->image);
$this->image = $image_thump;
} 
/**输出图片*/
public function showImage(){
header('Content-Type: image/'.$this->imageinfo['type']);
$funcs = "image".$this->imageinfo['type'];
$funcs($this->image);
}
/**保存图片到硬盘*/
public function saveImage($name){
$funcs = "image".$this->imageinfo['type'];

if($this->imageinfo['type']=='gif'){
	//不压缩
	$funcs($this->image,$name);
}else if($this->imageinfo['type']=='png'){
    if($this->ispngcompress==1){
        //其余一律按jpg格式压缩
	    imagejpeg($this->image,$name,$this->quality);
    }else{
        $funcs($this->image,$name);
    }
	
}else{
    imagejpeg($this->image,$name,$this->quality);
}
//$funcs($this->image,$name);
//imagejpeg($this->image,$name);

}
/**销毁图片*/
public function __destruct(){
imagedestroy($this->image);
}



}

