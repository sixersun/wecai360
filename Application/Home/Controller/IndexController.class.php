<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

	public function __construct(){
		parent::__construct();
		islogin();
	}
    public function index(){
    	$user=session('user');
    	$this->assign('user',$user);
        $this->display('./index');
    }
}