<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class Form6Controller extends CController {
	const CRLF = "\r\n";
	public $layout = '_main';
	private $_model;
	public function actionIndex() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = new Form6 ();
		$this->render ( '//form6/main', array (
				'data' => $model 
		) );
	}
	public function actionApproveStatus() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
	
		$approve_status = addslashes ( $_GET ['approve_status'] );
	
		$model = $this->loadModel ();
		$model->approve_status = $approve_status; // Delete
		$model->update ();
		//---------------------------------------------
		//MAIL APPROVED
		//---------------------------------------------
		$strSubject = "=?UTF-8?B?" . base64_encode ( ConfigUtil::getApplicationTitle() ) . "?=";
		$approve ='';
		switch($approve_status){
			case UserLoginUtils::INIT_APPROVE:
				$approve=UserLoginUtils::STAFF;
				break;
			case UserLoginUtils::STAFF_APPROVE:
				$approve=UserLoginUtils::EXECUTIVE;
				break;
		}
		$Approver = UserLoginUtils::getApprover($approve);
		if (isset ( $Approver )) {
			$detail = '  ( อุบัติเหตุทางรังสี ประเภท ' . (($model->accident_type_id ==1)? "อุบัติการณ์":"อุบัติเหตุ") .' สถานการณ์ '.$model->accident_situation.')';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		//---------------------------------------------
		//END
		//---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form6/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form6 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form6/main', array (
				'data' => $model 
		) );
	}
	public function actionCreate() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		if (isset ( $_POST ['Form6'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Form6 ();
			$model->attributes = $_POST ['Form6'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			
			$model->accident_date = CommonUtil::getDate ( $model->accident_date );
			if(isset( $_FILES ['file_path'])){
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->document_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			}
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $addSuccess = true;
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form6' ) );
		} else {
			// Render
			$this->render ( '//form6/create' );
		}
	}
	public function actionDelete() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = $this->loadModel ();
		$model->status = 'D'; // Delete
		$model->update ();
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form6/' ) );
	}
	public function actionUpdate() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$updateModel = $this->loadModel ();
		if (isset ( $_POST ['Form6'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			$model = new Form6 ();
			
			$model->attributes = $_POST ['Form6'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = ($updateModel->revision + 1);
			$model->update_from_id = $updateModel->update_from_id;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
// 			$model->bpm_as_of_date = CommonUtil::getDate ( $model->bpm_as_of_date );
			$model->accident_date = CommonUtil::getDate ( $model->accident_date );
			
			$model->update_from_id = $updateModel->update_from_id;
			$model->document_path = $updateModel->document_path;
			$model->approve_status= $updateModel->approve_status;
			if(isset( $_FILES ['file_path'])){
				
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->document_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			}
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form6' ) );
		}
		
		$this->render ( '//form6/update', array (
				'data' => $updateModel 
		) );
	}
	public function actionView() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = $this->loadModel ();
		
		$this->render ( '//form6/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form6::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
	public function actionReport1() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		/* รายงาน ปส. */
		$criteria = new CDbCriteria ();
		switch (UserLoginUtils::getUserRoleName ()) {
			case UserLoginUtils::ADMIN :
			case UserLoginUtils::EXCUTIVE :
				break;
			case UserLoginUtils::STAFF :
			case UserLoginUtils::USER :
				$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId ();
				break;
		}
		$datas = Form6::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบรายงานการรั่วไหลของวัสดุพลอยได้ที่อยู่ในความครอบครอง' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ตามกฎกระทรวงกำหนดเงื่อนไขวิธีการขอรับใบอนุญาต และการดำเนินการเกี่ยวกับวัสดุนิวเคลียร์พิเศษ' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'วัสดุต้นกำลัง วัสดุพลอยได้ หรือพลังงานปรมาณู พ.ศ. ๒๕๕๐  ข้อ ๓๖' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ออกตามความใน พระราชบัญญัติพลังงานปรมาณูเพื่อสันติพลังงานปรมาณูเพื่อสันติ พ.ศ. ๒๕๐๔' ) . '<br /><br />' . self::CRLF . 

		Yii::t ( 'main', '๑.		ชื่อสถานประกอบกิจการ  xxxxxxxxxxxxxxxxxxxxxxxxx มหาวิทยาลัยมหิดล' ) . '<br /><br />' . self::CRLF . Yii::t ( 'main', 'ที่ตั้งเลขที่ xxxxxxxxx หมู่ xxxxxxxx ตรอก/ซอย xxxxxxxx ถนน xxxxxxxxx แขวง/ตำบล xxxxxxxxx' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'เขต/อำเภอ xxxxxxxxx จังหวัด xxxxxxxxx รหัสไปรษณีย์ xxxxxxxxxx โทรศัพท์ xxxxxxxxxx โทรสาร xxxxxxxxxx E-mail xxxxxxxxxxx ' ) . '<br />' . self::CRLF . Yii::t ( 'main', '๒.    รหัสหน่วยงานเลขที่ xxxxxxxxxxx แบบรายงาน สร๓ ตามใบอนุญาต เลขที่ xxxxxxxxxxxxxxxx ' ) . '<br /><br />' . self::CRLF . Yii::t ( 'main', '๓.		ขอแจ้งการรั่วไหลของวัสดุพลอยได้ที่มีไว้ในครอบครองหรือใช้ ดังนี้' ) . '<br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th rowspan="3">ลำดับ</th>
								<th rowspan="3">ทะเบียนอ้างอิง</th>
								<th colspan="8">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th rowspan="3">สถานที่เก็บรักษา/สถานที่ใช้งาน</th>
								<th colspan="2">ปริมาณกัมมันตภาพหรือน้ำหนัก</th>
							</tr>
							<tr>
								<th rowspan="2">ธาตุ-เลขมวล</th>
								<th rowspan="2">รุ่น/รหัสสินค้า</th>
								<th rowspan="2">ผู้ผลิต</th>
								<th rowspan="2">หมายเลขวัสดุ</th>
								<th rowspan="2">สมบัติทางกายภาพ</th>
								<th colspan="3" style="text-align: center;">กัมมันตภาพหรือน้ำหนัก
									(Bq,Ci,Kg,Lb)</th>
								<th rowspan="2">ผู้ผลิต</th>
								<th rowspan="2">รุ่น/รหัสสินค้า</th>
								<th rowspan="2">หมายเลข</th>
								<th rowspan="2" style="text-align: center;">กัมมันตภาพหรือน้ำหนัก
									(Bq,Ci,Kg,Lb)</th>
	
								<th rowspan="2">ก่อนการรั่วไหล</th>
								<th rowspan="2">หลังการรั่วไหล</th>
							</tr>
							<tr>
								<th>ปริมาณ</th>
								<th>ณ วันที่</th>
								<th>จำนวน</th>
							</tr>
						</thead>
							<tbody>';
		// BODY
		$index = 1;
		foreach ( $datas as $item ) {
			
			$str .= '<tr>';
			$str .= '<td>' . $index . '</td>';
			$str .= '<td>' . $item->ref_doc . '</td>';
			$str .= '<td>' . $item->bpm_radioactive_elements->name . '</td>';
			$str .= '<td>' . $item->bpm_manufacturer->name . '</td>';
			$str .= '<td>' . $item->bpm_model . '</td>';
			$str .= '<td>' . $item->bpm_no . '</td>';
			
			$str .= '<td>' . $item->phisical_status->name . '</td>';
			$str .= '<td>' . $item->bpm_volume . '</td>';
			$str .= '<td>' . $item->bpm_as_of_date . '</td>';
			$str .= '<td>' . $item->bpm_number . '</td>';
			$str .= '<td>' . $item->machine_manufacturer->name . '</td>';
			$str .= '<td>' . $item->machine_model . '</td>';
			$str .= '<td>' . $item->machine_number . '</td>';
			$str .= '<td>' . $item->machine_radioactive_highest . '</td>';
			
			$str .= '<td>' . $item->room->name . '</td>';
			$str .= '<td>' . $item->previous_leaks . '</td>';
			$str .= '<td>' . $item->after_leaks . '</td>';
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt6_01_' . date ( "Y-m-d" ), $str );
	}
	public function actionReport2() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		/* รายงาน ปส. */
		$criteria = new CDbCriteria ();
		switch (UserLoginUtils::getUserRoleName ()) {
			case UserLoginUtils::ADMIN :
			case UserLoginUtils::EXCUTIVE :
				break;
			case UserLoginUtils::STAFF :
			case UserLoginUtils::USER :
				$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId ();
				break;
		}
		$datas = Form6::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบรายงานด้านความปลอดภัยทางรังสี มหาวิทยาลัยมหิดล' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'อุบัติการณ์/อุบัติเหตุทางรังสี' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ภาควิชา ' . UserLoginUtils::getUsersLogin ()->department->name . ' สาขา ' . UserLoginUtils::getUsersLogin ()->department->branch_id . '  คณะ ' . UserLoginUtils::getUsersLogin ()->department->faculty_id . '' ) . '<br /><br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
								<th>ลำดับ</th>
								<th>ประเภทอุบัติเหตุ</th>
								<th>วัน/เดือน/ปี ที่เกิดอุบัติเหตุ</th>
								<th>สถานที่</th>
								<th>สถานการณ์</th>
								<th>สาเหตุที่ทำให้เกิดอุบัติเหตุ</th>
								<th>จำนวนผู้ได้รับอันตราย</th>
								<th>ประมาณการค่าเสียหาย (บาท)</th>
								<th>แนวทางป้องกันในอนาคต</th>
						</thead>
							<tbody>';
		// BODY
		$index = 1;
		foreach ( $datas as $item ) {
			
			$str .= '<tr>';
			$str .= '<td>' . $index . '</td>';
			$str .= '<td>' . (($item->accident_type_id == 1) ? "อุบัติการณ์" : "อุบัติเหตุ") . '</td>';
			$str .= '<td>' . $item->accident_date . '</td>';
			$str .= '<td>' . $item->accident_room->name . '</td>';
			$str .= '<td>' . $item->accident_situation . '</td>';
			$str .= '<td>' . $item->accident_cause . '</td>';
			$str .= '<td>' . $item->accident_count . '</td>';
			$str .= '<td>' . $item->accident_estimated_loss . '</td>';
			$str .= '<td>' . $item->accident_Prevention . '</td>';
			
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt6_02_' . date ( "Y-m-d" ), $str );
	}
}