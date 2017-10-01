<?php
class MCodeUsageController extends CController {
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
		$model = new MCodeUsage ();
		$this->render ( '//mcodeusage/main', array (
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
		
		if (isset ( $_POST ['MCodeUsage'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new MCodeUsage ();
			$model->attributes = $_POST ['MCodeUsage'];
			// Add Branch
			$branch_group_ids = '';
			if (isset ( $_POST ['branch_group_id'] )) {
				foreach ( $_POST ['branch_group_id'] as $branch_id ) {
					$branch_group_ids .= $branch_id . ',';
				}
			}else{
				$branch_group_ids="";
			}
			$model->branch_group_id = rtrim ( $branch_group_ids, "," );
				
			// Add is_rad_machine
			if (isset ( $_POST ['is_rad_machine'] )) {
				$is_rad_machines = '';
			
				foreach ( $_POST ['is_rad_machine'] as $item ) {
					$is_rad_machines .= $item . ',';
				}
			}else{
				$is_rad_machines = '';
			}
			$model->is_rad_machine = rtrim ( $is_rad_machines, "," );
			// Add is_sealsource
			if (isset ( $_POST ['is_sealsource'] )) {
				$is_sealsources = '';
				foreach ( $_POST ['is_sealsource'] as $item ) {
					$is_sealsources .= $item . ',';
				}
				$model->is_sealsource = rtrim ( $is_sealsources, "," );
			}else{
				$is_sealsources = '';
			}
				
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'MCodeUsage' ) );
		} else {
			// Render
			$this->render ( '//mcodeusage/create' );
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
		$model->delete ();
		
		$this->redirect ( Yii::app ()->createUrl ( 'MCodeUsage/' ) );
	}
	public function actionUpdate() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = $this->loadModel ();
		if (isset ( $_POST ['MCodeUsage'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['MCodeUsage'];
			// Add Branch
			$branch_group_ids = '';
			if (isset ( $_POST ['branch_group_id'] )) {
				foreach ( $_POST ['branch_group_id'] as $branch_id ) {
					$branch_group_ids .= $branch_id . ',';
				}
			}else{
				$branch_group_ids="";
			}
			$model->branch_group_id = rtrim ( $branch_group_ids, "," );
			
			// Add is_rad_machine
			if (isset ( $_POST ['is_rad_machine'] )) {
				$is_rad_machines = '';
				
				foreach ( $_POST ['is_rad_machine'] as $item ) {
					$is_rad_machines .= $item . ',';
				}
			}else{
				$is_rad_machines = '';
			}
			$model->is_rad_machine = rtrim ( $is_rad_machines, "," );
			// Add is_sealsource
			if (isset ( $_POST ['is_sealsource'] )) {
				$is_sealsources = '';
				foreach ( $_POST ['is_sealsource'] as $item ) {
					$is_sealsources .= $item . ',';
				}
				$model->is_sealsource = rtrim ( $is_sealsources, "," );
			}else{
				$is_sealsources = '';
			}
			
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'MCodeUsage' ) );
		}
		$this->render ( '//mcodeusage/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = MCodeUsage::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}