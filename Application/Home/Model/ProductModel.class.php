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
    public function get_search_total($limit){
        $return=$this->limit($limit)->order('id desc')->select();
        return $return;        
    }
    public function get_search($content,$type,$limit='0,12'){
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
    	$return=$this->where($key.' like "%'.$content.'%"')->limit($limit)->order('id desc')->select();
    	return $return;
    }
    public function get_count($content,$type){
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
        $return=$this->where($key.' like "%'.$content.'%"')->count();
        return $return;        
    }
    public function search_code($code){
        return $this->where('code="'.$code.'"')->find();
    }
    public function batch_product($data){
        $_data['code']=$data[3];
        $_data['proname']=$data[1];
        $_data['price']=$data[5];
        //$_data['date']=$data[3];
        //$_data['shelf']=$data[4];
        //$_data['supplier']=$data[5];
        $_data['stock']=$data[6];
        $_data['note']=$data[4];
        $_data['img']=$data[0];
        $_data['model']=$data[2];
        D('Productbuylog')->add_buy_log($_data);
        $row=$this->search_code($_data['code']);
        if(is_array($row)) {
            $_data['stock']=$row['stock']+$_data['stock'];
            return  $this->save_product($_data);
        }
        return $this->add($_data);
    }
    public function get_cat_search($cat,$limit){
        $return=$this->where('cat='.$cat)->order('id desc')->limit($limit)->select();
        return $return;
    }
    public function get_cat_count($cat){
        $count=$this->where('cat='.$cat)->order('id asc')->count();
        return $count;
    }

}
?>