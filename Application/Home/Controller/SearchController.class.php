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
    	$type=I('post.search_type');
    	$content=I('post.content');
    	if(!$content) $this->error('请输入搜索内容');
    	$return=D('Product')->get_search($content,$type);
    	$this->assign('user',$this->user);
    	$this->assign('content',$content);
    	$this->assign('data',$return);
        $this->display('./search');
    }
}