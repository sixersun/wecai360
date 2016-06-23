<?php
namespace Home\Model;

use Think\Model;

class ProductModel extends Model {

	    //表名
    protected $tableName = 'product';

    public function get_product($id) {
    	$return=$this->where('id='.$id)->find();
    	return $return;
    }
    public function save_product($data){
    	 return $this->where('`code`="'.$data['code'].'"')->save($data);
    }
    public function get_search($content,$type){
    	switch ($type) {
    		case '1':
    			$key='`code`';
    			break;
    		case '2':
    			$key='`proname`';
    			break;	
    		case '3':
    			$key='`supplier`';
    			break;
    		case '4':
    			$key='`note`';
    			break;
    		default:
    			$key='`code`';
    			break;
    	}
    	$return=$this->where($key.' like "%'.$content.'%"')->select();
    	return $return;
    }
    public function search_code($code){
        return $this->where('code="'.$code.'"')->find();
    }
    public function batch_product($data){
        $_data['code']=$data[0];
        $_data['proname']=$data[1];
        $_data['price']=$data[2];
        $_data['date']=$data[3];
        $_data['shelf']=$data[4];
        $_data['supplier']=$data[5];
        $_data['stock']=$data[6];
        $_data['note']=$data[7];
        $row=$this->search_code($_data['code']);
        if(is_array($row)) {
            $_data['stock']=$row['stock']+$_data['stock'];
            return  $this->save_product($_data);
        }
        return $this->add($_data);
    }	
}
?>