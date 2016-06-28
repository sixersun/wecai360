<?php
namespace Home\Controller;
use Think\Controller;
class ShoppingController extends Controller {
	public function __construct(){
		parent::__construct();
		islogin();
		$this->user=session('user');
	}
    public function index(){
    	$user=session('user');
    	$user_id=$user['id'];
    	$list=D('Shopping')->getshopping($user_id);
    	$this->assign('list',$list);
    	$this->display('./shopping');
    }
    public function in(){
    	if(!$_POST) return false;
    	$data=I('post.data');
    	$arr=array(
    		'user_id'=>$this->user['id'],
    		'pro_id'=>$data['id'],
    		'code'=>$data['code'],
    		'proname'=>$data['proname'],
    		'pro_num'=>$data['num']?$data['num']:1,
            'supplier'=>$data['supplier'],
    		'price'=>$data['price'],
            'model'=>$data['model'],
            'note'=>$data['note'],
            'img'=>$data['img'],
    		'status'=>1,
    		);
    	if(!D('shopping')->saveshooping($arr)){
    		ajax(-1,'添加到购物车失败',null);
    	}
    	ajax(1,'添加到购物车',null);
    }
}