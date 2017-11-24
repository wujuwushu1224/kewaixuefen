<?php
class DaoruAction extends IndexAction {
	public function daoru() {
		if ($_FILES){
			include_once("admin/lib/excel/reader.php");
			$tmp = $_FILES['file']['tmp_name'];
			if (empty ($tmp)) {
				echo '请选择要导入的Excel文件！';
				exit;
			}
			$xls = new Spreadsheet_Excel_Reader();
			$xls->setOutputEncoding('utf-8');
			$xls->read($tmp);
			$exceldata = $xls->sheets[0]['cells'];
			$i = 0;
			foreach ($exceldata as $ls){

					
					//写入寝室信息表
					$data['college'] = $ls[1]; //系部
					$data['class'] = $ls[2]; //班级	
					$data['truename'] = $ls[3]; //姓名				
					$data['name'] = $ls[4]; //学号
					$data['phone'] = $ls[5]; //号码
					$data['pass'] = md5( md5(123456).'pass'); //密码
					
					
					$data['role'] = 3; //权限
					$data['startime'] = date("Y-m-d H:i:s");
					
					if ($this->findObj("adminuser", "name = '{$data['name']}'")){//如果学生信息已存在 ,则不再写入数据库
						echo "学号为".$data['name'].",姓名为".$data['truename']."的学生信息已存在!<br />";
					}else{
						$i++;//记数
						$menberModel = new adminuserModel();
						$menberModel->add($data);
					}

			}
			echo "导入成功,共导入".$i."条记录!<br />";
			echo "<a href='".__URL__."/daoru'>返回</a>";
			

		}else{
			$this->display();
		}
	}

	
	//导出系部汇总
	public function daochu_xueyuan(){
		require_once './admin/Lib/PHPExcel/PHPExcel.php';
		require_once './admin/Lib/PHPExcel/PHPExcel/Writer/Excel5.php';
		
		$objExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$objProps = $objExcel->getProperties();  
		
		$id = $_GET['id'];
		$fanxiaotime = $this->findObj("fanxiaotime", "id = $id");
		
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();	
				
		$objActSheet->setTitle($fanxiaotime['time'].$fanxiaotime['jieri'].'各系部汇总情况');			
		$objActSheet->setCellValue('A1', '系部');
		$objActSheet->setCellValue('B1', '已到');
		$objActSheet->setCellValue('C1', '病假');
		$objActSheet->setCellValue('D1', '事假');
		$objActSheet->setCellValue('E1', '实习');
		$objActSheet->setCellValue('F1', '联系不上');
		$objActSheet->setCellValue('G1', '车票买不到');
		$objActSheet->setCellValue('H1', '其他');
			
			//未到情况统计
			$userModel = D('adminuser');
			
			$collegelist = $userModel->group('college')->order('college')->findall("role in (3,4)");
			
			foreach ($collegelist as $i => $ls){
				$classlist = $userModel->group('class')->order('class')->findall("college = '{$ls["college"]}' and role in (3,4)");
				$contentModel = D('content');
				foreach ($classlist as $k => $cls){
					$users = $this->findList("adminuser", "class = '{$cls['class']}'");
					$uids = '';
					foreach ($users as $uls){
						$uids .= ','.$uls['id'];
					}
					$uids = substr($uids, 1);
					
					
					//已到
					$classlist[$k]['count'][0] = $contentModel->count("uid in ($uids) and timeid = $id and (content = '已到' or content = '')");
					$total[0] += $classlist[$k]['count'][0]; //系部已到统计
					$all_total[0] += $classlist[$k]['count'][0]; //学校已到统计
					
					//病假
					$classlist[$k]['count'][1] = $contentModel->count("uid in ($uids) and timeid = $id and content = '病假'");
					$total[1] += $classlist[$k]['count'][1];
					$all_total[1] += $classlist[$k]['count'][1]; //学校病假统计
					
					//事假
					$classlist[$k]['count'][2] = $contentModel->count("uid in ($uids) and timeid = $id and content = '事假'");
					$total[2] += $classlist[$k]['count'][2];
					$all_total[2] += $classlist[$k]['count'][2]; //学校事假统计
					
					//实习
					$classlist[$k]['count'][3] = $contentModel->count("uid in ($uids) and timeid = $id and content = '实习'");
					$total[3] += $classlist[$k]['count'][3];
					$all_total[3] += $classlist[$k]['count'][3]; //学校实习统计
					
					//联系不上
					$classlist[$k]['count'][4] = $contentModel->count("uid in ($uids) and timeid = $id and content = '联系不上'");
					$total[4] += $classlist[$k]['count'][4];
					$all_total[4] += $classlist[$k]['count'][4]; //学校联系不上统计
					
					//车票买不到
					$classlist[$k]['count'][5] = $contentModel->count("uid in ($uids) and timeid = $id and content = '车票买不到'");
					$total[5] += $classlist[$k]['count'][5];
					$all_total[5] += $classlist[$k]['count'][5]; //学校车票买不到统计
					
					//其他
					$classlist[$k]['count'][6] = $contentModel->count("uid in ($uids) and timeid = $id and content = '其他'");
					$total[6] += $classlist[$k]['count'][6];
					$all_total[6] += $classlist[$k]['count'][6]; //学校其他统计
					
				}
				//$collegelist[$i]['classlist'] = $classlist;
				$collegelist[$i]['total'] = $total;  //每个系部的总计
				$total = '';
			}
			
		$objActSheet->setCellValue('A2', '全校');
		$objActSheet->setCellValue('B2', $all_total[0]);
		$objActSheet->setCellValue('C2', $all_total[1]);
		$objActSheet->setCellValue('D2', $all_total[2]);
		$objActSheet->setCellValue('E2', $all_total[3]);
		$objActSheet->setCellValue('F2', $all_total[4]);
		$objActSheet->setCellValue('G2', $all_total[5]);
		$objActSheet->setCellValue('H2', $all_total[6]);
				
		$i = 2;
		foreach ($collegelist as $ls){
				$i++;
				$objActSheet->setCellValue('A'.$i, $ls['college']);
				$objActSheet->setCellValue('B'.$i, $ls['total'][0]);
				$objActSheet->setCellValue('C'.$i, $ls['total'][1]);
				$objActSheet->setCellValue('D'.$i, $ls['total'][2]);
				$objActSheet->setCellValue('E'.$i, $ls['total'][3]);
				$objActSheet->setCellValue('F'.$i, $ls['total'][4]);
				$objActSheet->setCellValue('G'.$i, $ls['total'][5]);
				$objActSheet->setCellValue('H'.$i, $ls['total'][6]);
		}
		
		
		$outputFileName = $fanxiaotime['time'].$fanxiaotime['jieri']."各系部汇总情况.xls";
		$outputFileName = iconv("utf-8", 'gbk', $outputFileName);
		header("Content-Type: application/force-download");  
		header("Content-Type: application/octet-stream");  
		header("Content-Type: application/download");  
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');  
		header("Content-Transfer-Encoding: binary");  
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		header("Pragma: no-cache");  
		$objWriter->save('php://output');
	}
	
