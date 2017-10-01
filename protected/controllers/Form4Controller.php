<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class Form4Controller extends CController {
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
		$model = new Form4 ();
		$this->render ( '//form4/main', array (
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
		// ---------------------------------------------
		// MAIL APPROVED
		// ---------------------------------------------
		$strSubject = "=?UTF-8?B?" . base64_encode ( ConfigUtil::getApplicationTitle () ) . "?=";
		$approve = '';
		switch ($approve_status) {
			case UserLoginUtils::INIT_APPROVE :
				$approve = UserLoginUtils::STAFF;
				break;
			case UserLoginUtils::STAFF_APPROVE :
				$approve = UserLoginUtils::EXECUTIVE;
				break;
		}
		$Approver = UserLoginUtils::getApprover ( $approve );
		if (isset ( $Approver )) {
			$detail = '  ( บุคลากรผู้ปฏิบัติงานทางรังสีปริมาณรังสีที่ได้รับ  ชื่อ ' . $model->name . ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		// ---------------------------------------------
		// END
		// ---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form4/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form4 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form4/revision', array (
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
		
		if (isset ( $_POST ['Form4'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// // Add Request
			$model = new Form4 ();
			$model->attributes = $_POST ['Form4'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			$_is_rso_actual_work = "0";
			if (isset ( $_POST ['is_rso_actual_work'] )) {
				foreach ( $_POST ['is_rso_actual_work'] as $item ) {
					$_is_rso_actual_work = $item;
				}
			}
			$model->is_rso_actual_work = $_is_rso_actual_work;
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $addSuccess = true;
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form4' ) );
			
			// echo '===>' . $model->is_rso_staff;
		} else {
			// Render
			$this->render ( '//form4/create' );
		}
	}
	public function actionImportFile() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		if (isset ( $_FILES ['file_path']['name'])) {
			if ($_FILES ['file_path'] ['name']) {
				$transaction = Yii::app ()->db->beginTransaction ();
				// if no errors...
				if (! $_FILES ['file_path'] ['error']) {
					
					$currentdir = getcwd ();
					
					$target = $currentdir . "/uploads/";
					$target = $target . basename ( $_FILES ['file_path'] ['name'] );
					
					if (move_uploaded_file ( $_FILES ['file_path'] ['tmp_name'], $target )) {
						// try {
						
						$inputFileType = PHPExcel_IOFactory::identify ( $target );
						$objReader = PHPExcel_IOFactory::createReader ( $inputFileType );
						$objPHPExcel = $objReader->load ( $target );
						$sheet = $objPHPExcel->getSheet ( 0 );
						$highestRow = $sheet->getHighestRow ();
						$highestColumn = $sheet->getHighestColumn ();
						
						for($row = 1; $row <= $highestRow; $row ++) {
							if ($row > 1) {
								$model = new Form4 ();
// 								$model->attributes = $_POST ['Form4'];
								$model->create_by = UserLoginUtils::getUsersLoginId ();
								$model->create_date = date ( "Y-m-d H:i:s" );
								$model->update_by = UserLoginUtils::getUsersLoginId ();
								$model->update_date = date ( "Y-m-d H:i:s" );
								$model->revision = 1;
								$model->owner_department_id = UserLoginUtils::getDepartmentId ();
								$model->status = 'T';
								
								$model->rso_level_id = $sheet->getCell ( "B" . $row )->getValue ();
								$model->name = $sheet->getCell ( "C" . $row )->getValue ();
								$model->hp_10_volume = $sheet->getCell ( "D" . $row )->getValue ();
								$model->hp_007_volume = $sheet->getCell ( "E" . $row )->getValue ();
								$model->hp_3_volume = $sheet->getCell ( "F" . $row )->getValue ();
								$model->result = $sheet->getCell ( "G" . $row )->getValue ();
								$model->report_year = $sheet->getCell ( "H" . $row )->getValue ();
								$model->report_month = $sheet->getCell ( "I" . $row )->getValue ();
								$model->report_department_id = $sheet->getCell ( "J" . $row )->getValue ();
								$model->save ();
							}
						}
						// } catch ( Exception $e ) {
						// die ( 'Error loading file "' . pathinfo ( $target, PATHINFO_BASENAME ) . '": ' . $e->getMessage () );
						// }
					} else {
						echo "Sorry, there was a problem uploading your file.";
					}
				}
				$transaction->commit ();
				$this->redirect ( Yii::app ()->createUrl ( 'Form4' ) );
			} else {
				// Render
				$this->render ( '//form4/importfile' );
			}
		}else{
			$this->render ( '//form4/importfile' );
			
		}
	}
	public function actionAttachFile() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		if (isset ( $_POST ['Form4'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// // Add Request
			$model = new Form4 ();
			$model->attributes = $_POST ['Form4'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->description = 'ATTACH_FILE';
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
						$model->file_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $addSuccess = true;
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form4' ) );
			
			// echo '===>' . $model->is_rso_staff;
		} else {
			// Render
			$this->render ( '//form4/attachfile' );
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
		$this->redirect ( Yii::app ()->createUrl ( 'Form4/' ) );
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
		if (isset ( $_POST ['Form4'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			
			$model = new Form4 ();
			$model->attributes = $_POST ['Form4'];
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			
			$model->revision = ($updateModel->revision + 1);
			$model->status = 'T';
			$model->update_from_id = $updateModel->update_from_id;
			$model->file_path = $updateModel->file_path;
			$model->approve_status= $updateModel->approve_status;
			
			$_is_rso_actual_work = "0";
			if (isset ( $_POST ['is_rso_actual_work'] )) {
				foreach ( $_POST ['is_rso_actual_work'] as $item ) {
					$_is_rso_actual_work = $item;
				}
			}
			$model->is_rso_actual_work = $_is_rso_actual_work;
			
			// $model->report_date = CommonUtil::getDate ( $model->report_date );
			// $file_path = $_FILES ['file_path'];
			// if (isset ( $file_path )) {
			// $file_ary = CommonUtil::reArrayFiles ( $file_path );
			
			// $index = 0;
			// foreach ( $file_ary as $file ) {
			// if ($file ['size'] > 0) {
			// CommonUtil::upload ( $file );
			// $model->file_path = '/uploads/' . $file ['name'];
			// }
			// $index ++;
			// }
			// }
			
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form4' ) );
		}
		
		$this->render ( 'update', array (
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
		
		$this->render ( 'update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form4::model ()->findbyPk ( $id );
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
		$datas = Form4::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบรายงานด้านความปลอดภัยทางรังสี มหาวิทยาลัยมหิดล' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'บุคลากรที่เกี่ยวข้องกับการใช้รังสี' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ภาควิชา ' . UserLoginUtils::getUsersLogin ()->department->name . ' สาขา ' . UserLoginUtils::getUsersLogin ()->department->branch_id . '  คณะ ' . UserLoginUtils::getUsersLogin ()->department->faculty_id . '' ) . '<br /><br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
								<th>ลำดับ</th>
								<th>ชื่อ - นามสกุล</th>
								<th>ปริมาณที่ได้รับ Hp(10)</th>
								<th>ปริมาณที่ได้รับ Hp(0.07)</th>
								<th>ปริมาณที่ได้รับ Hp(3)</th>
								<th>ว.ด.ป.(ที่รายงาน)</th>
								<th>การรายงานผล</th>
								<th>ตำแหน่ง</th>
								<th>เจ้าหน้าที่ความปลอดภัยทางรังสี (RSO)</th>
								<th>เลขที่ใบอนุญาต RSO</th>
								<th>วันที่ใบอนุญาตหมดอายุ</th>
						</thead>
							<tbody>';
		// BODY
		$index = 1;
		foreach ( $datas as $item ) {
			
			$str .= '<tr>';
			$str .= '<td>' . $index . '</td>';
			$str .= '<td>' . $item->name . '</td>';
			$str .= '<td>' . $item->hp_10_volume . '</td>';
			$str .= '<td>' . $item->hp_007_volume . '</td>';
			$str .= '<td>' . $item->hp_3_volume . '</td>';
			$str .= '<td>' . $item->report_date . '</td>';
			$str .= '<td>' . (($item->report_period_id == 1) ? "รายเดือน" : "รายปี") . '</td>';
			$str .= '<td>' . $item->position->name . '</td>';
			$str .= '<td>' . (isset ( $item->is_rso_staff ) && $item->is_rso_staff == '1' ? '&#10003' : '') . '</td>';
			$str .= '<td>' . $item->rso_license_no . '</td>';
			$str .= '<td>' . $item->rso_license_expire_date . '</td>';
			
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt4_01_' . date ( "Y-m-d" ), $str );
	}
}