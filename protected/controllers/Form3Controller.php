<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class Form3Controller extends CController {
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
		$model = new Form3 ();
		$this->render ( '//form3/main', array (
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
			$detail = '  ( การเคลื่อนย้ายวัสดุกัมมันตรังสี ' . (isset($model->rad->bpm_radioactive_elements->name)? $model->rad->bpm_radioactive_elements->name.' ('.$model->rad->bpm_no.')' : ''). ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		//---------------------------------------------
		//END
		//---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form3/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form3 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form3/main', array (
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
		
		if (isset ( $_POST ['Form3'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Form3 ();
			$model->attributes = $_POST ['Form3'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			
			// $model->bpm_as_of_date = CommonUtil::getDate($model->bpm_as_of_date);
			$model->date_from = CommonUtil::getDate ( $model->date_from );
			$model->date_to = CommonUtil::getDate ( $model->date_to );
			$model->check_date = CommonUtil::getDate ( $model->check_date );
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $addSuccess = true;
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form3' ) );
		} else {
			// Render
			$this->render ( '//form3/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form3/' ) );
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
		if (isset ( $_POST ['Form3'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			$model = new Form3 ();
			
			$model->attributes = $_POST ['Form3'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = ($updateModel->revision + 1);
			$model->update_from_id = $updateModel->update_from_id;
			// $model->revision = CommonUtil::getLastRevision_Form3 ( $model->ref_doc, 1 );
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			
			// $model->bpm_as_of_date = CommonUtil::getDate($model->bpm_as_of_date);
			$model->date_from = CommonUtil::getDate ( $model->date_from );
			$model->date_to = CommonUtil::getDate ( $model->date_to );
			$model->check_date = CommonUtil::getDate ( $model->check_date );
			
			$model->update_from_id = $updateModel->update_from_id;
			$model->approve_status= $updateModel->approve_status;
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form3' ) );
		}
		
		$this->render ( '//form3/update', array (
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
		
		$this->render ( '//form3/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form3::model ()->findbyPk ( $id );
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
		$datas = Form3::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบการย้ายวัสดุพลอยได้' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ตามกฎกระทรวงกำหนดเงื่อนไขวิธีการขอรับใบอนุญาต และการดำเนินการเกี่ยวกับวัสดุนิวเคลียร์พิเศษ' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'วัสดุต้นกำลัง วัสดุพลอยได้ หรือพลังงานปรมาณู พ.ศ. ๒๕๕๐ ' ) . '<br /><br />' . self::CRLF . 

		Yii::t ( 'main', '๑.		ชื่อสถานประกอบกิจการ  xxxxxxxxxxxxxxxxxxxxxxxxx มหาวิทยาลัยมหิดล' ) . '<br /><br />' . self::CRLF . Yii::t ( 'main', 'ที่ตั้งเลขที่ xxxxxxxxx หมู่ xxxxxxxx ตรอก/ซอย xxxxxxxx ถนน xxxxxxxxx แขวง/ตำบล xxxxxxxxx' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'เขต/อำเภอ xxxxxxxxx จังหวัด xxxxxxxxx รหัสไปรษณีย์ xxxxxxxxxx โทรศัพท์ xxxxxxxxxx โทรสาร xxxxxxxxxx E-mail xxxxxxxxxxx ' ) . '<br />' . self::CRLF . Yii::t ( 'main', '๒.    รหัสหน่วยงานเลขที่ xxxxxxxxxxx แบบรายงาน สร๓ ตามใบอนุญาต เลขที่ xxxxxxxxxxxxxxxx ' ) . '<br /><br />' . self::CRLF . Yii::t ( 'main', '๓.		ขอแจ้งการย้ายวัสดุพลอยได้ ดังนี้' ) . '<br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th rowspan="3">ลำดับ</th>
								<th colspan="8">รายละเอียดวัสดุพลอยได้</th>
								<th colspan="4">ภาชนะบรรจุ/เครื่องมือ/เครื่องจักร</th>
								<th colspan="2">สถานที่เก็บรักษา/สถานที่ใช้งาน</th>
								<th rowspan="3">ตั้งแต่วันที่ถึงวันที่</th>
								<th rowspan="3">ผู้ควบคุม</th>
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

								<th rowspan="2">เดิม</th>
								<th rowspan="2">ไปที่</th>
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
			
			$str .= '<td>' . $item->from_room->name . '</td>';
			$str .= '<td>' . $item->to_room->name . '</td>';
			$str .= '<td>' . $item->date_from . '-' . $item->date_to . '</td>';
			$str .= '<td>' . $item->supervisor->first_name . '  ' . $item->supervisor->last_name . '</td>';
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt3_01_' . date ( "Y-m-d" ), $str );
	}
}