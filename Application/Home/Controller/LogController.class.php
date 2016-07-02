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
        $id=I('path.2');
        $return=D('Product')->get_product($id);
        $code=$return['code'];
        $list=D('Productbuylog')->get_buy_log($code);
        $this->assign('name',$return['proname']);
        $this->assign('list',$list);
    	$this->display('./buylog');
    }
    public function sale(){
    	if($this->user['role']!=1&&$this->user['role']!=3) $this->error('你没有该权限');
        $id=I('path.2');
        $return=D('Product')->get_product($id);
        $code=$return['code'];
        $list=D('Productsalelog')->get_sale_log($code);
        $this->assign('name',$return['proname']);
        $this->assign('list',$list);
    	$this->display('./buylog');
    }
}