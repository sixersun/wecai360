<?php
namespace Home\Model;

use Think\Model;

class ProductbuylogModel extends Model {

	    //表名
    protected $tableName = 'product_buy_log';

    public function get_buy_log($code) {
    	$return=$this->where('`code`="'.$code.'"')->order('id desc')->select();
        if(!is_array($return)) return false;
    	return $return;
    }
    public function add_buy_log($data){
    	$user=session('user');
    	$data['user_name']=$user['true_name'];
        return $this->add($data);
    }	
}
?>