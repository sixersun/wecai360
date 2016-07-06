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
        $sort=$_POST['sort'];
        $sort=json_decode($sort,true);
        foreach ($sort as $k => $v) {
            $d_v=$v-1;
            if(!empty($data[$d_v])){
                $arr[]=array($k+1,$v['img'],$data[$d_v]['proname'],$data[$d_v]['model'],$data[$d_v]['code'],$data[$d_v]['note'],$data[$d_v]['price'],$data[$d_v]['pro_num']);
            } 
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
    public function outpdf(){
        $sort=$_POST['sort'];
        $sort=json_decode($sort,true);
        $data=trim($_POST['html']);
        $data=json_decode($data,true);
        $html='<table><tr><th width="50">序号</th><th align="center" width="100">图片</th><th align="center">产品名称</th><th>产品型号</th><th>编号</th><th width="120">备注</th><th>价格</th><th>数量</th></tr>';
        $str='';
        // foreach ($data as $k => $v) {
        //     if(!empty($data[$k])){
        //         $str='<tr><td>'.($k+1).'</td><td width="100"><img src="'.$v['img'].'"></td><td align="center">'.$v['proname'].'</td><td>'.$v['model'].'</td><td>'.$v['code'].'</td><td>'.$v['note'].'</td><td>'.$v['price'].'</td><td>'.$v['pro_num'].'</td></tr>';
        //     }
        //     $html=$html.$str; 
        // }
        foreach($sort as $k=>$v){
            $d_v=$v-1;
            if(!empty($data[$d_v])){
                $str='<tr><td>'.($k+1).'</td><td width="100"><img src="'.$data[$d_v]['img'].'"></td><td align="center">'.$data[$d_v]['proname'].'</td><td>'.$data[$d_v]['model'].'</td><td>'.$data[$d_v]['code'].'</td><td>'.$data[$d_v]['note'].'</td><td>'.$data[$d_v]['price'].'</td><td>'.$data[$d_v]['pro_num'].'</td></tr>';
            }
            $html=$html.$str;             
        }
        $html=$html.'</table>';
        import('@.Org.tcpdf.tcpdf');
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Rootxia');
        $pdf->SetTitle('adb');
        $pdf->SetSubject('aaa');
        $pdf->SetKeywords('bbbb');
        // set default header data
        $pdf->SetHeaderData('wecailogo.png', '30', '', '', array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(APP_PATH.'Home/Org/tcpdf/examples/lang/eng.php')) {
            require_once(APP_PATH.'Home/Org/tcpdf/examples/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('stsongstdlight', '', 12);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // Set some content to print
        $inhtml='<h1>测试</h1>';
//         $html = <<<EOD
//         <>
// EOD;
        $pdf->writeHTML($html);
        $pdf->Output('example_001.pdf', 'I');
    }
}