<?php
namespace Home\Controller;
use Think\Controller;
class CategoryController extends Controller {
	public function __construct(){
		parent::__construct();
		islogin();
		$this->user=session('user');
	}
    public function index(){
    	$cat=I('get.cat');
        $p=I('get.p')?I('get.p'):1;
        $size=12;
        $limit=($p-1)*$size.','.$size;
    	$return=D('Product')->get_cat_search($cat,$limit);
        $count=D('Product')->get_cat_count($cat);
        $Page = new \Think\Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数(2)
        $Page->nowPage = $p;
        $Page->setConfig('link', '/home/search?cate='.$cat.'&p=[PAGE]');
        $show = $Page->show(); //分页显示输出
        $this->assign('page',$show);
    	$this->assign('user',$this->user);
    	$this->assign('content',$content);
    	$this->assign('data',$return);
        $this->display('./search');
    }
}