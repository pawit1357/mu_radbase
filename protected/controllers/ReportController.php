<?php
class ReportController extends CController {
	// const CRLF = ""; // \r\n";
	public $layout = '_main';
	private $_model;
	
	/* ----------------------------- รายงาน ปส. -------------------------------------- */
	public function actionReport01() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and t.license_no =''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and t.license_no =''";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and t.license_no =''";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and t.license_no =''";
					break;
			}
			
			$datas = Form1::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// HEADER
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>ตารางที่ ๔.๑  รายละเอียด (เฉพาะที่ระบุได้) ของเครื่องกำเนิดรังสีทั้งหมดที่ขออนุญาต</td>' . '</tr>' . '</table>';
				$str .= '<br>';
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
					<thead>
							<tr>
								<th style="text-align: center" rowspan="2">ลำดับ</th>
								<th style="text-align: center" rowspan="2">ทะเบียนอ้างอิง</th>
								<th style="text-align: center" rowspan="2">รหัสประเภทการใช้งาน </th>
								<th style="text-align: center" rowspan="2">ผู้ผลิต</th>
								<th style="text-align: center" rowspan="2">รุ่น<br>(Model)
								</th>
								<th style="text-align: center" rowspan="2">หมายเลขเครื่อง<br>(Serial
									Number)
								</th>
								<th style="text-align: center" rowspan="2">ลักษณะการใช้งาน <br>(Fixes,<br>Mobile,<br>Portable,<br>Stationary)</th>
								<th style="text-align: center" colspan="4">กำลัง/พลังงานสูงสุด
								</th>
								<th style="text-align: center" rowspan="2">ชื่อห้อง/สถานที่<br>เก็บติดตั้ง<br>หรือใช้งาน
								</th>
								<th style="text-align: center" rowspan="2">บริษัทผู้แทน<br>จำหน่าย<br>(ที่อยู่)</th>
							</tr>
							<tr>
								<th style="text-align: center">กิโลโวลต์ <br>(Kv)</th>
								<th style="text-align: center">เมกกะ<br>อิเลคตรอนโวลต์ <br>(MeV)</th>
								<th style="text-align: center">มิลลิแอมแปร์ <br>(mA)</th>
								<th style="text-align: center">มิลลิโวลต์<br>(mV)</th>
							</tr>
						</thead>
							<tbody>';
				// BODY
				$order = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center">' . $order . '</td>';
					// $str .= '<td>' . '' . '</td>';
					$str .= '<td style="text-align: center">' . $item->license_no . '</td>';
					$str .= '<td style="text-align: center">' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					$str .= '<td style="text-align: center">' . (isset ( $item->maufacturer->name ) ? $item->maufacturer->name : '') . '</td>';
					
					$str .= '<td style="text-align: center">' . $item->model . '</td>';
					$str .= '<td style="text-align: center">' . $item->serial_number . '</td>';
					$str .= '<td style="text-align: center">' . (isset ( $item->use_type->name ) ? $item->use_type->name : '') . '</td>';
					
					$str .= '<td style="text-align: center">' . (($item->power_unit_id == '1') ? $item->power : '') . '' . (($item->power_unit_id2 == '1') ? ' ' . $item->power2 : '') . '</td>';
					$str .= '<td style="text-align: center">' . (($item->power_unit_id == '2') ? $item->power : '') . '' . (($item->power_unit_id2 == '2') ? ' ' . $item->power2 : '') . '</td>';
					$str .= '<td style="text-align: center">' . (($item->power_unit_id == '4') ? $item->power : '') . '' . (($item->power_unit_id2 == '4') ? ' ' . $item->power2 : '') . '</td>';
					$str .= '<td style="text-align: center">' . (($item->power_unit_id == '6') ? $item->power : '') . '' . (($item->power_unit_id2 == '6') ? ' ' . $item->power2 : '') . '</td>';
					$str .= '<td style="text-align: center">' . 'ห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : '(' . $item->room->number . ')') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					$str .= '<td style="text-align: center">' . (isset ( $item->maufacturer->name ) ? $item->maufacturer->name : '') . '</td>';
					$str .= '</tr>';
					$order ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				// create new PDF document
				$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
				// Set font
				$pdf->SetFont ( 'thsarabun', '', 14 );
				$pdf->AddPage ();
				// Print text using writeHTMLCell()
				$pdf->writeHTMLCell ( 0, 0, '', '', $str, 0, 1, 0, true, '', true );
				
				// Close and output PDF document
				$tmp_pdf_file = dirname ( __FILE__ ) . '../../../uploads/' . UserLoginUtils::getUsersLoginId () . 'report_' . date ( "Y-m-d" ) . '_' . str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ) . '.pdf';
				$pdf->Output ( $tmp_pdf_file, 'F' );
				
				// initiate FPDI
				$fpdi = new FPDI ();
				// add a page
				$fpdi->AddPage ();
				// set the source file
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template01.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 1 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 2 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 3 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 4 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 5 ) );
				$fpdi->AddPage ( 'L' );
				$pagecount = $fpdi->setSourceFile ( $tmp_pdf_file );
				for($x = 1; $x <= $pagecount; $x ++) {
					$fpdi->useTemplate ( $fpdi->importPage ( $x ) );
					if ($x < $pagecount) {
						$fpdi->AddPage ( 'L' );
					}
				}
				$fpdi->AddPage ();
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template01.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 7 ) );
				
				// if (file_exists($tmp_pdf_file)) { unlink ($tmp_pdf_file); }
				
				$fpdi->Output ();
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	public function actionReport02() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=1";
					break;
			}
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>ตาราง ๔.๑ ข้อมูล (เฉพาะที่ระบุได้)  ของวัสดุพลอยได้ชนิดปิดผนึก(Sealed Source) ทั้งหมดที่ขออนุญาต</td>' . '</tr>' . '</table>';
				$str .= '<br>';
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="3" style="text-align: center;">ลำดับ</th>
								<th rowspan="3" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="3" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="7" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4" style="text-align: center;">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th rowspan="3" colspan="1" style="text-align: center;">สถานภาพวัสดุ<br>1.ใช้งานปกติ<br>2.เก็บสำรอง<br>3.ยกเลิกการใช้<br>4.รอจัดการกาก<br>5.กำลังสั่งนำเข้า<br></th>
								<th rowspan="3" style="text-align: center;">ชื่อห้อง <br>/สถานที่<br>เก็บ<br>ติดตั้ง<br>หรือใช้<br>งาน</th>
								<th rowspan="3" style="text-align: center;">บริษัท<br>ผู้แทน<br>จำหน่าย<br>(ที่อยู่)</th>
							</tr>
							<tr>
								<th rowspan="2" style="text-align: center;">ธาตุ-เลขมวล</th>
								<th rowspan="2" style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">หมายเลข<br>วัสดุ <br>(Serial<br>number</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">หมายเลข<br>วัสดุ<br>(Serial<br>number)</th>
								<th rowspan="2" style="text-align: center;">ความจุ<br>กัมมันตภาพ<br>หรือน้ำหนัก<br>สูงสุด<br>(Bq, Ci,<br>Kg, Lb)</th>
							</tr>
							<tr>
								<th style="text-align: center;">ปริมาณ</th>
								<th style="text-align: center;">ณ<br>วันที่</th>
								<th style="text-align: center;">จำนวน</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_model . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->manufacturer->name ) ? $item->manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_no . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_volume . ' ' . $item->unit->name . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_as_of_date . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_number . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . 'ห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : '(' . $item->room->number . ')') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->dealer->name ) ? $item->dealer->name : '') . '</td>';
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				// UNSEAL
				$criteria2 = new CDbCriteria ();
				$criteria2->with = array (
						'owner_department' 
				);
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria2->condition = " t.status ='T' and license_no <> ''";
						break;
					case UserLoginUtils::USER :
						$criteria2->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=2";
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria2->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=2";
						break;
					case UserLoginUtils::STAFF :
						$criteria2->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=2";
						break;
				}
				
				$datas2 = Form2::model ()->findAll ( $criteria2 );
				if (isset ( $datas2 )) {
					// BEGIN
					
					$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>ตาราง ๔.๒ ข้อมูล (เฉพาะที่ระบุได้)  ของวัสดุพลอยได้ชนิดไม่ปิดผนึก(Unsealed Source) ทั้งหมดที่ขออนุญาต</td>' . '</tr>' . '</table>';
					$str .= '<br>';
					// TABLE
					$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="2" style="text-align: center;">ลำดับ</th>
								<th rowspan="2" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="2" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="5" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th rowspan="2" colspan="1" style="text-align: center;">สถานภาพวัสดุ<br>1.ใช้งานปกติ<br>2.เก็บสำรอง<br>3.ยกเลิกการใช้<br>4.รอจัดการกาก<br>5.กำลังสั่งนำเข้า<br></th>
								<th rowspan="2" style="text-align: center;">ชื่อห้อง <br>/สถานที่<br>เก็บ<br>ติดตั้ง<br>หรือใช้<br>งาน</th>
								<th rowspan="2" style="text-align: center;">บริษัท<br>ผู้แทน<br>จำหน่าย<br>(ที่อยู่)</th>
							</tr>
							<tr>
								<th style="text-align: center;">ธาตุ-เลขมวล</th>
								<th style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th style="text-align: center;">ผู้ผลิต</th>
								<th style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th style="text-align: center;">สมบัติทางกายภาพ<br>1.ของแข็ง<br>2.ของเหลว<br>3.ก๊าช</th>
					
							</tr>

			</thead>
			<tbody>';
					// BODY
					$index = 1;
					foreach ( $datas2 as $item ) {
						
						$str .= '<tr>';
						$str .= '<td style="text-align: center;">' . $index . '</td>';
						$str .= '<td style="text-align: center;">' . '' . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
						
						$str .= '<td style="text-align: center;">' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
						$str .= '<td style="text-align: center;">' . $item->bpm_model . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $item->manufacturer->name ) ? $item->manufacturer->name : '') . '</td>';
						// $str .= '<td style="text-align: center;">' . $item->bpm_no . '</td>';
						$str .= '<td style="text-align: center;">' . $item->bpm_volume . ' ' . $item->unit->name . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
						// $str .= '<td style="text-align: center;">' . $item->bpm_as_of_date . '</td>';
						// $str .= '<td style="text-align: center;">' . $item->bpm_number . '</td>';
						
						$str .= '<td style="text-align: center;">' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
						$str .= '<td style="text-align: center;">' . 'ห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : '(' . $item->room->number . ')') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $item->dealer->name ) ? $item->dealer->name : '') . '</td>';
						$str .= '</tr>';
						$index ++;
					}
					// END TABLE
					$str .= '</tbody></table>';
				}
				
				// create new PDF document
				$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
				// Set font
				$pdf->SetFont ( 'thsarabun', '', 14 );
				$pdf->AddPage ();
				// Print text using writeHTMLCell()
				$pdf->writeHTMLCell ( 0, 0, '', '', $str, 0, 1, 0, true, '', true );
				
				// Close and output PDF document
				$tmp_pdf_file = dirname ( __FILE__ ) . '../../../uploads/' . UserLoginUtils::getUsersLoginId () . 'report_' . date ( "Y-m-d" ) . '_' . str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ) . '.pdf';
				$pdf->Output ( $tmp_pdf_file, 'F' );
				
				// initiate FPDI
				$fpdi = new FPDI ();
				// add a page
				$fpdi->AddPage ();
				// set the source file
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template02.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 1 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 2 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 3 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 4 ) );
				$fpdi->AddPage ( 'L' );
				$pagecount = $fpdi->setSourceFile ( $tmp_pdf_file );
				for($x = 1; $x <= $pagecount; $x ++) {
					$fpdi->useTemplate ( $fpdi->importPage ( $x ) );
					if ($x < $pagecount) {
						$fpdi->AddPage ( 'L' );
					}
				}
				$fpdi->AddPage ();
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template02.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 7 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 8 ) );
				// if (file_exists($tmp_pdf_file)) { unlink ($tmp_pdf_file); }
				
				$fpdi->Output ();
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	public function actionReport03() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=1";
					break;
			}
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>ตาราง ๔.๑ ข้อมูล (เฉพาะที่ระบุได้)  ของวัสดุพลอยได้ชนิดปิดผนึก(Sealed Source) ทั้งหมดที่ขออนุญาต</td>' . '</tr>' . '</table>';
				$str .= '<br>';
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="3" style="text-align: center;">ลำดับ</th>
								<th rowspan="3" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="3" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="7" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4" style="text-align: center;">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th rowspan="3" style="text-align: center;">ชื่อห้อง <br>/สถานที่<br>เก็บ<br>ติดตั้ง<br>หรือใช้<br>งาน</th>
							</tr>
							<tr>
								<th rowspan="2" style="text-align: center;">ธาตุ-เลขมวล</th>
								<th rowspan="2" style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">หมายเลข<br>วัสดุ <br>(Serial<br>number</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">หมายเลข<br>วัสดุ<br>(Serial<br>number)</th>
								<th rowspan="2" style="text-align: center;">ความจุ<br>กัมมันตภาพ<br>หรือน้ำหนัก<br>สูงสุด<br>(Bq, Ci,<br>Kg, Lb)</th>
							</tr>
							<tr>
								<th style="text-align: center;">ปริมาณ</th>
								<th style="text-align: center;">ณ<br>วันที่</th>
								<th style="text-align: center;">จำนวน</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_model . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->manufacturer->name ) ? $item->manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_no . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_volume . ' ' . $item->unit->name . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_as_of_date . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_number . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->room->name ) ? $item->room->name : '') . '</td>';
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				// UNSEAL//
				$criteria2 = new CDbCriteria ();
				// $criteria2->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=2";
				$criteria2->with = array (
						'owner_department' 
				);
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria2->condition = " t.status ='T' and license_no <> ''";
						break;
					case UserLoginUtils::USER :
						$criteria2->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=2";
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria2->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=2";
						break;
					case UserLoginUtils::STAFF :
						$criteria2->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=2";
						break;
				}
				$datas2 = Form2::model ()->findAll ( $criteria2 );
				// BEGIN
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>ตาราง ๔.๒ ข้อมูล (เฉพาะที่ระบุได้)  ของวัสดุพลอยได้ชนิดไม่ปิดผนึก(Unsealed Source) ทั้งหมดที่ขออนุญาต</td>' . '</tr>' . '</table>';
				$str .= '<br>';
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="2" style="text-align: center;">ลำดับ</th>
								<th rowspan="2" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="2" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
					
								<th colspan="5" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th rowspan="2" style="text-align: center;">ชื่อห้อง <br>/สถานที่<br>เก็บ<br>ติดตั้ง<br>หรือใช้<br>งาน</th>
							</tr>
							<tr>
								<th style="text-align: center;">ธาตุ-เลขมวล</th>
								<th style="text-align: center;">รุ่น/<br>รหัสสินค้า</th>
								<th style="text-align: center;">ผู้ผลิต</th>
								<th style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th style="text-align: center;">สมบัติทางกายภาพ<br>1.ของแข็ง<br>2.ของเหลว<br>3.ก๊าช</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas2 as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_model . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->manufacturer->name ) ? $item->manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $item->bpm_volume . ' ' . $item->unit->name . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $item->room->name ) ? $item->room->name : '') . '</td>';
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				// create new PDF document
				$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
				// Set font
				$pdf->SetFont ( 'thsarabun', '', 14 );
				$pdf->AddPage ();
				// Print text using writeHTMLCell()
				$pdf->writeHTMLCell ( 0, 0, '', '', $str, 0, 1, 0, true, '', true );
				
				// Close and output PDF document
				$tmp_pdf_file = dirname ( __FILE__ ) . '../../../uploads/' . UserLoginUtils::getUsersLoginId () . 'report_' . date ( "Y-m-d" ) . '_' . str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ) . '.pdf';
				$pdf->Output ( $tmp_pdf_file, 'F' );
				
				// initiate FPDI
				$fpdi = new FPDI ();
				// add a page
				$fpdi->AddPage ();
				// set the source file
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template03.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 1 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 2 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 3 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 4 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 5 ) );
				$fpdi->AddPage ( 'L' );
				$pagecount = $fpdi->setSourceFile ( $tmp_pdf_file );
				for($x = 1; $x <= $pagecount; $x ++) {
					$fpdi->useTemplate ( $fpdi->importPage ( $x ) );
					if ($x < $pagecount) {
						$fpdi->AddPage ( 'L' );
					}
				}
				$fpdi->AddPage ();
				$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template03.pdf' );
				$fpdi->useTemplate ( $fpdi->importPage ( 8 ) );
				$fpdi->AddPage ();
				$fpdi->useTemplate ( $fpdi->importPage ( 9 ) );
				// if (file_exists($tmp_pdf_file)) { unlink ($tmp_pdf_file); }
				
				$fpdi->Output ();
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	public function actionReport14() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
			}
			$datas = Form3::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานการเคลื่อนย้ายวัสดุกัมมันตรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="3" style="text-align: center;">ลำดับ</th>
								<th rowspan="3" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="3" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="8" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4" style="text-align: center;">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th colspan="2" style="text-align: center;">สถานที่เก็บรักษา/สถานที่<br>ใช้งาน</th>
								<th rowspan="3" style="text-align: center;">ดำเนินการ<br>เคลื่อนย้าย<br>ตั้งแต่วันที่ - ถึงวันที่</th>
								<th rowspan="3" style="text-align: center;">ผู้ควบคุม</th>
							</tr>
							<tr>
								<th rowspan="2" style="text-align: center;">ธาตุ-เลขมวล</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">หมายเลขวัสดุ</th>
								<th rowspan="2" style="text-align: center;">สมบัติทาง<br>กายภาพ</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
					
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">หมายเลข</th>
								<th rowspan="2" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
					
								<th rowspan="2" style="text-align: center;">เดิม</th>
								<th rowspan="2" style="text-align: center;">ไปที่</th>
							</tr>
							<tr>
								<th style="text-align: center;">ปริมาณ</th>
								<th style="text-align: center;">ณ วันที่</th>
								<th style="text-align: center;">จำนวน</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->code_usage->name ) ? $data->rad->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_radioactive_elements->name ) ? $data->rad->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_model ) ? $data->rad->bpm_model : '') . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>'; // (isset ( $data->rad->machine_manufacturer->name ) ? $data->rad->machine_manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_no ) ? $data->rad->bpm_no : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->phisicalStatus->name ) ? $data->rad->phisicalStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_volume ) ? $data->rad->bpm_volume : '') . ' ' . (isset ( $data->rad->unit->name ) ? $data->rad->unit->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_as_of_date ) ? CommonUtil::getDateThai ( $data->rad->bpm_as_of_date ) : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_number ) ? $data->rad->bpm_number : '') . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->from_room->name ) ? $data->from_room->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->to_room->name ) ? $data->to_room->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . CommonUtil::getDateThai ( $data->date_from ) . '-' . CommonUtil::getDateThai ( $data->date_from ) . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->supervisor_name ) ? $data->supervisor_name : '') . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	
	/* ------------------------------------------------------------------- */
	
	// แบบรายงานเครื่องกำเนิดรังสี
	public function actionReport04() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and t.license_no <>''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and t.license_no <>''";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and t.license_no <>''";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and t.license_no <>''";
					break;
			}
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T'";
			$datas = Form1::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานเครื่องกำเนิดรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อเครื่องมือ</th>
								<th style="text-align: center">รหัสเครื่องมือ</th>
								<th style="text-align: center">รุ่น</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อผู้ดูแลประจำเครื่อง</th>
								<th style="text-align: center">หมายเลขโทรศัพท์</th>
								<th style="text-align: center">สถานะการใช้งาน</th>
								<th style="text-align: center">สถานที่ติดตั้ง</th>
								<th style="text-align: center">วันที่ทำการติดตั้งเครื่องมือ</th>
								<th style="text-align: center">วันที่ทำการปรับเทียบเครื่องมือ</th>
								<th style="text-align: center">หน่วยงาน/ผู้ปรับเทียบเครื่องมือ</th>
								<th style="text-align: center">วันที่ตรวจคุณภาพจากกรมวิทยาศาสตร์การแพทย์</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center">' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->rad_machine->name ) ? $item->rad_machine->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . $item->model . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThai ( $item->license_expire_date ) . '</td>';
					$str .= '<td style="text-align: center" >' . $item->machine_owner . '</td>';
					$str .= '<td style="text-align: center" >' . $item->machine_owner_phone . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->usage_status->name ) ? $item->usage_status->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . 'ห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : '(' . $item->room->number . ')') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->delivery_date_day . '/' . $item->delivery_date_month . '/' . $item->delivery_date_year . '</td>';
					$str .= '<td style="text-align: center" >' . '' . '</td>';
					$str .= '<td style="text-align: center" >' . CommonUtil::getInspectionAgencyId ( $item->inspection_agency_id ) . '</td>';
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThaiMoreOne ( $item->quality_check_date ) . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานวัสดุกัมมันตรังสีชนิดปิดผนึก
	public function actionReport05() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=1";
					break;
			}
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานวัสดุกัมมันตรังสีชนิดปิดผนึก</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center">สถานภาพวัสดุ</th>
								<th style="text-align: center">สมบัติทางกายภาพ</th>
								<th style="text-align: center">กัมมันตภาพสูงสุด/น้ำหนัก</th>
								<th style="text-align: center">ปริมาณ</th>
								<th style="text-align: center">สถานที่จัดเก็บ</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อ – นามสกุลผู้ดูแล</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center" >' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
					
					$str .= '<td style="text-align: center" >' . '-' . '</td>';
					$str .= '<td style="text-align: center" >' . $item->bpm_volume . '  ' . (isset ( $item->unit->name ) ? $item->unit->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . 'ชื่อห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : ' เลขห้อง ' . $item->room->number . '') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThai ( $item->license_expire_date ) . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานวัสดุกัมมันตรังสีชนิดไม่ปิดผนึก
	public function actionReport06() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=2";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=2";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=2";
					break;
			}
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=2";
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานวัสดุกัมมันตรังสีชนิดไม่ปิดผนึก</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center">สถานภาพวัสดุ</th>
								<th style="text-align: center">สมบัติทางกายภาพ</th>
								<th style="text-align: center">กัมมันตภาพสูงสุด/น้ำหนัก</th>
								<th style="text-align: center">ปริมาณ</th>
								<th style="text-align: center">สถานที่จัดเก็บ</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อ – นามสกุลผู้ดูแล</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center" >' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
					
					$str .= '<td style="text-align: center" >' . '-' . '</td>';
					$str .= '<td style="text-align: center" >' . $item->bpm_volume . '  ' . (isset ( $item->unit->name ) ? $item->unit->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . 'ชื่อห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : ' เลขห้อง ' . $item->room->number . '') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThai ( $item->license_expire_date ) . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
			}
			self::GeneratePDF ( $str );
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานการเคลื่อนย้ายวัสดุกัมมันตรังสี
	public function actionReport07() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
			}
			$datas = Form3::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานการเคลื่อนย้ายวัสดุกัมมันตรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="3" style="text-align: center;">ลำดับ</th>
								<th rowspan="3" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="3" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="8" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4" style="text-align: center;">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th colspan="2" style="text-align: center;">สถานที่เก็บรักษา/สถานที่<br>ใช้งาน</th>
								<th rowspan="3" style="text-align: center;">ดำเนินการ<br>เคลื่อนย้าย<br>ตั้งแต่วันที่ - ถึงวันที่</th>
								<th rowspan="3" style="text-align: center;">ผู้ควบคุม</th>
							</tr>
							<tr>
								<th rowspan="2" style="text-align: center;">ธาตุ-เลขมวล</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">หมายเลขวัสดุ</th>
								<th rowspan="2" style="text-align: center;">สมบัติทาง<br>กายภาพ</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
					
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">หมายเลข</th>
								<th rowspan="2" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
			
								<th rowspan="2" style="text-align: center;">เดิม</th>
								<th rowspan="2" style="text-align: center;">ไปที่</th>
							</tr>
							<tr>
								<th style="text-align: center;">ปริมาณ</th>
								<th style="text-align: center;">ณ วันที่</th>
								<th style="text-align: center;">จำนวน</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->code_usage->name ) ? $data->rad->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_radioactive_elements->name ) ? $data->rad->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_model ) ? $data->rad->bpm_model : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->manufacturer->name ) ? $data->rad->manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_no ) ? $data->rad->bpm_no : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->phisicalStatus->name ) ? $data->rad->phisicalStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_volume ) ? $data->rad->bpm_volume : '') . ' ' . (isset ( $data->rad->unit->name ) ? $data->rad->unit->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_as_of_date ) ? CommonUtil::getDateThai ( $data->rad->bpm_as_of_date ) : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_number ) ? $data->rad->bpm_number : '') . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . 'ห้อง' . $data->from_room->name . (CommonUtil::IsNullOrEmptyString ( $data->from_room->number ) ? '' : '(' . $data->from_room->number . ')') . ' ชั้น' . $data->from_room->floor . ' อาคาร' . $data->from_room->building_id . ' ' . $data->from_room->fac . '</td>';
					$str .= '<td style="text-align: center;">' . 'ห้อง' . $data->to_room->name . (CommonUtil::IsNullOrEmptyString ( $data->to_room->number ) ? '' : '(' . $data->to_room->number . ')') . ' ชั้น' . $data->to_room->floor . ' อาคาร' . $data->to_room->building_id . ' ' . $data->to_room->fac . '</td>';
					
					$str .= '<td style="text-align: center;">' . CommonUtil::getDateThai ( $data->date_from ) . '-' . CommonUtil::getDateThai ( $data->date_from ) . '</td>';
					$str .= '<td style="text-align: center;">' . $data->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานการกำจัดกากกัมมันตรังสี
	public function actionReport08() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T'";
			$datas = Form5::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานการกำจัดขยะรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่ส่งกำจัด</th>
								<th style="text-align: center;">ประเภทวัสดุกัมมันตรังสี</th>
								<th style="text-align: center;">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center;">สมบัติทางกายภาพ</th>
								<th style="text-align: center;">กัมมันตภาพสูงสุด<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th style="text-align: center;">หน่วยงาน/บริษัท ที่ส่งกำจัด</th>
			</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . CommonUtil::getDateThai ( $data->clear_date ) . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->types_of_radioactive_waste->name ) ? $data->types_of_radioactive_waste->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_radioactive_elements->name ) ? $data->rad->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->phisical_status->name ) ? $data->phisical_status->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $data->rad_or_maximum_weight . ' ' . (isset ( $data->unit->name ) ? $data->unit->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->department->branch_id ) ? 'สาขา ' . $data->department->branch_id : '') . ' ' . (isset ( $data->department->name ) ? 'ภาควิชา ' . $data->department->name : '') . ' ' . (isset ( $data->department->faculty->name ) ? $data->department->faculty->name : '') . '</td>';
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานอุบัติเหตุทางรังสี
	public function actionReport09() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and type=2";
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form6::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานอุบติเหตุทางรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่เกิด<br>อุบัติเหตุ</th>
								<th style="text-align: center;">สถานที่</th>
								<th style="text-align: center;">สถานการณ์</th>
								<th style="text-align: center;">สาเหตุที่ทำให้เกิด<br>อุบัติเหตุ</th>
								<th style="text-align: center;">จำนวนผู้ได้รับ<br>อันตราย(คน)</th>
								<th style="text-align: center;">ประมาณการ<br>ค่าเสียหาย (บาท)</th>
								<th style="text-align: center;">แนวทางป้องกันในอนาคต</th>
			</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					// $str .= '<td style="text-align: center;">' .(($data->accident_type_id ==1)? "อุบัติการณ์":"อุบัติเหตุ"). '</td>';
					$str .= '<td style="text-align: center;">' . CommonUtil::getDateThai ( $data->accident_date ) . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_room_id_text . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_situation . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_cause . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_count . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_estimated_loss . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_Prevention . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานบุคลากรที่เกี่ยวข้องกับการใช้รังสี
	public function actionReport10() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form4::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานบุคลากรที่เกี่ยวข้องกับการใช้รังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">ชื่อ – นามสกุล (ภาษาไทย)</th>
								<th style="text-align: center;">เจ้าหน้าที่ความปลอดภัยทางรังสี (RSO)</th>
								<th style="text-align: center;">เลขที่ใบอนุญาต (RSO)</th>
								<th style="text-align: center;">วันที่ใบอนุญาตหมดอายุ</th>
			</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: left;">' . $data->name . '</td>';
					
					$isRso = '[/] ใช่		[ ] ไม่ใช่';
					if ($data->is_rso == "0") {
						$isRso = '[ ] ใช่		[/] ไม่ใช่';
					}
					$str .= '<td style="text-align: center;">' . $isRso . '</td>';
					$str .= '<td style="text-align: center;">' . $data->rso_license_no . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rso_license_expire ) ? CommonUtil::getDateThai ( $data->rso_license_expire ) : '') . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานผลการตรวจวัดปริมาณรังสี
	public function actionReport11() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			

			$json = array ();
			$sql = "SELECT result,count(hp_10_volume) as hp10,count(hp_007_volume) as hp007,count(hp_3_volume) as hp3 FROM tb_form4 group by result";
			
			$command = Yii::app()->db->createCommand($sql);
			$ret= $command->queryAll();
			
			$json= array_values($ret);
	
			
			// $datas = Form6::model ()->findAll (); // $criteria );
			$str = '';
			// BEGIN HTML
			// TITLE
			$str .= "<h3>แบบรายงานผลการตรวจวัดปริมาณรังสี</h3><br />";
			$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
			$str .= "<br />";
			// TABLE
			$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style="text-align: center" rowspan="2">ค่าปริมาณรังสี</th>
			<th style="text-align: center;" colspan="2">ผลการประเมินปริมาณรังสีที่บุคลากรได้รับอยู่ในเกณฑ์</th>
			</tr>
			<tr>
			<th style="text-align: center;" rowspan="2">ปลอดภัย (คน)</th>
			<th style="text-align: center;" rowspan="2">ไม่ปลอดภัย (คน)</th>
			</tr>
			</thead>
			<tbody>';
			$str .= '<tr>';
			$str .= '<td style="text-align: center;">Hp (10)</td>';
			if (count ( $json ) > 0) {
				$str .= '<td style="text-align: center;">' . $json [0] ["hp10"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			if (count ( $json ) > 1) {
				$str .= '<td style="text-align: center;">' . $json [1] ["hp10"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			$str .= '</tr>';
			$str .= '<tr>';
			$str .= '<td style="text-align: center;">Hp (0.07)</td>';
			if (count ( $json ) > 0) {
				$str .= '<td style="text-align: center;">' . $json [0] ["hp007"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			if (count ( $json ) > 1) {
				$str .= '<td style="text-align: center;">' . $json [1] ["hp007"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			$str .= '</tr>';
			$str .= '<tr>';
			$str .= '<td style="text-align: center;">Hp (3)</td>';
			if (count ( $json ) > 0) {
				$str .= '<td style="text-align: center;">' . $json [0] ["hp3"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			if (count ( $json ) > 1) {
				$str .= '<td style="text-align: center;">' . $json [1] ["hp3"] . '</td>';
			} else {
				$str .= '<td style="text-align: center;"></td>';
			}
			$str .= '</tr>';
			
			$str .= '</tbody></table>';
			
			self::GeneratePDF ( $str );
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	// แบบรายงานการอบรมทางรังสี
	public function actionReport12() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$criteria = new CDbCriteria ();
			// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form7::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				$str = '';
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานอบรมทางรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">ชื่อ – นามสกุล (ภาษาไทย)</th>
								<th style="text-align: center;">หลักสูตรการอบรม</th>
								<th style="text-align: center;">หน่วยงานที่จัดอบรม</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่เข้ารับการอบรม</th>
			</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					$course = '';
					switch ($data->course_id) {
						case "1" :
							$course = 'การอบรมความปลอดภัยเบื้องต้น';
							break;
						case "2" :
							$course = 'การอบรมการป้องกันอันตรายจากรังสี ระดับป้อง 1 2';
							break;
					}
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: left;">' . $data->name . '</td>';
					$str .= '<td style="text-align: center;">' . $course . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->training_departments->name ) ? $data->training_departments->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->training_date ) ? CommonUtil::getDateThai ( $data->training_date ) : '') . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				self::GeneratePDF ( $str );
			}
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	
	/* -------------------------------- แบบฟอร์มหาลัย ----------------------------------- */
	public function actionReport13() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$criteria = new CDbCriteria ();
		if (isset ( $_POST ['Form1'] )) {
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			
			$str = '';
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form1::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานเครื่องกำเนิดรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อเครื่องมือ</th>
								<th style="text-align: center">รหัสเครื่องมือ</th>
								<th style="text-align: center">รุ่น</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อผู้ดูแลประจำเครื่อง</th>
								<th style="text-align: center">หมายเลขโทรศัพท์</th>
								<th style="text-align: center">สถานะการใช้งาน</th>
								<th style="text-align: center">สถานที่ติดตั้ง</th>
								<th style="text-align: center">วันที่ทำการติดตั้งเครื่องมือ</th>
								<th style="text-align: center">วันที่ทำการปรับเทียบเครื่องมือ</th>
								<th style="text-align: center">หน่วยงาน/ผู้ปรับเทียบเครื่องมือ</th>
								<th style="text-align: center">วันที่ตรวจคุณภาพจากกรมวิทยาศาสตร์การแพทย์</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center">' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->rad_machine->name ) ? $item->rad_machine->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->code_usage->name ) ? $item->code_usage->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . $item->model . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					$str .= '<td style="text-align: center" >' . $item->license_expire_date . '</td>';
					$str .= '<td style="text-align: center" >' . $item->machine_owner . '</td>';
					$str .= '<td style="text-align: center" >' . $item->machine_owner_phone . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->usage_status->name ) ? $item->usage_status->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . 'ห้อง' . $item->room->name . (CommonUtil::IsNullOrEmptyString ( $item->room->number ) ? '' : '(' . $item->room->number . ')') . ' ชั้น' . $item->room->floor . ' อาคาร' . $item->room->building_id . ' ' . $item->room->fac . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->delivery_date_day . '/' . $item->delivery_date_month . '/' . $item->delivery_date_year . '</td>';
					$str .= '<td style="text-align: center" >' . '' . '</td>';
					$str .= '<td style="text-align: center" >' . CommonUtil::getInspectionAgencyId ( $item->inspection_agency_id ) . '</td>';
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThaiMoreOne ( $item->quality_check_date ) . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
			}
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=1";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=1";
					break;
			}
			
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานวัสดุกัมมันตรังสีชนิดปิดผนึก</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center">สถานภาพวัสดุ</th>
								<th style="text-align: center">สมบัติทางกายภาพ</th>
								<th style="text-align: center">กัมมันตภาพสูงสุด/น้ำหนัก</th>
								<th style="text-align: center">ปริมาณ</th>
								<th style="text-align: center">สถานที่จัดเก็บ</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อ – นามสกุลผู้ดูแล</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center" >' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
					
					$str .= '<td style="text-align: center" >' . '-' . '</td>';
					$str .= '<td style="text-align: center" >' . $item->bpm_volume . '  ' . (isset ( $item->unit->name ) ? $item->unit->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->room->name ) ? $item->room->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThai ( $item->license_expire_date ) . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
			}
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T' and license_no <> ''";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and license_no <> '' and t.type=2";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T' and license_no <> '' and t.type=2";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T' and license_no <> '' and t.type=2";
					break;
			}
			
			$datas = Form2::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานวัสดุกัมมันตรังสีชนิดไม่ปิดผนึก</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left" border="1" cellpadding="1" cellspacing="1" id="cssTable">
			<thead>
							<tr>
								<th style="text-align: center">ลำดับ</th>
								<th style="text-align: center">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center">สถานภาพวัสดุ</th>
								<th style="text-align: center">สมบัติทางกายภาพ</th>
								<th style="text-align: center">กัมมันตภาพสูงสุด/น้ำหนัก</th>
								<th style="text-align: center">ปริมาณ</th>
								<th style="text-align: center">สถานที่จัดเก็บ</th>
								<th style="text-align: center">เลขที่ใบอนุญาต</th>
								<th style="text-align: center">วันที่ใบอนุญาตหมดอายุ</th>
								<th style="text-align: center">ชื่อ – นามสกุลผู้ดูแล</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $item ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center" >' . $index . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->bpm_radioactive_elements->name ) ? $item->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->materialStatus->name ) ? $item->materialStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->phisicalStatus->name ) ? $item->phisicalStatus->name : '') . '</td>';
					
					$str .= '<td style="text-align: center" >' . '-' . '</td>';
					$str .= '<td style="text-align: center" >' . $item->bpm_volume . '  ' . (isset ( $item->unit->name ) ? $item->unit->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . (isset ( $item->room->name ) ? $item->room->name : '') . '</td>';
					$str .= '<td style="text-align: center" >' . $item->license_no . '</td>';
					
					$str .= '<td style="text-align: center" >' . CommonUtil::getDateThai ( $item->license_expire_date ) . '</td>';
					
					$str .= '<td style="text-align: center" >' . $item->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
			}
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
			}
			
			$datas = Form3::model ()->findAll ( $criteria );
			
			if (isset ( $datas )) {
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานการเคลื่อนย้ายวัสดุกัมมันตรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
							<tr>
								<th rowspan="3" style="text-align: center;">ลำดับ</th>
								<th rowspan="3" style="text-align: center;">ทะเบียน<br>อ้างอิง</th>
								<th rowspan="3" style="text-align: center;">รหัสประเภท<br>การใช้งาน</th>
								<th colspan="8" style="text-align: center;">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4" style="text-align: center;">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th colspan="2" style="text-align: center;">สถานที่เก็บรักษา/สถานที่<br>ใช้งาน</th>
								<th rowspan="3" style="text-align: center;">ดำเนินการ<br>เคลื่อนย้าย<br>ตั้งแต่วันที่ - ถึงวันที่</th>
								<th rowspan="3" style="text-align: center;">ผู้ควบคุม</th>
							</tr>
							<tr>
								<th rowspan="2" style="text-align: center;">ธาตุ-เลขมวล</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">หมายเลขวัสดุ</th>
								<th rowspan="2" style="text-align: center;">สมบัติทาง<br>กายภาพ</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
				
								<th rowspan="2" style="text-align: center;">ผู้ผลิต</th>
								<th rowspan="2" style="text-align: center;">รุ่น/รหัสสินค้า</th>
								<th rowspan="2" style="text-align: center;">หมายเลข</th>
								<th rowspan="2" style="text-align: center;">กัมมันตภาพ<br>หรือน้ำหนัก</th>
				
								<th rowspan="2" style="text-align: center;">เดิม</th>
								<th rowspan="2" style="text-align: center;">ไปที่</th>
							</tr>
							<tr>
								<th style="text-align: center;">ปริมาณ</th>
								<th style="text-align: center;">ณ วันที่</th>
								<th style="text-align: center;">จำนวน</th>
							</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->code_usage->name ) ? $data->rad->code_usage->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_radioactive_elements->name ) ? $data->rad->bpm_radioactive_elements->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_model ) ? $data->rad->bpm_model : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->manufacturer->name ) ? $data->rad->manufacturer->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_no ) ? $data->rad->bpm_no : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->phisicalStatus->name ) ? $data->rad->phisicalStatus->name : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_volume ) ? $data->rad->bpm_volume : '') . ' ' . (isset ( $data->rad->unit->name ) ? $data->rad->unit->name : '') . '</td>';
					
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_as_of_date ) ? CommonUtil::getDateThai ( $data->rad->bpm_as_of_date ) : '') . '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_number ) ? $data->rad->bpm_number : '') . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . '' . '</td>';
					$str .= '<td style="text-align: center;">' . 'ห้อง' . $data->from_room->name . (CommonUtil::IsNullOrEmptyString ( $data->from_room->number ) ? '' : '(' . $data->from_room->number . ')') . ' ชั้น' . $data->from_room->floor . ' อาคาร' . $data->from_room->building_id . ' ' . $data->from_room->fac . '</td>';
					$str .= '<td style="text-align: center;">' . 'ห้อง' . $data->to_room->name . (CommonUtil::IsNullOrEmptyString ( $data->to_room->number ) ? '' : '(' . $data->to_room->number . ')') . ' ชั้น' . $data->to_room->floor . ' อาคาร' . $data->to_room->building_id . ' ' . $data->to_room->fac . '</td>';
					
					$str .= '<td style="text-align: center;">' . CommonUtil::getDateThai ( $data->date_from ) . '-' . CommonUtil::getDateThai ( $data->date_from ) . '</td>';
					$str .= '<td style="text-align: center;">' . $data->supervisor_name . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
			}
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form5::model ()->findAll ( $criteria );
			// BEGIN HTML
			// TITLE
			$str .= "<h3>แบบรายงานการกำจัดขยะรังสี</h3><br />";
			$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
			$str .= "<br />";
			// TABLE
			$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่ส่งกำจัด</th>
								<th style="text-align: center;">ประเภทวัสดุกัมมันตรังสี</th>
								<th style="text-align: center;">ชื่อวัสดุกัมมันตรังสี</th>
								<th style="text-align: center;">สมบัติทางกายภาพ</th>
								<th style="text-align: center;">กัมมันตภาพสูงสุด<br>หรือน้ำหนัก<br>(Bq, Ci, Kg, Lb)</th>
								<th style="text-align: center;">หน่วยงาน/บริษัท ที่ส่งกำจัด</th>
			</tr>
			</thead>
			<tbody>';
			// BODY
			$index = 1;
			foreach ( $datas as $data ) {
				
				$str .= '<tr>';
				$str .= '<td style="text-align: center;">' . $index . '</td>';
				$str .= '<td style="text-align: center;">' . (isset ( $data->clear_date ) ? CommonUtil::getDateThai ( $data->clear_date ) : '') . '</td>';
				$str .= '<td style="text-align: center;">' . (isset ( $data->types_of_radioactive_waste->name ) ? $data->types_of_radioactive_waste->name : '') . '</td>';
				$str .= '<td style="text-align: center;">' . (isset ( $data->rad->bpm_radioactive_elements->name ) ? $data->rad->bpm_radioactive_elements->name : '') . '</td>';
				$str .= '<td style="text-align: center;">' . (isset ( $data->phisical_status->name ) ? $data->phisical_status->name : '') . '</td>';
				$str .= '<td style="text-align: center;">' . $data->rad_or_maximum_weight . ' ' . $data->unit->name . '</td>';
				$str .= '<td style="text-align: center;">' . (isset ( $data->department->branch_id ) ? 'สาขา ' . $data->department->branch_id : '') . ' ' . (isset ( $data->department->name ) ? 'ภาควิชา ' . $data->department->name : '') . ' ' . (isset ( $data->department->faculty->name ) ? $data->department->faculty->name : '') . '</td>';
				$str .= '</tr>';
				$index ++;
			}
			// END TABLE
			$str .= '</tbody></table>';
			
			$criteria = new CDbCriteria ();
			$criteria->with = array (
					'owner_department' 
			);
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
			}
			$datas = Form6::model ()->findAll ( $criteria );
			if (isset ( $datas )) {
				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานอุบติเหตุทางรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่เกิด<br>อุบัติเหตุ</th>
								<th style="text-align: center;">สถานที่</th>
								<th style="text-align: center;">สถานการณ์</th>
								<th style="text-align: center;">สาเหตุที่ทำให้เกิด<br>อุบัติเหตุ</th>
								<th style="text-align: center;">จำนวนผู้ได้รับ<br>อันตราย(คน)</th>
								<th style="text-align: center;">ประมาณการ<br>ค่าเสียหาย (บาท)</th>
								<th style="text-align: center;">แนวทางป้องกันในอนาคต</th>
			</tr>
			</thead>
			<tbody>';
				// BODY
				$index = 1;
				foreach ( $datas as $data ) {
					
					$str .= '<tr>';
					$str .= '<td style="text-align: center;">' . $index . '</td>';
					// $str .= '<td style="text-align: center;">' .(($data->accident_type_id ==1)? "อุบัติการณ์":"อุบัติเหตุ"). '</td>';
					$str .= '<td style="text-align: center;">' . (isset ( $data->accident_date ) ? CommonUtil::getDateThai ( $data->accident_date ) : '') . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_room_id_text . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_situation . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_cause . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_count . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_estimated_loss . '</td>';
					$str .= '<td style="text-align: center;">' . $data->accident_Prevention . '</td>';
					
					$str .= '</tr>';
					$index ++;
				}
				// END TABLE
				$str .= '</tbody></table>';
				
				// ///
				$criteria = new CDbCriteria ();
				// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
				$criteria->with = array (
						'owner_department' 
				);
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria->condition = " t.status ='T'";
						break;
					case UserLoginUtils::USER :
						$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
						break;
					case UserLoginUtils::STAFF :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
						break;
				}
				$datas = Form4::model ()->findAll ( $criteria );
				if (isset ( $datas )) {
					// BEGIN HTML
					// TITLE
					$str .= "<h3>แบบรายงานบุคลากรที่เกี่ยวข้องกับการใช้รังสี</h3><br />";
					$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
					$str .= "<br />";
					// TABLE
					$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">ชื่อ – นามสกุล (ภาษาไทย)</th>
								<th style="text-align: center;">เจ้าหน้าที่ความปลอดภัยทางรังสี (RSO)</th>
								<th style="text-align: center;">เลขที่ใบอนุญาต (RSO)</th>
								<th style="text-align: center;">วันที่ใบอนุญาตหมดอายุ</th>
			</tr>
			</thead>
			<tbody>';
					// BODY
					$index = 1;
					foreach ( $datas as $data ) {
						$str .= '<tr>';
						$str .= '<td style="text-align: center;">' . $index . '</td>';
						$str .= '<td style="text-align: left;">' . $data->name . '</td>';
						
						$isRso = '[/] ใช่		[ ] ไม่ใช่';
						if ($data->is_rso == "0") {
							$isRso = '[ ] ใช่		[/] ไม่ใช่';
						}
						$str .= '<td style="text-align: center;">' . $isRso . '</td>';
						$str .= '<td style="text-align: center;">' . $data->rso_license_no . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $data->rso_license_expire ) ? CommonUtil::getDateThai ( $data->rso_license_expire ) : '') . '</td>';
						
						$str .= '</tr>';
						$index ++;
					}
					// END TABLE
					$str .= '</tbody></table>';
				}
				///
				
				$json = array ();
				$sql = "SELECT result,count(hp_10_volume) as hp10,count(hp_007_volume) as hp007,count(hp_3_volume) as hp3 FROM tb_form4 group by result";
				
				$command = Yii::app()->db->createCommand($sql);
				$ret= $command->queryAll();
				
				$json= array_values($ret);

				// BEGIN HTML
				// TITLE
				$str .= "<h3>แบบรายงานผลการตรวจวัดปริมาณรังสี</h3><br />";
				$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
				$str .= "<br />";
				// TABLE
				$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style="text-align: center" rowspan="2">ค่าปริมาณรังสี</th>
			<th style="text-align: center;" colspan="2">ผลการประเมินปริมาณรังสีที่บุคลากรได้รับอยู่ในเกณฑ์</th>
			</tr>
			<tr>
			<th style="text-align: center;" rowspan="2">ปลอดภัย (คน)</th>
			<th style="text-align: center;" rowspan="2">ไม่ปลอดภัย (คน)</th>
			</tr>
			</thead>
			<tbody>';
				$str .= '<tr>';
				$str .= '<td style="text-align: center;">Hp (10)</td>';
				if (count ( $json ) > 0) {
					$str .= '<td style="text-align: center;">' . $json [0] ["hp10"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				if (count ( $json ) > 1) {
					$str .= '<td style="text-align: center;">' . $json [1] ["hp10"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				$str .= '</tr>';
				$str .= '<tr>';
				$str .= '<td style="text-align: center;">Hp (0.07)</td>';
				if (count ( $json ) > 0) {
					$str .= '<td style="text-align: center;">' . $json [0] ["hp007"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				if (count ( $json ) > 1) {
					$str .= '<td style="text-align: center;">' . $json [1] ["hp007"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				$str .= '</tr>';
				$str .= '<tr>';
				$str .= '<td style="text-align: center;">Hp (3)</td>';
				if (count ( $json ) > 0) {
					$str .= '<td style="text-align: center;">' . $json [0] ["hp3"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				if (count ( $json ) > 1) {
					$str .= '<td style="text-align: center;">' . $json [1] ["hp3"] . '</td>';
				} else {
					$str .= '<td style="text-align: center;"></td>';
				}
				$str .= '</tr>';
				
				$str .= '</tbody></table>';
				///
				/////
				$criteria = new CDbCriteria ();
				// $criteria->condition = " t.owner_department_id = " . $model->owner_department_id . " and t.status ='T' and license_no <> '' and type=1";
				$criteria->with = array (
						'owner_department'
				);
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria->condition = " t.status ='T'";
						break;
					case UserLoginUtils::USER :
						$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE') and t.status ='T'";
						break;
					case UserLoginUtils::STAFF :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('EXECUTIVE_APPROVE')  and t.status ='T'";
						break;
				}
				$datas = Form7::model ()->findAll ( $criteria );
				if (isset ( $datas )) {
					// BEGIN HTML
					// TITLE
					$str .= "<h3>แบบรายงานอบรมทางรังสี</h3><br />";
					$str .= '<table style="width: 100%">' . '<tr style="text-align: left">' . '<td>' . 'สาขา' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->branch_id) . '  ภาควิชา/หน่วยงาน' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->name) . '  ' . ((! isset ( $datas [0] )) ? '-' : $datas [0]->owner_department->faculty->name) . '' . '</td>' . '</tr>' . '</table>';
					$str .= "<br />";
					// TABLE
					$str .= '<table style="text-align:left;font-family:arial;font-size:12px;" border="1" cellpadding="1" cellspacing="1" id="cssTable">
				<thead>
			<tr>
			<th style = "text-align: center">ลำดับ</th>
								<th style="text-align: center;">ชื่อ – นามสกุล (ภาษาไทย)</th>
								<th style="text-align: center;">หลักสูตรการอบรม</th>
								<th style="text-align: center;">หน่วยงานที่จัดอบรม</th>
								<th style="text-align: center;">วัน/เดือน/ปี ที่เข้ารับการอบรม</th>
			</tr>
			</thead>
			<tbody>';
					// BODY
					$index = 1;
					foreach ( $datas as $data ) {
						$course = '';
						switch ($data->course_id) {
							case "1" :
								$course = 'การอบรมความปลอดภัยเบื้องต้น';
								break;
							case "2" :
								$course = 'การอบรมการป้องกันอันตรายจากรังสี ระดับป้อง 1 2';
								break;
						}
						$str .= '<tr>';
						$str .= '<td style="text-align: center;">' . $index . '</td>';
						$str .= '<td style="text-align: left;">' . $data->name . '</td>';
						$str .= '<td style="text-align: center;">' . $course . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $data->training_departments->name ) ? $data->training_departments->name : '') . '</td>';
						$str .= '<td style="text-align: center;">' . (isset ( $data->training_date ) ? CommonUtil::getDateThai ( $data->training_date ) : '') . '</td>';
						
						$str .= '</tr>';
						$index ++;
					}
					// END TABLE
					$str .= '</tbody></table>';
				}
				/////
			}
			
			// /
			// create new PDF document
			$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
			// Set font
			$pdf->SetFont ( 'thsarabun', '', 14 );
			$pdf->AddPage ();
			// Print text using writeHTMLCell()
			$pdf->writeHTMLCell ( 0, 0, '', '', $str, 0, 1, 0, true, '', true );
			
			// Close and output PDF document
			$tmp_pdf_file = dirname ( __FILE__ ) . '../../../uploads/report_' . date ( "Y-m-d" ) . '_' . str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ) . '.pdf';
			$pdf->Output ( $tmp_pdf_file, 'F' );
			
			// initiate FPDI
			$fpdi = new FPDI ();
			// add a page
			$fpdi->AddPage ();
			// set the source file
			$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template04.pdf' );
			$fpdi->useTemplate ( $fpdi->importPage ( 1 ) );
			$fpdi->AddPage ();
			$fpdi->useTemplate ( $fpdi->importPage ( 2 ) );
			$fpdi->AddPage ();
			$fpdi->useTemplate ( $fpdi->importPage ( 3 ) );
			$fpdi->AddPage ();
			$fpdi->useTemplate ( $fpdi->importPage ( 4 ) );
			$fpdi->AddPage ();
			$fpdi->useTemplate ( $fpdi->importPage ( 5 ) );
			$fpdi->AddPage ( 'L' );
			
			$pagecount = $fpdi->setSourceFile ( $tmp_pdf_file );
			for($x = 1; $x <= $pagecount; $x ++) {
				$fpdi->useTemplate ( $fpdi->importPage ( $x ) );
				if ($x < $pagecount) {
					$fpdi->AddPage ( 'L' );
				}
			}
			
			$fpdi->AddPage ( 'L' );
			$fpdi->setSourceFile ( dirname ( __FILE__ ) . '../../../docs/template/template04.pdf' );
			$fpdi->useTemplate ( $fpdi->importPage ( 10 ) );
			
			// if (file_exists($tmp_pdf_file)) { unlink ($tmp_pdf_file); }
			
			$fpdi->Output ();
		} else {
			$dataProvider = new CActiveDataProvider ( "Form1", array (
					'criteria' => $criteria 
			) );
			
			$this->render ( '//report/report01', array (
					'dataProvider' => $dataProvider 
			) );
		}
	}
	
	/* ---------- */
	// http://www.fpdf.org/makefont/make.php
	public function GeneratePDF($str) {
		ob_end_clean ();
		// create new PDF document
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		// Set font
		$pdf->SetFont ( 'thsarabun', '', 14 );
		$pdf->AddPage ();
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell ( 0, 0, '', '', $str, 0, 1, 0, true, '', true );
		
		// $tmp_pdf_file = dirname ( __FILE__ ) . '../../../uploads/report_' . date ( "Y-m-d" ) . '_' . str_pad ( mt_rand ( 0, 999999 ), 6, '0', STR_PAD_LEFT ) . '.pdf';
		// $pdf->Output ( $tmp_pdf_file, 'F' );
		// Close and output PDF document
		$pdf->Output ();
	}
}