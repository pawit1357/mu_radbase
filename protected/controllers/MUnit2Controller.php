<?php
class MUnit2Controller extends CController {
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
		$model = new MUnit2 ();
		$this->render ( '//munit2/main', array (
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
		
		if (isset ( $_POST ['MUnit2'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new MUnit2 ();
			$model->attributes = $_POST ['MUnit2'];
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
			$this->redirect ( Yii::app ()->createUrl ( 'MUnit2' ) );
		} else {
			// Render
			$this->render ( '//munit2/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'MUnit/' ) );
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
		if (isset ( $_POST ['MUnit2'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['MUnit2'];
			//Add Branch
			$branch_group_ids = '';
			foreach ($_POST['branch_group_id'] as $branch_id)
			{
				$branch_group_ids.= $branch_id.',';
			}
			$model->branch_group_id = rtrim($branch_group_ids, ",");
				
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'MUnit2' ) );
		}
		$this->render ( '//munit2/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = MUnit2::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}