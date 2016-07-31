<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller {
	public function __construct(){
		parent::__construct();
		islogin();
		$this->user=session('user');
	}
    public function index(){
    	$type=I('get.search_type');
    	$content=I('get.content');
    	if(!$content) $this->error('请输入搜索内容');
        $p=I('get.p')?I('get.p'):1;
        $size=12;
        $limit=($p-1)*$size.','.$size;
    	$return=D('Product')->get_search($content,$type,$limit);
        $count=D('Product')->get_count($content,$type);
        $Page = new \Think\Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数(2)
        $Page->nowPage = $p;
        $Page->setConfig('link', '/home/search?search_type='.$type.'&content='.$content.'&p=[PAGE]');
        $show = $Page->show(); //分页显示输出
        $this->assign('page',$show);
    	$this->assign('user',$this->user);
    	$this->assign('content',$content);
    	$this->assign('data',$return);
        $this->display('./search');
    }
    public function admin(){
        $role=$this->user['role'];
        if($role!=1) $this->error('你没有该权限');
        $p=I('get.p')?I('get.p'):1;
        $size=12;
        $limit=($p-1)*$size.','.$size;
        $return=D('Product')->get_search_total($limit);
        $count=D('Product')->get_count($content,$type);
        $Page = new \Think\Page($count, $size); // 实例化分页类 传入总记录数和每页显示的记录数(2)
        $Page->nowPage = $p;
        $Page->setConfig('link', '/home/search/admin?p=[PAGE]');
        $show = $Page->show(); //分页显示输出
        $this->assign('page',$show);
        $this->assign('user',$this->user);
        $this->assign('content',$content);
        $this->assign('data',$return);
        $this->display('./search');        
    }
}