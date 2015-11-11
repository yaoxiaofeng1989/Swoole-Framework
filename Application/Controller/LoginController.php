<?php
namespace Application\Controller;
/**
 * 验证用户信息
 * User: Kp
 * Date: 2015/10/20
 * Time: 9:31
 */
class LoginController extends BaseController {

    public function authAction(){
    	if($this->di->redis === $this->di->redis){
    		echo 'ok';
    	}
    	else{
    		echo 'no';
    	}
    }
}