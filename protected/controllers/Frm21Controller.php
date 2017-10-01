<?php

/**
 * SiteController is the default controller to handle user requests.
*/
class Frm21Controller extends CController {
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
		$model = new Form2 ();
		$this->render ( '//frm21/main', array (
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
			$detail = '  (วัสดุกัมมันตรังสี ชนิดปิดผนึก  ธาตุ-เลขมวล = ' . $model->bpm_radioactive_elements->name . ')  ';
			$receiptNmae = 'คุณ' . $Approver->first_name . '  ' . $Approver->last_name;
			MailUtil::sendMail ( $Approver->email, $strSubject, MailUtil::GetEmailContent ( $receiptNmae, $detail ) );
		}
		//---------------------------------------------
		//END
		//---------------------------------------------
		$this->redirect ( Yii::app ()->createUrl ( 'Frm21/' ) );
	}
	public function actionRevision() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}

		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}

		$model = new Form2 ();
		if (isset ( $_GET ['id'] )) {
			$model->rev_id = $_GET ['id'];
		}
		$this->render ( '//frm21/main', array (
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
		if (isset ( $_POST ['Form2'] )) {
				
			$transaction = Yii::app ()->db->beginTransaction ();
				
			$bpm_no = $_POST ['bpm_no'];
			$bpm_volume = $_POST ['bpm_volume'];
			$bpm_as_of_date = $_POST ['bpm_as_of_date'];
			$bpm_volume_unit_id = $_POST ['bpm_volume_unit_id'];
			$bpm_number = $_POST ['bpm_number'];
				
			if (isset ( $bpm_no )) {
				$index = 0;
				foreach ( $bpm_no as $key => $item ) {
					// echo '::'.$item.'-'.$bpm_volume[$index].'<br>';
					if (! CommonUtil::IsNullOrEmptyString ( $item )) {
						$model = new Form2 ();
						$model->attributes = $_POST ['Form2'];
						$model->type = 2;
						$model->create_by = UserLoginUtils::getUsersLoginId ();
						$model->create_date = date ( "Y-m-d H:i:s" );
						$model->update_by = UserLoginUtils::getUsersLoginId ();
						$model->update_date = date ( "Y-m-d H:i:s" );
						$model->revision = 1;
						$model->owner_department_id = UserLoginUtils::getDepartmentId ();
						$model->status = 'T';
						
// 						echo $model->license_expire_date .'==>'.CommonUtil::getDate ( $model->license_expire_date );
						if(!CommonUtil::IsNullOrEmptyString($model->license_expire_date)){
							$model->license_expire_date = CommonUtil::getDate ( $model->license_expire_date );
						}

						$model->bpm_no = $item;
						$model->bpm_volume = $bpm_volume [$index];
						$model->bpm_as_of_date = CommonUtil::getDate ( $bpm_as_of_date [$index] );
						$model->bpm_volume_unit_id = $bpm_volume_unit_id [$index];
						$model->bpm_number = $bpm_number [$index];

						$model->save ();
					}
					$index ++;
				}
			}
				
			// Add Request
				
			// Add when other
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->code_usage_other )) {
// 				$rad = new MCodeUsage ();
// 				$rad->id = MCodeUsage::getMax ();
// 				$rad->name = $model->code_usage_other;
// 				$rad->is_rad_machine = 2;
// 				$rad->is_sealsource = 1;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();

// 				$model->code_usage_id = $rad->id;
// 			}
				
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->bpm_radioactive_elements_other )) {
// 				$rad = new MRadioactiveElement ();
// 				$rad->id = MRadioactiveElement::getMax ();
// 				$rad->name = $model->bpm_radioactive_elements_other;
// 				$rad->type = 2;
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();

// 				$model->bpm_radioactive_elements_id = $rad->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->manufacturer_other )) {
// 				$rad = new Manufacturer ();
// 				$rad->id = Manufacturer::getMax ();
// 				$rad->name = $model->manufacturer_other;
// 				$rad->country_id = '-';
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();

// 				$model->manufacturer_id = $rad->id;
// 			}
// 			if (! CommonUtil::IsNullOrEmptyString ( $model->dealer_other )) {
// 				$rad = new MDealerCompany ();
// 				$rad->id = MDealerCompany::getMax ();
// 				$rad->name = $model->dealer_other;
// 				$rad->addr = '-';
// 				$rad->branch_group_id = UserLoginUtils::getUserInfo ()->branch_group_id;
// 				$rad->save ();

// 				$model->dealer_id = $rad->id;
// 			}
				
			$transaction->commit ();
				
			$this->redirect ( Yii::app ()->createUrl ( 'Frm21' ) );
		} else {
			// Render
			$this->render ( '//frm21/create' );
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

		$this->redirect ( Yii::app ()->createUrl ( 'Frm21/' ) );
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
		if (isset ( $_POST ['Form2'] )) {
				
			$transaction = Yii::app ()->db->beginTransaction ();
			$updateModel->status = 'F';
			$updateModel->update_from_id = ($updateModel->update_from_id == 0) ? $updateModel->id : $updateModel->update_from_id;
			$updateModel->update ();
				
			$bpm_no = $_POST ['bpm_no'];
			$bpm_volume = $_POST ['bpm_volume'];
			$bpm_as_of_date = $_POST ['bpm_as_of_date'];
			$bpm_volume_unit_id = $_POST ['bpm_volume_unit_id'];
			$bpm_number = $_POST ['bpm_number'];

			if (isset ( $bpm_no )) {
				$index = 0;
				foreach ( $bpm_no as $key => $item ) {
					// echo '::'.$item.'-'.$bpm_volume[$index].'<br>';
					if (! CommonUtil::IsNullOrEmptyString ( $item )) {
						$model = new Form2 ();
						$model->attributes = $_POST ['Form2'];
						$model->type = 2;
						$model->create_by = UserLoginUtils::getUsersLoginId ();
						$model->create_date = date ( "Y-m-d H:i:s" );
						$model->update_by = UserLoginUtils::getUsersLoginId ();
						$model->update_date = date ( "Y-m-d H:i:s" );
						$model->revision = ($updateModel->revision + 1);
						$model->update_from_id = $updateModel->update_from_id;
						$model->owner_department_id = UserLoginUtils::getDepartmentId ();
						$model->status = 'T';
						
						if(!CommonUtil::IsNullOrEmptyString($model->license_expire_date)){
							if($model->license_expire_date  != '0000-00-00'){
								$model->license_expire_date = CommonUtil::getDate ( $model->license_expire_date );
							}
						}
						$model->approve_status= $updateModel->approve_status;
						//
							
						$model->bpm_no = $item;
						$model->bpm_volume = $bpm_volume [$index];
						$model->bpm_as_of_date = CommonUtil::getDate ( $bpm_as_of_date [$index] );
						$model->bpm_volume_unit_id = $bpm_volume_unit_id [$index];
						$model->bpm_number = $bpm_number [$index];
							
						$model->save ();
					}
					$index ++;
				}
			}
				
	
			$model->save ();

			
			
			$transaction->commit ();
				
			$this->redirect ( Yii::app ()->createUrl ( 'Frm21' ) );
		}

		$this->render ( '//frm21/update', array (
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

		$this->render ( '//frm21/update', array (
				'data' => $model
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Form2::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
	
}