<?php
namespace Home\Controller;
use Think\Controller;
class EditController extends Controller {

    public function __construct(){
        parent::__construct();
        islogin();
    }
    public function index(){
    	$id=I('path.2');
    	if(!is_numeric($id)) return false;
    	$row=D('Product')->get_product($id);
    	$this->assign('row',$row);
        $this->display('./edit');
    }
    public function save(){
    	$data=I('post.');
        if($data['upimg']==1){//图片文件发生改变
            $img=$data['img'];
            $img=str_replace('/uploadfile/','uploadfile/', $img);
            $_img=str_replace('uploadfile/','up/', $img);
            $date=date('Y/m/d');
            if(file_exists("up/".$date)) {
                rmdir("up/".$date);
            }
            mkdir("up/".$date,0777,true);//创建文件夹
            rename($img,$_img);
            $data['img']='/'.$_img;
            unlink($img);
        }
    	if(!D('Product')->save_product($data)) $this->error('更新失败');
        D('Productbuylog')->add_buy_log($data);
    	$this->success("更新成功");
    }
    public function uploadfile(){
        $file=$_FILES['uploadfile'];
        if ((($file["type"] == "image/gif")|| ($file["type"] == "image/jpeg")|| ($file["type"] == "image/png"))){
            if ($file["error"] > 0){
               ajax(-1,null,'文件上传失败');
            }else{
                if (file_exists("upload/" . $file["name"])){
                   ajax(-1,null,'文件已存在');
                }else{
                    $date=date('Y/m/d');
                    $fileSuffix = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
                    $new_file="uploadfile/".$date.'/'.md5_file($file['tmp_name']).$time.'.'.$fileSuffix;
                    if(file_exists("uploadfile/".$date)) {
                        rmdir("uploadfile/".$date);
                    }
                    mkdir("uploadfile/".$date,0777,true);//创建文件夹
                    move_uploaded_file($file["tmp_name"],$new_file);
                    ajax(1,'/'.$new_file,'文件上传成功');
                }
            }
        }else{
            ajax(-1,null,'文件类型错误');
        }
    }
}