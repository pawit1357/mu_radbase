<?php
class MManufacturerController extends CController {
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
		$model = new Manufacturer ();
		$this->render ( '//mmanufacturer/main', array (
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
		
		if (isset ( $_POST ['Manufacturer'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Manufacturer ();
			$model->attributes = $_POST ['Manufacturer'];
			//Add Branch
			$branch_group_ids = '';
			foreach ($_POST['branch_group_id'] as $branch_id)
			{
				$branch_group_ids.= $branch_id.',';
			}
			$model->branch_group_id = rtrim($branch_group_ids, ",");
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'MManufacturer' ) );
		} else {
			// Render
			$this->render ( '//mmanufacturer/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'MManufacturer/' ) );
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
		if (isset ( $_POST ['Manufacturer'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['Manufacturer'];
			//Add Branch
			$branch_group_ids = '';
			foreach ($_POST['branch_group_id'] as $branch_id)
			{
				$branch_group_ids.= $branch_id.',';
			}
			$model->branch_group_id = rtrim($branch_group_ids, ",");
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'MManufacturer' ) );
		}
		$this->render ( '//mmanufacturer/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = Manufacturer::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}