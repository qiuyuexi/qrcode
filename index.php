<?php
require_once("phpqrcode/phpqrcode.php"); 

class code {

	private $text;//二维码里面的参数

	private $outfile;//默认为false 表示不生成文件，直接返回图片

	private $level;//图片容错率 L,M,Q,H  低到高

	private $size;//图片的大小

	private $margin;//二维码的空白区域大小

	private $saveandprint;//保存并输出

	private $logo ;//logo的地址

	public function __construct($text = NULL,$outfile = NULL,$level = NULL,$size = NULL,$margin = NULL,$saveandprint = NULL){

		$this->text = isset($text) ? $text  : 'http://www.baidu.com';

		$this->outfile = isset($outfile) ? $outfile : false;

		$this->level = isset($level) ? $level : 'H';
 	
 		$this->size  = isset($size) ? $size : '10';

 		$this->margin = isset($margin) ? $margin : 4;

 		$this->saveandprint = ($this->outfile == false) ? false : 2;

		$this->logo = 'log.jpg';
	}
 

 	public function showQrcode(){
 		//echo $this->text;
 		QRcode::png($this->text,$this->outfile,$this->level,$this->size);
 	}

 	
 	//图片和logo 合并
 	public function downLoadQrcode(){
 		$this->file_name = __DIR__.'/'.time().'.png';
 		
 		//保存二维码到本地，目标图像
 		QRcode::png($this->text,$this->file_name,$this->level, $this->size, 2); 
 		$qrcode = imagecreatefromstring(file_get_contents($this->file_name));
 		$qr['width'] = imagesx($qrcode);//宽
 		$qr['height'] = imagesy($qrcode);//高
 		//var_dump($qr);

 		//读取logo 的长宽,源图像
 		$logoo = imagecreatefromstring(file_get_contents($this->logo));
 		$logo['width'] = imagesx($logoo);//宽
 		$logo['height'] = imagesy($logoo);//高
 		//var_dump($logo);

 		//把logo 写到二维码的中间
 		$dst_x = ($qr['width'] - $qr['width'] / 5) / 2;//目标的x坐标

 		$dst_y = ($qr['width'] - $qr['width'] / 5) / 2;//目标的y坐标
 		
 		$src_x = 0;//源的x坐标
 		
 		$src_y = 0;//源的y坐标
 		
 		$dst_w = $qr['width'] / 5 ;//目标宽度
 		
 		$dst_h =  $qr['width'] / 5;//目标高度
 		
 		$src_w = $logo['width'];//源图像的宽度
 		
 		$src_h = $logo['height'];//源图像的高度
 		
 		imagecopyresampled($qrcode,$logoo,$dst_x,$dst_y,$src_x,$src_y,$dst_w,$dst_h,$src_w,$src_h);
 		
 		imagepng($qrcode, 'helloweixin.png');   
		
		echo '<img src="helloweixin.png">';   
 	}
}
$text = isset($_GET['text']) ? $_GET['text'] : '没东西啊';
$a = new code($text);
$a->downLoadQrcode();
