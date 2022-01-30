<?php

/**
 * ************
 * SessionRedis  重写session机制
 * 将session存到redis数据库中
 * ************
 */
 
 /**
	使用
	
	$session = new SessionRedis(array(
		'HOST'=>'127.0.0.1',
		'PORT'=>6379
	));
	session_set_save_handler($session,true);
	//session_id($_COOKIE['PHPSESSID']);
	session_start();
	$_SESSION['name'] = 'onmpw';
	var_dump($_SESSION);
 
 **/
class SessionRedis implements SessionHandlerInterface
{

    private $save_handle = '';
    private $reconnect = false;  //是否重新连接  默认不重新连接
    private $handle = '';
    private $auth = null;   //是否有用户验证，默认无密码验证。如果不是为null，则为验证密码
    private $prefix = 'FrPHP_SESSION';
    private $expire = null;
    private $config = array(
        'SAVE_HANDLE' => 'Redis',
        'HOST' => '127.0.0.1',
        'PORT' => 6379,
        'AUTH' => null,    //是否有用户验证，默认无密码验证。如果不是为null，则为验证密码
        'TIMEOUT' => 0,   //连接超时
        'RESERVED' => null,
        'RETRY_INTERVAL' => 100,  //单位是 ms 毫秒
        'RECONNECT' => false, //连接超时是否重连  默认不重连
        'EXPIRE'=>1800  //session垃圾回收时间 单位s  在此也是session的过期时间
    );

    public function __construct($config = array())
    {

        if (!empty($config)) $this->config = array_merge($this->config, $config);

        $this->parseConfig();
    }

    public function parseConfig()
    {

        $this->save_handle = $this->config['SAVE_HANDLE'];

        $this->reconnect = $this->config['RECONNECT'];

        $this->auth = $this->config['AUTH'];

        $this->expire= $this->config['EXPIRE'];
    }

    /**
     * Redis服务器连接
     * @param string $host 主机地址
     * @param number $port 连接端口
     * @param number $timeout 连接超时时间
     * @param string $reserved
     * @param number $retry_interval
     *
     * @throws RedisException
     *
     * @return boolean
     */
    public function redisConnect($host = '127.0.0.1', $port = 6379, $timeout = 0, $reserved = null, $retry_interval = 100)
    {
        //实例化Redis对象
        try {

            $this->handle = new \Redis();

        } catch (RedisException $e) {

            throw $e;

        }


        /*
         * 判断是否重新连接
         */
        if (!$this->reconnect) {

            $this->handle->connect($host, $port, $timeout);

        } else {

            $this->handle->connect($host, $port, $timeout, $reserved, $retry_interval);

        }
        /*
         * 判断是否有密码验证
         * 有密码验证则进行验证才可继续后续的操作
         */
        if (!is_null($this->auth)) {

            $this->handle->auth($this->auth);

        }
        return true;
    }

    /**
     * 解析连接
     */
    private function parseConnect()
    {

        if ($this->save_handle == 'Redis') {

            $this->redisConnect($this->config['HOST'], $this->config['PORT'], $this->config['TIMEOUT'], $this->config['RESERVED'], $this->config['RETRY_INTERVAL']);

        }

    }

    /**
     * 当session_start()函数被调用的时候该函数被触发
     *
     * @see SessionHandlerInterface::open()
     */
    public function open($save_path, $name)
    {
        /*
         * 首先连接服务器
         */
        $this->parseConnect();
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
        /*
         * 根据sessionId 构造键名
         */
        $key = $this->prefix . ':' . $session_id;

        //读取当前sessionid下的data数据
        $res = $this->handle->hGet($key, 'data');
        //读取完成以后 更新时间，说明已经操作过session
        $time=time();
        $this->handle->hSet($key, 'last_time', $time);
        return (string)$res;

    }

    /**
     * 将session的数据写入到session的存储空间内。
     * 当session准备好存储和关闭的时候调用该函数
     *
     * @see SessionHandlerInterface::write()
     */
    public function write($session_id, $session_data)
    {
        /*
         * 根据sessionId 构造键名
         */
        $key = $this->prefix . ':' . $session_id;
        $time = time();
        //查看该键内容是否存在
        if (!$this->handle->exists($key)) {
            /*
             * 不存在则插入新的内容
             * 插入最后更新时间
             */
            $this->handle->hset($key, 'last_time', $time);
        } else {
            /*
             * 存在，则更新该键值
             */
            $this->handle->hMset($key, array('last_time' => $time, 'data' => $session_data));
        }
        return true;

    }

    /**
     * 销毁session
     *
     * @see SessionHandlerInterface::destroy()
     */
    public function destroy($session_id)
    {
        /*
         * 根据sessionId 构造键名
         */
        $key = $this->prefix . ':' . $session_id;
        $this->handle->hDel($key, 'data');
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
        /*
         * 取出所有的 带有指定前缀的键
         */
        $keys = $this->handle->keys($this->prefix . '*');

        $now = time(); //取得现在的时间
        //垃圾回收
        foreach ($keys as $key) {
            //取得当前key的最后更新时间
            $last_time = $this->handle->hGet($key, 'last_time');
            /*
             * 查看当前时间和最后的更新时间的时间差是否超过最大生命周期
             */
            if (($now - $last_time) > $this->expire) {
                //超过了最大生命周期时间 则删除该key
                $this->handle->del($key);
            }

        }

    }
}










