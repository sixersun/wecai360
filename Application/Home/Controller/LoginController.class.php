<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

	private $key='84914c0005333d5ae8cdb1e1d4983244';

    public function index(){
        $this->display('./login');
    }
    public function login(){
    	$safecode=I('post.safecode');
		if(!$this->check_verify($safecode)){  
		    $this->error("亲，验证码输错了哦！",$this->site_url,3);  
		}  
    	$tel=I('post.tel');
    	if(!is_numeric($tel)&&strlen($tel)!=11) $this->error('帐号格式错误');
    	$data=array(
    		'tel'=>$tel,
    		'password'=>I('post.password')
    		);
    	if(!$return=D('user')->checklogin($data)) $this->error('密码错误');
    	$this->success('登录成功','/home/index',3);
    }
	private function check_verify($code, $id = ""){  
	    $verify = new \Think\Verify();  
	    return $verify->check($code, $id);  
	}  
	public function verify_c(){  
	    $Verify = new \Think\Verify();  
	    $Verify->fontSize = 18;  
	    $Verify->length   = 4;  
	    $Verify->useNoise = false;  
	    $Verify->codeSet = '0123456789';  
	    $Verify->imageW = 150;  
	    $Verify->imageH = 50;  
	    //$Verify->expire = 600;  
	    $Verify->entry();  
	}
}