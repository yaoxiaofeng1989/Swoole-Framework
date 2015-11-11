# Swoole-Framework
=============
一个轻量级的Swoole框架，引入依赖注入和回调绑定，简洁的路由分发，类似phalcon的缓存封装，类似TP的控制器和模型

2015-11-11 （光棍节更新）
=============
添加共享服务(注册的服务会以单例模式运行，第一次调用之后，第二次直接返回实例结果)
-------------
使用方法：
```php
// 注入redis缓存服务
$di->setShared('redis' , function(){
	$redis = new \Swoole\Cache\Redis(array(
		'host'		=>	'127.0.0.1',
		'port'		=>	6379,
		'lifetime' 	=>	10
		));
	return $redis;
});
```


2015-11-09
=============
添加服务容器
-------------
使用方法：
```php
// 注入redis缓存服务
$di->set('redis' , function(){
	$redis = new \Swoole\Cache\Redis(array(
		'host'		=>	'127.0.0.1',
		'port'		=>	6379,
		'lifetime' 	=>	10
		));
	return $redis;
});
```