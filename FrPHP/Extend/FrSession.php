<?php

/**
 * ************
 * FrSession类  重写session机制
 * 将session存到redis数据库中
 * ************
 */
 

class FrSession implements SessionHandlerInterface
{

    private $save_handle = '';
    private $prefix = 'frses_';//前缀
    private $expire = null;
	private $save_path = 'cache/tmp';//存储目录
	private $life_time = 1800;//过期时间，单位s  -1表示不过期
    private $config = array(
			
    );

    public function __construct($config = array())
    {

        if (!empty($config)){
			$this->save_path = $config['save_path'];
			$this->life_time = $config['life_time'];
		} 

        
    }
    function checkmkdirs($dir, $mode = 0755)
    {
        if (!is_dir($dir)) {
            $this->checkmkdirs(dirname($dir), $mode);
            return @mkdir($dir, $mode);
        }
        return true;
    }

    /**
     * 当session_start()函数被调用的时候该函数被触发
     *
     * @see SessionHandlerInterface::open()
     */
    public function open($save_path, $name)
    {
      
        return true;

    }

    /**
     * 关闭当前session
     * 当session关闭的时候该函数自动被触发
     *
     * @see SessionHandlerInterface::close()
     */
    public function close()
    {
        return true;
    }

    /**
     * 从session存储空间读取session的数据。
     * 当调用session_start()函数的时候该函数会被触发
     * 但是在session_start()函数调用的时候先触发open函数，再触发该函数
     *
     * @see SessionHandlerInterface::read()
     */
    public function read($session_id)
    {	
        if(!is_dir($this->save_path)){
			$this->checkmkdirs($this->save_path);
		}
        $sfile = $this->save_path.'/'.$this->prefix.$session_id.'.php';
        $res = $this->sesstime($sfile);
		if($res){
			return $res;
		}else{
			return '';
		}

    }

    /**
     * 将session的数据写入到session的存储空间内。
     * 当session准备好存储和关闭的时候调用该函数
     *
     * @see SessionHandlerInterface::write()
     */
    public function write($session_id, $session_data)
    {
        if(!is_dir($this->save_path)){
			$this->checkmkdirs($this->save_path);
		}
        if( !is_readable($this->save_path) ){
            return false;
        }
        $sfile = $this->save_path.'/'.$this->prefix.$session_id.'.php';
		$life_time = ( -1 == $this->life_time ) ? '300000000' : $this->life_time;
		
		$value = '<?php die();?>'.( time() + $life_time ).serialize($session_data);
		$res = file_put_contents($sfile, $value);
		if($res){
			return true;
		}else{
			return false;
		}
    }

    /**
     * 销毁session
     *
     * @see SessionHandlerInterface::destroy()
     */
    public function destroy($session_id)
    {
		$sfile = $this->save_path.'/'.$this->prefix.$session_id.'.php';
		if(file_exists($sfile)){
			return @unlink($sfile);
		}
		return true;
    }

    /**
     * 清除垃圾session，也就是清除过期的session。
     * 该函数是基于php.ini中的配置选项
     * session.gc_divisor, session.gc_probability 和 session.gc_lifetime所设置的值的
     *
     * @see SessionHandlerInterface::gc()
     */
    public function gc($maxlifetime)
    {
        
		$dirName=@opendir($this->save_path);
		while(($file = @readdir($dirName)) !== false){
			if($file!='.' && $file!='..'){
				$this->sesstime($this->save_path.'/'.$file);
			}
		}
		closedir($dirName);

    }
	private function sesstime($sfile){
		if( !is_readable($sfile) ){
			return false;
		}
		$arg_data = file_get_contents($sfile);
		if( substr($arg_data, 14, 10) < time() ){
			@unlink($sfile); 
			return false;
		}
		return unserialize(substr($arg_data, 24));
	}
}










