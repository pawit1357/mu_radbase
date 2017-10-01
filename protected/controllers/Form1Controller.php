<?php
class Form1Controller extends CController {
	const CRLF = ""; // \r\n";
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
		
		$model = new Form1 ();
		$this->render ( '//form1/main', array (
				'data' => $model 
		) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = new Form1 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//form1/main', array (
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
		
		if (isset ( $_POST ['Form1'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = 1;
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			$model->update_from_id = 0;
			
			if(!CommonUtil::IsNullOrEmptyString($model->license_expire_date)){
				$model->license_expire_date = CommonUtil::getDate ( $model->license_expire_date );
			}
			
			$inspection_agency_id = '';
			$quality_check_date = '';
			if (isset ( $_POST ['inspection_agency_id'] )) {
				foreach ( $_POST ['inspection_agency_id'] as $item ) {
					$inspection_agency_id .= $item . ',';
					
					if (isset ( $_POST ['quality_check_date'] )) {
						foreach ( $_POST ['quality_check_date'] as $key => $value ) {
							if ($key == $item) {
								$quality_check_date .= CommonUtil::getDate ( $value ) . ',';
							}
						}
					}
				}
				$model->inspection_agency_id = rtrim ( $inspection_agency_id, "," );
				$model->quality_check_date = rtrim ( $quality_check_date, "," );
			}
			
			// Add when other
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->rad_machine_other )) {
// 				$rad = new MRadMachine ();
// 				$rad->id = MRadMachine::getMax ();
// 				$rad->name = $model->rad_machine_other;
// 				$rad->seq = 0;
// 				$rad->branch_group_id = UserLoginUtils::getUsersLogin ()->branch_group_id;
// 				$rad->save ();
				
// 				$model->rad_machine_id = $rad->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->maufacturer_other )) {
// 				$man = new Manufacturer ();
// 				$man->id = Manufacturer::getMax ();
// 				$man->name = $model->maufacturer_other;
// 				$man->branch_group_id = UserLoginUtils::getUsersLogin ()->branch_group_id;
// 				$man->save ();
				
// 				$model->maufacturer_id = $man->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->utilization_other )) {
// 				$util = new MUtilization ();
// 				$util->id = MUtilization::getMax ();
// 				$util->name = $model->utilization_other;
// 				$util->branch_group_id = UserLoginUtils::getUsersLogin ()->branch_group_id;
// 				$util->save ();
				
// 				$model->utilization_id = $util->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->inspection_agency_other )) {
// 				$insp = new MInspectionAgency ();
// 				$insp->id = MInspectionAgency::getMax ();
// 				$insp->name = $model->inspection_agency_other;
// 				$insp->branch_group_id = UserLoginUtils::getUsersLogin ()->branch_group_id;
// 				$insp->save ();
				
// 				$model->inspection_agency_id = $insp->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->dealer_other )) {
// 				$dealer = new MDealerCompany ();
// 				$dealer->id = MDealerCompany::getMax ();
// 				$dealer->name = $model->dealer_other;
// 				$dealer->branch_group_id = UserLoginUtils::getUsersLogin ()->branch_group_id;
// 				$dealer->save ();
				
// 				$model->dealer_id = $dealer->id;
// 			}
			
			$machine_files = $_FILES ['machine_file'];
			
			if (isset ( $machine_files )) {
				$file_ary = CommonUtil::reArrayFiles ( $machine_files );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->file_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$room_plans = $_FILES ['room_plan'];
			
			if (isset ( $room_plans )) {
				$file_ary = CommonUtil::reArrayFiles ( $room_plans );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->room_plan = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$model->save ();
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Form1' ) );

		} else {
			// Render
			$this->render ( '//form1/create' );
		}
	}
	
	public function actionDelete() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = $this->loadModel ();
		$model->status = 'D'; // Delete
		$model->update ();
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form1/' ) );
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
			$detail = '  ( เครื่องกำเนิดรังสี ชื่อเครื่อง = ' . $model->rad_machine->name . ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		// ---------------------------------------------
		// END
		// ---------------------------------------------
		
		$this->redirect ( Yii::app ()->createUrl ( 'Form1/' ) );
	}
	public function actionUpdate() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$updateModel = $this->loadModel ();
		
		if (isset ( $_POST ['Form1'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
			
			$model = new Form1 ();
			$model->attributes = $_POST ['Form1'];
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->revision = ($updateModel->revision + 1);
			$model->owner_department_id = UserLoginUtils::getDepartmentId ();
			$model->status = 'T';
			$model->update_from_id = $updateModel->update_from_id;
			$model->file_path = $updateModel->file_path;
			$model->room_plan = $updateModel->room_plan;
			$model->approve_status= $updateModel->approve_status;
			
			if(!CommonUtil::IsNullOrEmptyString($model->license_expire_date)){
				$model->license_expire_date = CommonUtil::getDate ( $model->license_expire_date );
			}
			
			
			$inspection_agency_id = '';
			$quality_check_date = '';
			if (isset ( $_POST ['inspection_agency_id'] )) {
				foreach ( $_POST ['inspection_agency_id'] as $item ) {
					$inspection_agency_id .= $item . ',';
					
					if (isset ( $_POST ['quality_check_date'] )) {
						foreach ( $_POST ['quality_check_date'] as $key => $value ) {
							if ($key == $item) {
								$quality_check_date .= CommonUtil::getDate ( $value ) . ',';
								// echo '--->' . $key . ':' . $value.'<br>';
							}
						}
					}
				}
				$model->inspection_agency_id = rtrim ( $inspection_agency_id, "," );
				$model->quality_check_date = rtrim ( $quality_check_date, "," );
			}
			$machine_files = $_FILES ['machine_file'];
			if (isset ( $machine_files )) {
				$file_ary = CommonUtil::reArrayFiles ( $machine_files );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->file_path = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			$room_plans = $_FILES ['room_plan'];
			
			if (isset ( $room_plans )) {
				$file_ary = CommonUtil::reArrayFiles ( $room_plans );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
// 						CommonUtil::upload ( $file );
						$model->room_plan = '/uploads/' . CommonUtil::upload ( $file );
					}
					$index ++;
				}
			}
			
			// $model = new Form1 ();
			
			// $model->attributes = $_POST ['Form1'];
			// $model->create_by = UserLoginUtils::getUsersLoginId ();
			// $model->create_date = date ( "Y-m-d H:i:s" );
			// $model->update_by = UserLoginUtils::getUsersLoginId ();
			// $model->update_date = date ( "Y-m-d H:i:s" );
			// $model->revision = ($updateModel->revision + 1);
			
			// // $model->revision = CommonUtil::getLastRevision ( $model->refer_doc );
			// $model->owner_department_id = UserLoginUtils::getDepartmentId ();
			// $model->status = 'T';
			// $model->update_from_id = $updateModel->update_from_id;
			
			// $model->license_expire_date = CommonUtil::getDate ( $model->license_expire_date );
			// $model->delivery_date = CommonUtil::getDate ( $model->delivery_date );
			// $model->quality_check_date = CommonUtil::getDate ( $model->quality_check_date );
			
			$model->save ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Form1' ) );
		} else {
			
			$this->render ( '//form1/update', array (
					'data' => $updateModel 
			) );
		}
	}
	public function actionView() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		$model = $this->loadModel ();
		
		$this->render ( '//form1/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form1::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}