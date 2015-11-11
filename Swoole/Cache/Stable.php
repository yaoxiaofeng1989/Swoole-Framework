<?php
namespace Swoole\Cache;

use Swoole\Interfaces\Cache;
use Swoole\Base\CacheBase;
use Swoole\System\Console;

class Stable extends CacheBase implements Cache{
	// 设置必要配置参数
    protected $must_config = array('dbsize' => 1 , 'lifetime' => 1);

    /**
     * 连接
     * @return [type] [description]
     */
	public function connect(){
		// 判断是否存在redis
        if (!class_exists('swoole_table')) {
            return Console::error("Class swoole_table not exists");
        }
		$this->cache_object = new \swoole_table($this->config['dbsize']);
		$this->cache_object->column('time', \swoole_table::TYPE_INT, 4);       //1,2,4,8
		$this->cache_object->column('data', \swoole_table::TYPE_STRING, 65535);
		$this->cache_object->create();
	}

	/**
     * 缓存内容
     * @param  string $key   缓存的key
     * @param  string $value 缓存值
     * @return boolean       
     */
	public function save($key , $value , $lifetime = null){
		// 判断是否为数据
        $value = is_array($value) ? json_encode($value) : $value;
        // 设置缓存过期截止时间
        $lifetime = $lifetime ? time() + $lifetime : time() + $this->config['lifetime'];
        // 缓存
		return $this->cache_object->set($key , array('time'=>$lifetime , 'data' => base64_encode($value)));
	}

	/**
     * 获取一个key的value
     * @param  string $key 键名
     * @return mix    [string | array]      
     */
	public function get($key){
		if($row = $this->cache_object->get($key)){
			// 判断过期时间
        	$ttl = $row['time'] - time();
        	if($ttl >= 0){
        		$data = base64_decode($row['data']);
        		$content = json_decode($data , true);
            	return $content == null ? $data : $content;
        	}else{
        		return false;
        	}
		}
		return false;
	}

	/**
     * 删除一个key
     * @param  string $key 键名
     * @return boolean     
     */
    public function delete($key){    
        return $this->cache_object->del($key);  
    }


    /**
     * 清空当前数据库中所有的key
     * @return boolean 
     */
    public function flush(){   
        
    }


    /**
     * 判断key是否存在
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function exists($key){
        return $this->cache_object->exist($key);
    }


    /**
     * 获取key的生存时间
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getLifetime($key){
        if($row = $this->cache_object->get($key)){
        	$ttl = $row['time'] - time();
        	if($ttl < 0){
        		return -1;
        	}else{
        		return $ttl;
        	}
        }
        return -1;
    }
}