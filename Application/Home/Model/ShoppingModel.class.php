<?php
namespace Home\Model;

use Think\Model;

class ShoppingModel extends Model {

	    //表名
    protected $tableName = 'shopping';
    private $key='84914c0005333d5ae8cdb1e1d4983244';
    public function getshopping($userid) {
    	$return=$this->where('user_id='.$userid.' AND status=1')->order('id desc')->select();
    	if(!is_array($return)) return false;
    	return $return;
    }
    public function saveshoping($data){
    	 $arr=$this->where('user_id='.$data['user_id'].' AND pro_id='.$data['pro_id'])->find();
    	 if(!is_array($arr)){
    	 	return $this->add($data);
    	 }else{
    	 	$data['pro_num']=$arr['pro_num']+$data['pro_num'];
    	 	return $this->where('user_id='.$data['user_id'].' AND pro_id='.$data['pro_id'])->save($data);
    	 }
    }
    public function delshopping($id){
        $data['status']=0;
        return $this->where('id='.$id)->save($data);
    }
}
?>