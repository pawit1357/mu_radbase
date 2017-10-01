<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class Form7Controller extends CController {
	const CRLF = "\r\n";
	public $layout = '_main';
	private $_model;
	public function actionIndex() {
		// Authen Login
		// if (! UserLoginUtils::isLogin ()) {
		// $this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		// }
		// if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
		// $this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		// }
		$model = new Form7 ();
		$this->render ( '//form7/main', array (
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
			$detail = '  ( การพัฒนาบุคลากรด้านการป้องกันอันตรายทางรังสีประจำปี ชื่อ-สกุล ' . $model->name.' ได้อบรมหลักสูตร '.($model->course_id=='1'? 'การอบรมความปลอดภัยเบื้องต้น':'การอบรมการป้องกันอันตรายจากรังสี ระดับป้อง 1 2') . ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		// ---------------------------------------------
		// END
		// ---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form7/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form7 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form7/main', array (
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
		
		if (isset ( $_POST ['Form7'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			
			$dep_id = UserLoginUtils::getDepartmentId ();
			
			// Add Request
			$model = new Form7 ();
			$model->attributes = $_POST ['Form7'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->department_id = $dep_id;
			$model->owner_department_id = $dep_id;
			$model->status = 'T';
			$model->training_date = CommonUtil::getDate ( $model->training_date );
			
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
						
						$model->document_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$file_path_plan = $_FILES ['file_path_plan'];
			if (isset ( $file_path_plan )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path_plan );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->rso_plan_file = '/uploads/' .CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$model->save ();
			
			// echo "SAVE";
			$transaction->commit ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form7' ) );
		} else {
			// Render
			$this->render ( '//form7/create' );
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
		
		$transaction = Yii::app ()->db->beginTransaction ();
		
		$dep_id = UserLoginUtils::getDepartmentId ();
		
		if (isset ( $_FILES ['fileupload1'] ['name'] )) {
			if ($_FILES ['fileupload1'] ['name']) {
				// if no errors...
				if (! $_FILES ['fileupload1'] ['error']) {
					$currentdir = getcwd ();
					
					$target = $currentdir . "/uploads/";
					$target = $target . basename ( $_FILES ['fileupload1'] ['name'] );
					
					if (move_uploaded_file ( $_FILES ['fileupload1'] ['tmp_name'], $target )) {
						// try {
						
						$inputFileType = PHPExcel_IOFactory::identify ( $target );
						$objReader = PHPExcel_IOFactory::createReader ( $inputFileType );
						$objPHPExcel = $objReader->load ( $target );
						$sheet = $objPHPExcel->getSheet ( 0 );
						$highestRow = $sheet->getHighestRow ();
						$highestColumn = $sheet->getHighestColumn ();
						
						for($row = 1; $row <= $highestRow; $row ++) {
							if ($row > 1) {
								$model = new Form7 ();
								$model->create_by = UserLoginUtils::getUsersLoginId ();
								$model->create_date = date ( "Y-m-d H:i:s" );
								$model->update_by = UserLoginUtils::getUsersLoginId ();
								$model->update_date = date ( "Y-m-d H:i:s" );
								$model->revision = 1;
								$model->department_id = $dep_id;
								$model->owner_department_id = $dep_id;
								$model->status = 'T';
								
								$model->name = $sheet->getCell ( "B" . $row )->getValue ();
								$model->course = $sheet->getCell ( "C" . $row )->getValue ();
								$model->training_department = $sheet->getCell ( "D" . $row )->getValue ();
								$eValue = $sheet->getCell ( "E" . $row )->getValue();
// 								echo $eValue.'<br>';
								
								if(isset($eValue)){
									list ( $day, $month, $year ) = explode ( "/", $eValue);
								
									$model->training_date = ($year-543).'-'.$month.'-'.$day;
								}
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
				
				// echo "SAVE";
				$transaction->commit ();
				$this->redirect ( Yii::app ()->createUrl ( 'Form7' ) );
			} else {
				// Render
				$this->render ( '//form7/importfile' );
			}
		} else {
			$this->render ( '//form7/importfile' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form7/' ) );
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
		if (isset ( $_POST ['Form7'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			
			$model = new Form7 ();
			$model->attributes = $_POST ['Form7'];
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			
			$model->revision = ($updateModel->revision + 1);
			$model->update_from_id = $updateModel->update_from_id;
			$model->status = 'T';
			$model->training_date = CommonUtil::getDate ( $model->training_date );
			$model->update_from_id = $updateModel->update_from_id;
			$model->document_path = $updateModel->document_path;
			$model->approve_status= $updateModel->approve_status;
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
						$model->document_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			$file_path_plan = $_FILES ['file_path_plan'];
			if (isset ( $file_path_plan )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path_plan );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
						$model->rso_plan_file = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form7' ) );
		}
		
		$this->render ( '//form7/update', array (
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
		
		$this->render ( '//form7/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form7::model ()->findbyPk ( $id );
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
		$datas = Form7::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบรายงานด้านความปลอดภัยทางรังสี มหาวิทยาลัยมหิดล' ) . '<br />' . self::CRLF . Yii::t ( 'main', '' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ภาควิชา ' . UserLoginUtils::getUsersLogin ()->department->name . ' สาขา ' . UserLoginUtils::getUsersLogin ()->department->branch_id . '  คณะ ' . UserLoginUtils::getUsersLogin ()->department->faculty_id . '' ) . '<br /><br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
								<th>ลำดับ</th>
								<th>ชื่อ – นามสกุล (ภาษาไทย)</th>
								<th>หลักสูตรการอบรม</th>
								<th>หน่วยงานที่จัดอบรม</th>
								<th>วัน/เดือน/ปี ที่เข้ารับการอบรม</th>
						</thead>
							<tbody>';
		// BODY
		$index = 1;
		foreach ( $datas as $item ) {
			
			$str .= '<tr>';
			$str .= '<td>' . $index . '</td>';
			$str .= '<td>' . $item->name . '</td>';
			$str .= '<td>' . $item->course . '</td>';
			$str .= '<td>' . $item->department->name . '</td>';
			$str .= '<td>' . $item->training_date . '</td>';
			
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt7_01_' . date ( "Y-m-d" ), $str );
	}
}