<?php
namespace Home\Model;

use Think\Model;

class UserModel extends Model {

	    //表名
    protected $tableName = 'user';
    private $key='84914c0005333d5ae8cdb1e1d4983244';
    public function create($data) {
    	$tel=$data['tel'];
    	$check=$this->where('tel='.$tel)->find();//判断手机号码是否存在
    	if(is_array($check)) return false;
    	return $this->add($data);
    }
    public function up($data){
    	$id=$data['id'];
    	return $this->where('id='.$id)->save($data);
    }

    public function checklogin($data){
    	$tel=$data['tel'];
    	$password=$data['password'];
    	$_password=hash('md5',$key.$password);
    	$check=$this->where('tel='.$tel)->find();//判断手机号码是否存在
    	if(!is_array($check)) return false;//手机号码不存在
    	unset($check['password']);
    	session('user',$check);
    	return true;
    }
    public function getuser($id){
    	$return=$this->where('id='.$id)->find();
    	if(!is_array($return)) return false;
    	$role=session('user.role');//管理员时可以编辑已删除的用户
    	if($return['is_del']!=0&&$role!=0) return false;
    	return $return;
	}	
}
?>