<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController {
	public $layout = '_login';
	private $_model;
	public function actionIndex() {
		// Authen Login		
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		// Render
		$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/' ) );
	}
	
	/**
	 * Login Page
	 */
	public function actionLogin() {
		// if login redirect to index
		if (UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( '' ) );
		}
		
		if (isset ( $_POST ['UsersLogin'] ['username'] ) && isset ( $_POST ['UsersLogin'] ['password'] )) {
			
			$username = addslashes ( $_POST ['UsersLogin'] ['username'] );
			$password = addslashes ( $_POST ['UsersLogin'] ['password'] );
			
			// Authen
			if (UserLoginUtils::authen ( $username, $password )) {
				if (UserLoginUtils::isForceChangePassword ()) {
					$this->redirect ( Yii::app ()->createUrl ( 'Site/ChangePassword/' ) );
				} else {
					$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/' ) );
				}
			} else {
				$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
			}
		}
		$this->render ( '//site/login' );
	}
	
	/**
	 * Logout
	 */
	public function actionLogout() {
		UserLoginUtils::logout ();
		$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
	}
	public function actionChangePassword() {
		// Authen Login
		// if (! UserLoginUtils::isLogin ()) {
		// $this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		// }
		if (isset ( $_POST ['UsersLogin'] )) {
			$update_model = UsersLogin::model ()->findbyPk ( UserLoginUtils::getUsersLoginId () );
			
			$model = UsersLogin::model ();
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['UsersLogin'];
			$update_model->password = md5 ( $model->password );
			$update_model->update_by = UserLoginUtils::getUsersLoginId ();
			$update_model->update_date = date ( "Y-m-d H:i:s" );
			$update_model->is_force_change_password = 0;
			
			$update_model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Site/LogOut' ) );
		} else {
			
			// $model = $this->loadModel ();
			$this->render ( '//site/change_password' );
		}
	}
}