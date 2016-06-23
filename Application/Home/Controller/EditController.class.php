<?php
namespace Home\Controller;
use Think\Controller;
class EditController extends Controller {
    public function index(){
    	$id=I('path.2');
    	if(!is_numeric($id)) return false;
    	$row=D('Product')->get_product($id);
    	$this->assign('row',$row);
        $this->display('./edit');
    }
    public function save(){
    	$data=I('post.');
    	if(!D('Product')->save_product($data)) $this->error('更新失败');
    	$this->success("更新成功");
    }
}