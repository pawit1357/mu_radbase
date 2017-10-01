<?php
class FormQuestionnaireController extends CController {
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
		$model = new MDepartment ();
		$this->render ( 'main', array (
				'data' => $model 
		) );
	}
	public function actionQuestion() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		if (isset ( $_POST ['answer_choices'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			
			$answer_choices = $_POST ['answer_choices'];
			$answer_texts = $_POST ['answer_texts'];
			$answer_files = $_FILES ['answer_files'];
			
			// Delete old role
			$cri = new CDbCriteria ();
			$cri->condition = " department_id='" . $_GET ['id'] . "'";
			Answers::model ()->deleteAll ( $cri );
			
			// CHOICE
			foreach ( $answer_choices as $key1 => $value1 ) {
				// echo "-------#" . $key1 . "," . $value1 . "<br>";
				$ans = new Answers ();
				
				$ans->question_id = $key1;
				$ans->value = $value1;
				$ans->type = CommonUtil::CHECKBOX_TYPE;
				$ans->create_by = UserLoginUtils::getUsersLoginId ();
				$ans->create_date = date ( "Y-m-d H:i:s" );
				$ans->update_by = UserLoginUtils::getUsersLoginId ();
				$ans->update_date = date ( "Y-m-d H:i:s" );
				$ans->department_id = $_GET ['id'];
				$ans->save ();
			}
			// CHOICE
			foreach ( $answer_texts as $key1 => $value1 ) {
				// echo "-------#" . $key1 . "," . $value1 . "<br>";
				if (! CommonUtil::IsNullOrEmptyString ( $value1 )) {
					$ans = new Answers ();
					
					$ans->question_id = $key1;
					$ans->value = $value1;
					$ans->type = CommonUtil::TEXT_TYPE;
					$ans->create_by = UserLoginUtils::getUsersLoginId ();
					$ans->create_date = date ( "Y-m-d H:i:s" );
					$ans->update_by = UserLoginUtils::getUsersLoginId ();
					$ans->update_date = date ( "Y-m-d H:i:s" );
					$ans->department_id = $_GET ['id'];
					$ans->save ();
				}
			}
			// FILES
			$QuesIdList = array (
					"1.1",
					"1.2",
					"2",
					"4.1.1",
					"4.1.2",
					"4.1.3",
					"6.1",
					"6.2",
// 					"6.3",
// 					"7.1.1",
// 					"7.1.2",
// 					"9",
					"9.1.1",
					"9.1.2",
					"9.1.3",
					"13.1",
					"14.1",
					"14.2" 
			);
			if (isset ( $answer_files )) {
				$file_ary = CommonUtil::reArrayFiles ( $answer_files );
				
				$index = 0;
				foreach ( $file_ary as $file ) {
					if ($file ['size'] > 0) {
						
						CommonUtil::upload ( $file );
						$ans = new Answers ();
						
						$ans->question_id = $QuesIdList [$index];
						$ans->value = '/uploads/' . $file ['name'];
						$ans->type = CommonUtil::FILE_TYPE;
						$ans->create_by = UserLoginUtils::getUsersLoginId ();
						$ans->create_date = date ( "Y-m-d H:i:s" );
						$ans->update_by = UserLoginUtils::getUsersLoginId ();
						$ans->update_date = date ( "Y-m-d H:i:s" );
						$ans->department_id = $_GET ['id'];
						$ans->save ();
						
						// echo '==>'.$index;
					}
					$index ++;
				}
			}
			
			$tmpfilePath = isset ( $_POST ['tmpPath'] )?  $_POST ['tmpPath']:NULL;
			if (isset ( $tmpfilePath )) {
				foreach ( $tmpfilePath as $key1 => $value1 ) {
// 					echo "-------#" . $key1 . "," . $value1 . "<br>";
					
					if (isset ( $answer_files )) {
						$file_ary = CommonUtil::reArrayFiles ( $answer_files );
						
						$bExist = false;
						$index = 0;
						foreach ( $file_ary as $file ) {
							if ($file ['size'] > 0) {
								if ($QuesIdList [$index] == $key1) {
									$bExist = true;
								}
							}
							$index ++;
						}
						if (! $bExist) {
							$ans = new Answers ();
							
							$ans->question_id = $key1;
							$ans->value = $value1;
							$ans->type = CommonUtil::FILE_TYPE;
							$ans->create_by = UserLoginUtils::getUsersLoginId ();
							$ans->create_date = date ( "Y-m-d H:i:s" );
							$ans->update_by = UserLoginUtils::getUsersLoginId ();
							$ans->update_date = date ( "Y-m-d H:i:s" );
							$ans->department_id = $_GET ['id'];
							$ans->save ();
						}
					}
				}
			}
			
			$transaction->commit ();
			$this->render ( '//formquestionnaire/result' );
		} else {
			$this->render ( '//formquestionnaire/question' );
		}
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = MTitle::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}