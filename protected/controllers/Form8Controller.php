<?php
class Form8Controller extends CController {
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
		$model = new Form8 ();
		$this->render ( '//form8/main', array (
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
			$detail = '  ( รายชื่อคณะกรรมการความปลอดภัยทางรังสี (ประจำส่วนงาน) ' . $model->name . ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		//---------------------------------------------
		//END
		//---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Form8/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form8 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form8/main', array (
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
		
		if (isset ( $_POST ['Form8'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Form8 ();
			$model->attributes = $_POST ['Form8'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
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
			$this->redirect ( Yii::app ()->createUrl ( 'Form8' ) );
		} else {
			// Render
			$this->render ( '//form8/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form8/' ) );
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
		if (isset ( $_POST ['Form8'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			
			$model = new Form8 ();
			$model->attributes = $_POST ['Form8'];
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->update_from_id=$updateModel->update_from_id;
			$model->revision = ($updateModel->revision + 1);
			$model->status = 'T';
			$model->update_from_id = $updateModel->update_from_id;
			$model->approve_status= $updateModel->approve_status;
			
			$model->file_path = $updateModel->file_path;
			$file_path = $_FILES ['file_path'];
			if (isset ( $file_path )) {
				$file_ary = CommonUtil::reArrayFiles ( $file_path );
			
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->file_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form8' ) );
		}
		
		$this->render ( '//form8/update', array (
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
		
		$this->render ( '//form8/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form8::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}