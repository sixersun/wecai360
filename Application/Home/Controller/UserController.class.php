<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {

	public function __construct(){
		parent::__construct();
		islogin();
	}
	private $key='84914c0005333d5ae8cdb1e1d4983244';
	
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