	//导出新生接待管理信息
	public function daochu_jiedai(){
		
		require_once './admin/Lib/PHPExcel/PHPExcel.php';
		require_once './admin/Lib/PHPExcel/PHPExcel/Writer/Excel5.php';
		
		$objExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$objProps = $objExcel->getProperties();
		
		$id = $_GET['id'];
		
		$jiedaitime = $this->findObj("jiedaitime", "id = $id");
		
		$time_s = strtotime($jiedaitime['time_s']);  //接待值班开始时间
		$time_e = strtotime($jiedaitime['time_e']);  //接待值班结束时间	
		
		$days = ($time_e - $time_s)/(24*60*60) + 1; //计算出值班的天数
		
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();			
		$objActSheet->setTitle($jiedaitime['title'].'统计');	
		
		$objActSheet->getStyle('1')->getFont()->setBold(true);
		
		$objActSheet->setCellValue('A2', '系部');
		$objActSheet->setCellValue('B2', '填表时间');
		
		$objActSheet->mergeCells('C1:E1');
		$objActSheet->setCellValue('C1', '组长')->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('C2', '姓名');
		$objActSheet->setCellValue('D2', '联系电话');
		$objActSheet->setCellValue('E2', '主要分工');
		
		$objActSheet->mergeCells('F1:H1');
		$objActSheet->setCellValue('F1', '副组长')->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('F2', '姓名');
		$objActSheet->setCellValue('G2', '联系电话');
		$objActSheet->setCellValue('H2', '主要分工');
		
		$objActSheet->mergeCells('I1:K1');
		$objActSheet->setCellValue('I1', '成员1')->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('I2', '姓名');
		$objActSheet->setCellValue('J2', '联系电话');
		$objActSheet->setCellValue('K2', '主要分工');
		
		$objActSheet->mergeCells('L1:N1');
		$objActSheet->setCellValue('L1', '成员2')->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('L2', '姓名');
		$objActSheet->setCellValue('M2', '联系电话');
		$objActSheet->setCellValue('N2', '主要分工');
		
		$objActSheet->mergeCells('O1:Q1');
		$objActSheet->setCellValue('O1', '成员3')->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('O2', '姓名');
		$objActSheet->setCellValue('P2', '联系电话');
		$objActSheet->setCellValue('Q2', '主要分工');
		
		$objActSheet->mergeCells('R1:T1');
		$objActSheet->setCellValue('R1', '成员4')->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('R2', '姓名');
		$objActSheet->setCellValue('S2', '联系电话');
		$objActSheet->setCellValue('T2', '主要分工');
		
		$objActSheet->mergeCells('U1:X1');
		$objActSheet->setCellValue('U1', '提前报到新生接待工作联系人')->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('U2', '姓名');
		$objActSheet->setCellValue('V2', '办电');
		$objActSheet->setCellValue('W2', '手机');
		$objActSheet->setCellValue('X2', '短号');
		
		$objActSheet->mergeCells('Y1:AB1');
		$objActSheet->setCellValue('Y1', '迎新信息管理系统具体工作负责人')->getStyle('Y1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('Y2', '姓名');
		$objActSheet->setCellValue('Z2', '办电');
		$objActSheet->setCellValue('AA2', '手机');
		$objActSheet->setCellValue('AB2', '短号');
		
		$objActSheet->mergeCells('AC1:AF1');
		$objActSheet->setCellValue('AC1', '新生档案接收负责人')->getStyle('AC1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('AC2', '姓名');
		$objActSheet->setCellValue('AD2', '办电');
		$objActSheet->setCellValue('AE2', '手机');
		$objActSheet->setCellValue('AF2', '短号');
		
		$objActSheet->mergeCells('AG1:AK1');
		$objActSheet->setCellValue('AG1', '报到手续办理点安排(1)')->getStyle('AG1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('AG2', '时间');
		$objActSheet->setCellValue('AH2', '校区');
		$objActSheet->setCellValue('AI2', '办理点位置');
		$objActSheet->setCellValue('AJ2', '联系人');
		$objActSheet->setCellValue('AK2', '联系电话');
		
		$objActSheet->mergeCells('AL1:AP1');
		$objActSheet->setCellValue('AL1', '报到手续办理点安排(2)')->getStyle('AL1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objActSheet->setCellValue('AL2', '时间');
		$objActSheet->setCellValue('AM2', '校区');
		$objActSheet->setCellValue('AN2', '办理点位置');
		$objActSheet->setCellValue('AO2', '联系人');
		$objActSheet->setCellValue('AP2', '联系电话');
		
		
		for ($i=0;$i<$days;$i++){ //值几天班,就循环几次
			$tmp1 = 65; //A的ASCII值
			$tmp2 = 81; //S的ASCII值
		
			$tmp2 = $tmp2+3*$i; //每次要加3*$i
			
			for($j=0;$j<3;$j++){
				if ($tmp2>90){ //Z的ASCII值为90,则第一位变为B,第二位从A开始算   (相当于26进制)
					$tmp1 = 66; //B的ASCII值
					$tmp2 -= 26; //减去26,从A开始
				}
				$Sheet[$j] = chr($tmp1).chr($tmp2);
				$tmp2++;
			}
			
			$objActSheet->mergeCells($Sheet[0].'1:'.$Sheet[2].'1'); //合并
			$objActSheet->setCellValue($Sheet[0].'1', '值班安排')->getStyle($Sheet[0].'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objActSheet->setCellValue($Sheet[0].'2', '日期');
			$objActSheet->setCellValue($Sheet[1].'2', '姓名');
			$objActSheet->setCellValue($Sheet[2].'2', '联系电话');
		}
		
		
		//查出所有此返校时间的除 '已到'和 为空 的数据
		$conlist = $this->findList("jiedai", "tid = $id", 'pubtime desc');
		
		$j = 2;
		foreach ($conlist as $ls){
			//$user = $this->findObj("adminuser", "id = {$ls['uid']}");
			$j++;
			
				$objActSheet->setCellValue('A'.$j, $ls['college']);
				$objActSheet->setCellValue('B'.$j, substr($ls['pubtime'],0,10));
				
				$objActSheet->setCellValue('C'.$j, $ls['a1']);
				$objActSheet->setCellValueExplicit('D'.$j, $ls['a2'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('E'.$j, $ls['a3']);
				
				$objActSheet->setCellValue('F'.$j, $ls['a4']);
				$objActSheet->setCellValueExplicit('G'.$j, $ls['a5'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('H'.$j, $ls['a6']);
				
				$objActSheet->setCellValue('I'.$j, $ls['a7']);
				$objActSheet->setCellValueExplicit('J'.$j, $ls['a8'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('K'.$j, $ls['a9']);
				
				$objActSheet->setCellValue('L'.$j, $ls['a10']);
				$objActSheet->setCellValueExplicit('M'.$j, $ls['a11'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('N'.$j, $ls['a12']);
				
				$objActSheet->setCellValue('O'.$j, $ls['a13']);
				$objActSheet->setCellValueExplicit('P'.$j, $ls['a14'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('Q'.$j, $ls['a15']);
				
				$objActSheet->setCellValue('R'.$j, $ls['a16']);
				$objActSheet->setCellValueExplicit('S'.$j, $ls['a17'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValue('T'.$j, $ls['a18']);
				
				$objActSheet->setCellValue('U'.$j, $ls['a19']);
				$objActSheet->setCellValueExplicit('V'.$j, $ls['a20'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('W'.$j, $ls['a21'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('X'.$j, $ls['a22'], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objActSheet->setCellValue('Y'.$j, $ls['a23']);
				$objActSheet->setCellValueExplicit('Z'.$j, $ls['a24'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('AA'.$j, $ls['a25'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('AB'.$j, $ls['a26'], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objActSheet->setCellValue('AC'.$j, $ls['a37']);
				$objActSheet->setCellValueExplicit('AD'.$j, $ls['a38'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('AE'.$j, $ls['a39'], PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->setCellValueExplicit('AF'.$j, $ls['a40'], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objActSheet->setCellValue('AG'.$j, $ls['a27']);
				$objActSheet->setCellValue('AH'.$j, $ls['a28']);
				$objActSheet->setCellValue('AI'.$j, $ls['a29']);
				$objActSheet->setCellValue('AJ'.$j, $ls['a30']);
				$objActSheet->setCellValueExplicit('AK'.$j, $ls['a31'], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objActSheet->setCellValue('AL'.$j, $ls['a32']);
				$objActSheet->setCellValue('AM'.$j, $ls['a33']);
				$objActSheet->setCellValue('AN'.$j, $ls['a34']);
				$objActSheet->setCellValue('AO'.$j, $ls['a35']);
				$objActSheet->setCellValueExplicit('AP'.$j, $ls['a36'], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$zhiban = $this->findList("jiedai_zb", "jid = {$ls['id']} and uid = {$ls['uid']}", "date desc");
				
				$n = 0;
				for ($i=0;$i<$days;$i++){ //值几天班,就循环几次
					$tmp1 = 65; //A的ASCII值
					$tmp2 = 81; //S的ASCII值
				
					$tmp2 = $tmp2+3*$i; //每次要加3*$i
					
					for($l=0;$l<3;$l++){
						if ($tmp2>90){ //Z的ASCII值为90,则第一位变为B,第二位从A开始算   (相当于26进制)
							$tmp1 = 66; //B的ASCII值
							$tmp2 -= 26; //减去26,从A开始
						}
						$Sheet[$l] = chr($tmp1).chr($tmp2);
						$tmp2++;
					}
					
					$objActSheet->setCellValue($Sheet[0].$j, $zhiban[$n]['date']);
					$objActSheet->setCellValue($Sheet[1].$j, $zhiban[$n]['name']);
					$objActSheet->setCellValueExplicit($Sheet[2].$j, $zhiban[$n]['tel'], PHPExcel_Cell_DataType::TYPE_STRING);
					$n++;
				}
		
		}
		
		$outputFileName = $jiedaitime['title']."统计.xls";
		$outputFileName = iconv("utf-8", 'gbk', $outputFileName);
		header("Content-Type: application/force-download");  
		header("Content-Type: application/octet-stream");  
		header("Content-Type: application/download");  
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');  
		header("Content-Transfer-Encoding: binary");  
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		header("Pragma: no-cache");  
		$objWriter->save('php://output');
	}
	
}

?>