<?php
Class Client{

	protected $client;

	Public function __construct($host,$port){
		$this->client = new swoole_client(SWOOLE_SOCK_TCP , SWOOLE_SOCK_ASYNC);
		//设置事件回调函数
		$this->client->on("error",   array($this , 'onError'));
		$this->client->on("close",   array($this , 'onClose'));
		$this->client->on("receive",   array($this , 'onReceive'));
		$this->client->on("connect",   array($this , 'onConnect'));
		//发起网络连接
		$this->client->connect($host, $port);
		$this->isConnected() or die;
	}

	Public function onError($client){
		echo "Connect failed". PHP_EOL;
	}

	Public function onClose($client){
		echo "Connection close". PHP_EOL;
	}

	public function onConnect($client){
		echo "Connection open". PHP_EOL;
	}

	Public function send($data){
		$this->client->send($data);
	}

	Public function onReceive(swoole_client $client, $data){
		$data = $this->client->recv();
		print_r($data);
	}

	private function isConnected(){
		return $this->client->isConnected();
	}
}

$client = new Client('127.0.0.1',9501);

$data = json_encode(array('code'=>'4001','data'=>array('id'=>1)));

$client->send($data);