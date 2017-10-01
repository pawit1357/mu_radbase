<?php
class MRoomController extends CController {
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
		$model = new MRoom ();
		$this->render ( '//mroom/main', array (
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
		
		if (isset ( $_POST ['MRoom'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new MRoom ();
			$model->attributes = $_POST ['MRoom'];
			
			if ($_FILES ['room_plan'] ['name']) {
				// if no errors...
				if (! $_FILES ['room_plan'] ['error']) {
					$currentdir = getcwd ();
					
					$target = $currentdir . "/uploads/";
					$target = $target . basename ( $_FILES ['room_plan'] ['name'] );
					$temploc = $_FILES ['uploadedfile'] ['tmp_name'];
					
					// This is our size condition
					if ($uploaded_size > 350000) {
						echo "Your file is too large.<br>";
					}
					// This is our limit file type condition
					if ($uploaded_type == "text/php") {
						echo "No PHP files<br>";
					} 					

					// Here we check that $ok was not set to 0 by an error
					// if ($ok == 0) {
					// Echo "Sorry your file was not uploaded";
					// }
					
					// If everything is ok we try to upload it
					else {
						if (move_uploaded_file ( $_FILES ['room_plan'] ['tmp_name'], $target )) {
							$model->room_plan = '/uploads/' . $_FILES ['room_plan'] ['name'];
							// echo "The file " . basename ( $_FILES ['room_plan'] ['name'] ) . " has been uploaded";
						} else {
							echo "Sorry, there was a problem uploading your file.";
						}
					}
				}
			}
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'MRoom' ) );
		} else {
			// Render
			$this->render ( '//mroom/create' );
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
		
		$this->redirect ( Yii::app ()->createUrl ( 'MRoom/' ) );
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
		if (isset ( $_POST ['MRoom'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['MRoom'];
			
			if ($_FILES ['room_plan'] ['name']) {
				// if no errors...
				if (! $_FILES ['room_plan'] ['error']) {
					$currentdir = getcwd ();
					
					$target = $currentdir . "/uploads/";
					$target = $target . basename ( $_FILES ['room_plan'] ['name'] );
					$temploc = $_FILES ['uploadedfile'] ['tmp_name'];
					
					// This is our size condition
					if ($uploaded_size > 350000) {
						echo "Your file is too large.<br>";
					}
					// This is our limit file type condition
					if ($uploaded_type == "text/php") {
						echo "No PHP files<br>";
					} 					

					// Here we check that $ok was not set to 0 by an error
					// if ($ok == 0) {
					// Echo "Sorry your file was not uploaded";
					// }
					
					// If everything is ok we try to upload it
					else {
						if (move_uploaded_file ( $_FILES ['room_plan'] ['tmp_name'], $target )) // THIS IS LINE 43
{
							$model->room_plan = '/uploads/' . $_FILES ['room_plan'] ['name'];
							// echo "The file " . basename ( $_FILES ['room_plan'] ['name'] ) . " has been uploaded";
						} else {
							echo "Sorry, there was a problem uploading your file.";
						}
					}
				}
			}
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'MRoom' ) );
		}
		$this->render ( '//mroom/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = MRoom::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}