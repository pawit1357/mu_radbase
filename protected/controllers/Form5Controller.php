<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class Form5Controller extends CController {
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
		$model = new Form5 ();
		$this->render ( '//form5/main', array (
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
	
		$approve_status = addslashes($_GET['approve_status']);
		
		$model = $this->loadModel();
		// check SI (approver)
		$isISTask = false;
		if ($approve_status == UserLoginUtils::EXECUTIVE_APPROVE) {
		    
		    $approverIndex = isset($model->approve_index) ? $model->approve_index : 1;
		    
		    $approver = new MApprover();
		    $approver->approver_index = $approverIndex;
		    $approver = $approver->searchByIndex($approverIndex);
		    
		    if (isset($approver)) {
		        $isISTask = true;
		        if ($approverIndex <= 2) { // SI approver has 3 person
		            $model->approve_index = $approverIndex + 1;
		        } else {
		            $model->approve_status = $approve_status;
		        }
		    } else {
		        $model->approve_status = $approve_status;
		    }
		}else{
		    $model->approve_status = $approve_status;
		}
		$model->update();
		
		// ---------------------------------------------
		// MAIL APPROVED
		// ---------------------------------------------
		$strSubject = "=?UTF-8?B?" . base64_encode(ConfigUtil::getApplicationTitle()) . "?=";
		$approve = '';
		$isISTaskStaffApproved = false;
		switch ($approve_status) {
		    case UserLoginUtils::INIT_APPROVE:
		        $approve = UserLoginUtils::STAFF;
		        break;
		    case UserLoginUtils::STAFF_APPROVE:
		        $approve = UserLoginUtils::EXECUTIVE;
		        $isISTaskStaffApproved =true;
		        break;
		}
		$Approver = UserLoginUtils::getApprover($approve);
		
		if ($isISTaskStaffApproved) {
		    $approverSI = new MApprover();
		    $approverSI->approver_index = 1;
		    $approverSI = $approverSI->searchByIndex();
		    if(isset($approverSI)) {
		        $Approver = UsersLogin::model()->findbyPk($approverSI->user_id);
		    }
		}
		if ($isISTask) {
		    $approverSI = new MApprover();
		    $approverSI->approver_index = $model->approve_index;
		    $approverSI = $approverSI->searchByIndex();
		    if(isset($approverSI)) {
		        $Approver = UsersLogin::model()->findbyPk($approverSI->user_id);
		    }
		}
		if (isset ( $Approver )) {
			$detail = '  ( การกำจัดขยะรังสี ' . (isset($model->rad->bpm_radioactive_elements->name)? $model->rad->bpm_radioactive_elements->name : ''). ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		//---------------------------------------------
		//END
		//---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form5/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form5 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form5/main', array (
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
		
		if (isset ( $_POST ['Form5'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Form5 ();
			$model->attributes = $_POST ['Form5'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			
			$model->clear_date = CommonUtil::getDate ( $model->clear_date );
			// Add Other
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->inspection_agency_other )) {
// 				$rad = new MInspectionAgency ();
// 				$rad->id = MInspectionAgency::getMax ();
// 				$rad->name = $model->inspection_agency_other;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();
				
// 				$model->inspection_agency_id = $rad->id;
// 			}
			
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->company_operates_other )) {
// 				$rad = new MCompanyOperates ();
// 				$rad->id = MCompanyOperates::getMax ();
// 				$rad->name = $model->company_operates_other;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();
				
// 				$model->company_operates_id = $rad->id;
// 			}
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $addSuccess = true;
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form5' ) );
		} else {
			// Render
			$this->render ( '//form5/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form5/' ) );
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
		if (isset ( $_POST ['Form5'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			
			$model = new Form5 ();
			$model->attributes = $_POST ['Form5'];
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			
			$model->revision = ($updateModel->revision + 1);
			$model->status = 'T';
			$model->update_from_id = $updateModel->update_from_id;
			$model->clear_date = CommonUtil::getDate ( $model->clear_date );
			
			$model->update_from_id = $updateModel->update_from_id;
			$model->approve_status= $updateModel->approve_status;
			// Add Other
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->inspection_agency_other )) {
// 				$rad = new MInspectionAgency ();
// 				$rad->id = MInspectionAgency::getMax ();
// 				$rad->name = $model->inspection_agency_other;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();
			
// 				$model->inspection_agency_id = $rad->id;
// 			}
				
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->company_operates_other )) {
// 				$rad = new MCompanyOperates ();
// 				$rad->id = MCompanyOperates::getMax ();
// 				$rad->name = $model->company_operates_other;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();
			
// 				$model->company_operates_id = $rad->id;
// 			}
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form5' ) );
		}
		
		$this->render ( '//form5/update', array (
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
		
		$this->render ( '//form5/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form5::model ()->findbyPk ( $id );
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
		$datas = Form5::model ()->findAll ( $criteria );
		
		// BEGIN HTML
		$str = '<html>' . self::CRLF . '<head>' . self::CRLF . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF . '</head>' . self::CRLF . '<body style="text-align:center">' . self::CRLF;
		
		// TITLE
		$str .= "<b>$title</b><br /><br />" . self::CRLF . Yii::t ( 'main', 'แบบรายงานด้านความปลอดภัยทางรังสี มหาวิทยาลัยมหิดล' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'การกำจัดขยะรังสี' ) . '<br />' . self::CRLF . Yii::t ( 'main', 'ภาควิชา ' . UserLoginUtils::getUsersLogin ()->department->name . ' สาขา ' . UserLoginUtils::getUsersLogin ()->department->branch_id . '  คณะ ' . UserLoginUtils::getUsersLogin ()->department->faculty_id . '' ) . '<br /><br />' . self::CRLF;
		
		// TABLE
		$str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">
						<thead>
								<th>ลำดับ</th>
								<th>วัน/เดือน/ปี ที่ส่งกำจัด</th>
								<th>ประเภทวัสดุกัมมันตรังสี</th>
								<th>ชื่อวัสดุกัมมันตรังสี</th>
								<th>สมบัติทางกายภาพ</th>
								<th>กัมมันตภาพสูงสุดหรือน้ำหนัก (Bq, Ci, Kg, Lb)</th>
								<th>หน่วยงาน/บริษัท ที่ส่งกำจัด</th>
						</thead>
							<tbody>';
		// BODY
		$index = 1;
		foreach ( $datas as $item ) {
			
			$str .= '<tr>';
			$str .= '<td>' . $index . '</td>';
			$str .= '<td>' . $item->clear_date . '</td>';
			$str .= '<td>' . $item->material_type->name . '</td>';
			$str .= '<td>' . $item->name . '</td>';
			$str .= '<td>' . $item->phisical_status->name . '</td>';
			$str .= '<td>' . $item->rad_or_maximum_weight . '</td>';
			$str .= '<td>' . $item->department->name . '</td>';
			
			$str .= '</tr>';
			$index ++;
		}
		// END TABLE
		$str .= '</tbody></table>';
		
		$str .= '</body>' . self::CRLF . '</html>';
		// echo $str;
		// END HTML
		ExcelExporter::sendAsXLSByHtml ( 'muradbase_rpt5_01_' . date ( "Y-m-d" ), $str );
	}
}