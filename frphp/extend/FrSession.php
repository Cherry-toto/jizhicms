<?php

/**
 * ************
 * FrSession类  重写session机制
 * 将session分目录存储到文件系统中
 * ************
 */
 

class FrSession implements SessionHandlerInterface
{

    private $save_handle = '';
    private $prefix = 'frses_';//前缀
    private $expire = null;
	private $save_path = 'cache/tmp';//存储根目录
	private $life_time = 1800;//过期时间，单位s  -1表示不过期
	private $dir_count = 64;//分目录数量
	private $max_files = 500;//每目录最大session文件数，超出后清理最旧的
    private $config = array(
			
    );

    public function __construct($config = array())
    {

        if (!empty($config)){
			if (isset($config['save_path'])) {
				$this->save_path = $config['save_path'];
			}
			if (isset($config['life_time'])) {
				$this->life_time = $config['life_time'];
			}
			if (isset($config['dir_count'])) {
				$this->dir_count = max(1, (int)$config['dir_count']);
			}
			if (isset($config['max_files'])) {
				$this->max_files = max(1, (int)$config['max_files']);
			}
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

	private function sanitizeId($id)
	{
		return str_replace(['..', '/', '\\'], '', $id);
	}

	private function getSubDirName($session_id)
	{
		$index = crc32($session_id) % $this->dir_count;
		$pad = max(2, strlen((string)($this->dir_count - 1)));
		return str_pad((string)$index, $pad, '0', STR_PAD_LEFT);
	}

	private function getSessionDir($session_id)
	{
		$dir = $this->save_path . '/' . $this->getSubDirName($session_id);
		if (!is_dir($dir)) {
			$this->checkmkdirs($dir);
		}
		return $dir;
	}

	private function getSessionFile($session_id)
	{
		$session_id = $this->sanitizeId($session_id);
		return $this->getSessionDir($session_id) . '/' . $this->prefix . $session_id . '.php';
	}

	private function cleanDirIfNeeded($dir)
	{
		if (!is_dir($dir) || !is_readable($dir)) {
			return;
		}
		$files = glob($dir . '/' . $this->prefix . '*.php');
		if ($files === false || count($files) <= $this->max_files) {
			return;
		}
		$fileStats = array();
		foreach ($files as $file) {
			$fileStats[] = array('path' => $file, 'mtime' => @filemtime($file));
		}
		usort($fileStats, function ($a, $b) {
			return $a['mtime'] - $b['mtime'];
		});
		$toDelete = count($files) - $this->max_files;
		for ($i = 0; $i < $toDelete; $i++) {
			@unlink($fileStats[$i]['path']);
		}
	}

    /**
     * 当session_start()函数被调用的时候该函数被触发
     *
     * @see SessionHandlerInterface::open()
     */
    #[\ReturnTypeWillChange]
    public function open($save_path, $name)
    {
		if (!is_dir($this->save_path)) {
			$this->checkmkdirs($this->save_path);
		}
        return true;

    }

    /**
     * 关闭当前session
     * 当session关闭的时候该函数自动被触发
     *
     * @see SessionHandlerInterface::close()
     * @return bool
     */
    #[\ReturnTypeWillChange]
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
     * @return string|false
     */
    #[\ReturnTypeWillChange]
    public function read($id)
    {	
        $sfile = $this->getSessionFile($id);
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
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function write($id, $data)
    {
		$session_id = $this->sanitizeId($id);
        $dir = $this->getSessionDir($session_id);
        if( !is_readable($dir) ){
            return false;
        }
        $sfile = $dir . '/' . $this->prefix . $session_id . '.php';
		$life_time = ( -1 == $this->life_time ) ? '300000000' : $this->life_time;
		
		$value = '<?php die();?>'.( time() + $life_time ).serialize($data);
		$res = file_put_contents($sfile, $value);
		if($res){
			$this->cleanDirIfNeeded($dir);
			return true;
		}else{
			return false;
		}
    }

    /**
     * 销毁session
     *
     * @see SessionHandlerInterface::destroy()
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function destroy($id)
    {
		$sfile = $this->getSessionFile($id);
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
    #[\ReturnTypeWillChange]
    public function gc($maxlifetime)
    {
		if (!is_dir($this->save_path)) {
			return 0;
		}
		$deleted = 0;
		$dirs = @scandir($this->save_path);
		if ($dirs === false) {
			return 0;
		}
		foreach ($dirs as $entry) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}
			$dir = $this->save_path . '/' . $entry;
			if (!is_dir($dir)) {
				if (strpos($entry, $this->prefix) === 0) {
					if (!$this->sesstime($dir)) {
						$deleted++;
					}
				}
				continue;
			}
			$dirHandle = @opendir($dir);
			if ($dirHandle === false) {
				continue;
			}
			while (($file = @readdir($dirHandle)) !== false) {
				if ($file != '.' && $file != '..') {
					if (!$this->sesstime($dir . '/' . $file)) {
						$deleted++;
					}
				}
			}
			closedir($dirHandle);
			$this->cleanDirIfNeeded($dir);
		}
		return $deleted;

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



