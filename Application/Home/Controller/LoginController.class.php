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
    public function createnew(){
    	$this->display('./createnew');
    }
    public function create(){
    	if(!$_POST) return false;
    	$password=I('post.password');
    	$_password=hash('md5',$key.$password);
    	$role=I('post.role');
    	$role_name=C('ROLE_NAME.'.$role);
    	$data=array(
    		'role'=>I('post.role'),
    		'role_name'=>$role_name,
    		'true_name'=>I('post.true_name'),
    		'tel'=>I('post.tel'),
    		'password'=>$_password,
    		'note'=>I('post.note'),
    		);
    	if(I('post.id')){
    		$data['id']=I('post.id');
    		$return=D('user')->up($data);
    	}else{
    		$return=D('user')->create($data);	
    	}
    	if(!$return) $this->error('出现错误，请联系管理员');
    	$this->success('Success');
    	

    }
    public function edit(){
    	$id=I('path.2');
    	if(!is_numeric($id)) $this->error('参数错误');
    	$role=session('user.role');
    	$user_id=session('user.id');
    	if($role!=1&&$user_id!=$id) $this->error('用户权限错误');
    	$return=D('user')->getuser($id);
    	if(!$return) $this->error('用户不存在');
    	$this->assign('row',$return);
    	$this->display('./createnew');
    }
}