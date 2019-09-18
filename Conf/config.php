<?php return array (
  'db' => 
  array (
    'host' => '127.0.0.1',
    'dbname' => '',
    'username' => '',
    'password' => '',
    'prefix' => 'jz_',
    'port' => '3306',
  ),
  'redis' =>
  array(
        'SAVE_HANDLE' => 'Redis',
        'HOST' => '127.0.0.1',
        'PORT' => 6379,
        'AUTH' => null,    //是否有用户验证，默认无密码验证。如果不是为null，则为验证密码
        'TIMEOUT' => 0,   //连接超时
        'RESERVED' => null,
        'RETRY_INTERVAL' => 100,  //单位是 ms 毫秒
        'RECONNECT' => false, //连接超时是否重连  默认不重连
        'EXPIRE'=>1800  //session垃圾回收时间 单位s  在此也是session的过期时间
    ),

); ?>