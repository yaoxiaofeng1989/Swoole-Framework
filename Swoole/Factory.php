<?php
/**
 * 工厂类
 */
namespace Swoole;

class Factory {
    // 设置共享服务容器
    protected static $shared = array();
	// 设置服务容器
    protected static $registry = array();
 	// 设置允许swoole回调的服务
 	protected static $allowCallback = array('onStart','onWorkerStart','onConnect','onReceive','onClose','onShutdown','onTimer','onTask','onFinish');

    /**
     * 添加一个共享服务到registry数据中
     * @param [type] $name    依赖标识
     * @param [type] $resolve 一个匿名函数用来创建实例
     * @return void
     */
    public function setShared($name, $resolve){
        static::$shared[$name] = null;
        static::$registry[$name] = $resolve;
    }

    /**
     * 添加一个resolve到registry数组中
     * @param  string $name 依赖标识
     * @param  object $resolve 一个匿名函数用来创建实例
     * @return void
     */
    public function set($name, $resolve){
    	static::$registry[$name] = $resolve;
    }
 
    /**
     * 返回一个实例
     * @param  string $name 依赖的标识
     * @return mixed
     */
    public function get($name){
        if (static::registered($name)){
            if(static::shared($name)){
                if($sh = static::$shared[$name]){
                    return $sh; 
                }else{
                    $service = static::$registry[$name];
                    if(in_array($name , static::$allowCallback)){
                        return $service;    
                    }else{
                        $sh = static::$shared[$name] = $service();
                        return $sh;
                    }
                }
            }else{
                $service = static::$registry[$name];
                if(in_array($name , static::$allowCallback)){
                    return $service;    
                }else{
                    return $service();
                }
            }
        }
        throw new \Exception('Nothing registered with that name, fool.');
    }

    public function __set($name, $resolve){
    	$this->set($name, $resolve);
    }

    public function __get($name){
    	return $this->get($name);
    }

    /**
     * 查询某个依赖实例是否存在
     * @param  string $name id
     * @return bool 
     */
    public function registered($name){
    	return array_key_exists($name, static::$registry);
    }

    /**
     * 查询某个实例是否为共享实例
     * @param  string $name id
     * @return bool
     */
    public function shared($name){
        return array_key_exists($name, static::$shared);
    }
}