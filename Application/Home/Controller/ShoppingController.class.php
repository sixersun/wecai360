<?php
namespace Home\Controller;
use Think\Controller;
class ShoppingController extends Controller {
    public function __construct(){
        parent::__construct();
        islogin();
        $this->user=session('user');
    }
    public function index(){
        $user=session('user');
        $user_id=$user['id'];
        $list=D('Shopping')->getshopping($user_id);
        $this->assign('list',$list);
        $this->display('./shopping');
    }
    public function del_id(){
        $id=I('post.shopping_id');
        if(!is_numeric($id)) return false;
        if(!D('Shopping')->delshopping($id)) ajax(-1,'删除失败',null);
        ajax(1,'删除成功',null);
    }
    public function in(){
        if(!$_POST) return false;
        $data=I('post.data');
        $arr=array(
            'user_id'=>$this->user['id'],
            'pro_id'=>$data['id'],
            'code'=>$data['code'],
            'proname'=>$data['proname'],
            'pro_num'=>$data['num']?$data['num']:1,
            'supplier'=>$data['supplier'],
            'price'=>$data['price'],
            'model'=>$data['model'],
            'note'=>$data['note'],
            'img'=>$data['img'],
            'status'=>1,
            );
        if(!D('shopping')->saveshoping($arr)){
            ajax(-1,'添加到购物车失败',null);
        }
        ajax(1,'添加到购物车',null);
    }
    public function export_excel(){

        $data=trim($_POST['data']);
        $data=json_decode($data,true);
        foreach ($data as $k => $v) {
            $arr[]=array($k+1,$v['img'],$v['proname'],$v['model'],$v['code'],$v['note'],$v['price'],$v['pro_num']);
        }
        $this->push($arr);
    }
    public function push($data){
        import('@.Org.excel.PHPExcel');
        $excel = new \PHPExcel();  
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        /*设置文本对齐方式*/
        $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet = $excel->getActiveSheet();
        $letter = array('A','B','C','D','E','F','G','H','I');
        /*设置表头数据*/
        $tableheader = array('序号', '图片', '产品', '型号', '编号','备注','单价','数量');
        /*填充表格表头*/
        for($i = 0;$i < count($tableheader);$i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
        }
        /*填充表格内容*/
        for ($i = 0;$i < count($data);$i++) {
            $j = $i + 2;
            /*设置表格宽度*/
            $objActSheet->getColumnDimension("$letter[$i]")->setWidth(20);
            /*设置表格高度*/
            $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(100);
            /*向每行单元格插入数据*/
            for ($row = 0;$row < count($data[$i]);$row++) {
                if ($row == 1) {
                    /*实例化插入图片类*/
                    $objDrawing = new \PHPExcel_Worksheet_Drawing();
                    /*设置图片路径 切记：只能是本地图片*/
                    $data[$i][$row]=str_replace('/up/','up/',$data[$i][$row]);
                    $objDrawing->setPath($data[$i][$row]);
                    /*设置图片高度*/
                    $objDrawing->setHeight(130);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates("$letter[$row]$j");
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(20);
                    $objDrawing->setRotation(20);
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(50);
                    $objDrawing->setWorksheet($excel->getActiveSheet());
                    continue;
                }
                $excel->getActiveSheet()->setCellValue("$letter[$row]$j",$data[$i][$row]);
            }
        }
        $write = new \PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="测试文件.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }
    private function out_excel($PHPExcel, $filename) {
        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")) {
            //如果是ie存为的名字要urlencode
            header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
        } else {
            header('Content-Disposition: attachment; filename="'.$filename.'"');//存为的名字
        }
        header('Content-Transfer-Encoding: binary');//可删否
        header('Cache-Control: max-age=0');
        ob_clean();//关键
        flush();//关键
        $Writer = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $Writer->save('php://output');

    }
}