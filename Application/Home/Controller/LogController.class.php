<?php
namespace Home\Controller;
use Think\Controller;
class LogController extends Controller {

	public function __construct(){
		parent::__construct();
		islogin();
		$this->user=session('user');
	}

    public function index(){
        $this->display('./buylog');
    }
    public function buy(){
    	if($this->user['role']!=1&&$this->user['role']!=2) $this->error('你没有该权限');
    	$this->display('./buylog');
    }
    public function sale(){
    	if($this->user['role']!=1&&$this->user['role']!=3) $this->error('你没有该权限');
    	$this->display('./buylog');
    }
}