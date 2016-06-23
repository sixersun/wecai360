<?php
namespace Home\Controller;
use Think\Controller;
class BatchController extends Controller {

	public function __construct(){
		parent::__construct();
		islogin();
		$this->ext=array('xls','xlsx');
	}

    public function index(){
        $this->display('./batch');
    }
	public function upload(){
	    $file=$_FILES['batchfile'];
	    $file['ext']    =   pathinfo($file['name'], PATHINFO_EXTENSION);
	    if(!in_array($file['ext'],$this->ext)){
	    	$this->error('只支持xls，xlsx文件上传');
	    }
	    $excel=$file['tmp_name'];
	    //$excel=file_get_contents($file['tmp_name']);
        import('@.Org.excel.PHPexcel');
        $reader = \PHPExcel_IOFactory::createReader('Excel2007'); 
        $PHPExcel = $reader->load($excel); // 载入excel文件
        //$sheetcount=$PHPExcel->getSheetCount();//获取工作表的数量	    
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=2;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
            $arr=array();
			for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$arr[]=$currentSheet->getCell($address)->getValue();
			}
            if(!D('Product')->batch_product($arr)) return false;
		
		}
        $this->success('数据导入成功');
	}
	// public function export_excel(){
	// 		$arr=array(
 //                      'sheet1'=>array( //第一张工作表
 //                             '0'=>array(0=>'A1',1=>'A2'),
 //                             '1'=>array(0=>'B1',2=>'B2'),
 //                       ),
 //                      'sheet2'=>array( //第二张工作表
 //                             '0'=>array(0=>'A11',1=>'A22'),
 //                             '1'=>array(0=>'B11',2=>'B22'),
 //                       ),  
 //                   );
	// 	$this->push($arr);
	// }
 	public function push($data,$filename='aaa.xls'){
 		import('@.Org.excel.excel');
        $PHPExcel = new \PHPExcel();
        $activesheet=0;//初始化工作表
        foreach ($data as $k => $v) {
            $span=ord('A');
            if($activesheet!=0){
                $PHPExcel->createSheet();//新建工作表
            }
            $PHPExcel->setactivesheetindex($activesheet);
            $PHPExcel->getActiveSheet()->setTitle($k);//设置工作表名称       
            foreach ($v as $key => $value) {//行写入
                $i=chr($span);
                $column=1;
                foreach ($value as $setkey => $setvalue) {
                	echo $setvalue;
                    $PHPExcel->getactivesheet()->setCellValue($i.$column,$setvalue);//保存数据
                    $column++;
                }
                $span++;   
            }
            $activesheet++;

        }
        $PHPExcel->setActiveSheetIndex(0);//设置第一表为活动表
        //exit;
        $this->out_excel($PHPExcel, $filename);
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
        $Writer = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $Writer->save('php://output');

    }
    //导出批量模板
    public function export_excel(){
			$arr=array(
                      'sheet1'=>array( //第一张工作表
                             '0'=>array(0=>'A1',1=>'A2'),
                             '1'=>array(0=>'B1',2=>'B2'),
                       ),
                      'sheet2'=>array( //第二张工作表
                             '0'=>array(0=>'A11',1=>'A22'),
                             '1'=>array(0=>'B11',2=>'B22'),
                       ),  
                   );
        //开始制作excel
        import('@.ORG.excel.excel');
        $PHPExcel=new \PHPExcel();
        $activesheet=0;//初始化工作表
        foreach ($arr as $k => $v) {
            $span=ord('A');
            if($activesheet!=0){
                $PHPExcel->createSheet();//新建工作表
            }
            $PHPExcel->setactivesheetindex($activesheet);
            $PHPExcel->getActiveSheet()->setTitle($k);//设置工作表名称 
            if($k == '说明'){   //第一张说明表特殊处理
                $PHPExcel->getactivesheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
                $PHPExcel->getactivesheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setWrapText(true);
                $PHPExcel->getactivesheet()->setCellValue('A1',$v[0][0]);//保存数据
                $PHPExcel->getActiveSheet()->mergeCells('A1:K17');   //合并单元格
                
            }
            foreach ($v as $key => $value) {//行写入
                $i=chr($span);
                $column=1;
                foreach ($value as $setkey => $setvalue) {
                    $PHPExcel->getactivesheet()->setCellValue($i.$column,$setvalue);//保存数据
                    $column++;
                }
                $span++;   
            }
            $activesheet++;

        }
        $PHPExcel->setActiveSheetIndex(0);//设置第一表为活动表
	    $filename='aaaa.xlsx'; //这里可能有坑 非中文系统文件名可能会乱码
        $filename=iconv("utf-8","gb2312",$filename);

       // 输出excel
       $this->out_excel($PHPExcel, $filename);
	}
}