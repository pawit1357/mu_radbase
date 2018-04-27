<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class DashBoardController extends CController {
	public $layout = '_main';
	private $_model;
	
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex() {
		
		
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		

// 		if (isset ( $_POST ['answers_choice'] )) {
			
// 			$transaction = Yii::app ()->db->beginTransaction ();
			
// 			$answers_choice = $_POST ['answers_choice'];
// 			$answers_other = $_POST ['answers_other'];
// 			$answers_file = $_FILES ['answers_file'];
			
// 			// get attatch file array
// 			$cri1 = new CDbCriteria ();
// 			$cri1->condition = " has_attach_file =1";
// 			$attach_file_indexs = FormQuestionnaire::model ()->findAll ( $cri1 );
// 			// echo '-----.......0000+'.$attach_file_indexs[0]->id;
// 			// CHOICE
// 			foreach ( $answers_choice as $key1 => $value1 ) {
// 				// echo "-------#" . $key1 . "," . $value1 . "<br>";
// 				$qa = new FormQuestionnaireAnswer ();
// 				$qa->form_questionnaire_id = $key1;
				
// 				if (isset ( $answers_other )) {
// 					foreach ( $answers_other as $key2 => $value2 ) {
// 						// echo "-------#" . $key2 . "," . $value2 . "<br>";
// 						if ($key2 == $key1) {
// 							$qa->other_description = $value2;
// 						}
// 					}
// 				}
// 				// FILES
// 				if (isset ( $answers_file )) {
// 					$file_ary = CommonUtil::reArrayFiles ( $answers_file );
					
// 					$index = 0;
// 					foreach ( $file_ary as $file ) {
// 						if ($attach_file_indexs [$index]->id == $key1) {
// 							// print 'File Name: ' . $file ['name'];
// 							// print 'File Type: ' . $file ['type'];
// 							// print 'File Size: ' . $file ['size'];
// 							// print '<br>';
// 							if ($file ['size'] > 0) {
// 								self::upload ( $file );
// 								$qa->file_path = '/uploads/' . $file ['name'];
// 							}
// 							$index ++;
// 						}
// 					}
// 				}
				
// 				// $qa->update_from_id,
// 				$qa->revision = 1;
// 				$qa->owner_department_id = UserLoginUtils::getDepartmentId();
// 				$qa->status = 'T';
// 				$qa->create_by = UserLoginUtils::getUsersLoginId ();
// 				$qa->create_date = date ( "Y-m-d H:i:s" );
// 				$qa->update_by = UserLoginUtils::getUsersLoginId ();
// 				$qa->update_date = date ( "Y-m-d H:i:s" );
// 				$qa->save ();
// 			}
			
// 			$transaction->commit ();
// 			$this->render ( '//dashboard/result' );
// 		} else {
			$this->render ( '//dashboard/main' );
// 		}
		
	}
	public function upload($foto) {
		$currentdir = getcwd ();
	
		$upload_dir = $currentdir . "/uploads/";
		$file_dir = $upload_dir . $foto ["name"];
	
		$move = move_uploaded_file ( $foto ["tmp_name"], $file_dir );
		return $file_dir;
	}
	public function actionPermission() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$this->render ( '//dashboard/permission' );
	}
}