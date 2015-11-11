<?php
namespace Lib\Net;

use \Lib\Protocols\JsonProtocol;

Class Client{

	protected $client;

	Public function __construct($host,$port,$timeout = 1,$flag = 0){
		$this->client = new \swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
		//设置事件回调函数
		$this->client->on("error",   array($this , 'onError'));
		$this->client->on("close",   array($this , 'onClose'));
		//发起网络连接
		$this->client->connect($host, $port, $timeout, $flag);
		$this->isConnected() or die;
	}

	//错误
	Public function onError($client){
		echo "Connect failed". PHP_EOL;
	}

	//关闭连接
	Public function onClose($client){
		echo "Connection close". PHP_EOL;
	}

	//发送数据
	Public function send($data){
		$data = JsonProtocol::encode($data);

		echo $data.PHP_EOL;
		$this->client->send($data);
	}

	//接收数据
	Public function receive($size = 65535, $waitall = 0){
		$data = $this->client->recv($size,$waitall);
		return JsonProtocol::decode($data);
	}

	//检测是否已经连接
	private function isConnected(){
		return $this->client->isConnected();
	}
}