<?php
namespace Home\Controller;
use Think\Controller;
class ProductController extends Controller {

	public function __construct(){
		parent::__construct();
		islogin();
		$this->user=session('user');
	}

    public function index(){
    	$id=I('path.2');
    	if(!is_numeric($id)) return false;
    	$row=D('Product')->get_product($id);
    	$this->assign('row',$row);
        $this->display('./product');
    }
}