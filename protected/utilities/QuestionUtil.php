<?php
class QuestionUtil {
	
	public static function getAnswerValue($answers,$type,$qid,$radio_compare){
		$result = '';
		/*
		 * TYPE
		 * 1= Radio
		 * 2= TextBox
		 * 3= CheckBox
		 * 4= File
		 * */
		
		if (isset ( $answers )) {
			foreach ( $answers as $item ) {
				if($item->question_id ==$qid && $item->type == $type){
					switch ($item->type){
						case "1":
							$result = ($item->value == $radio_compare)? 'checked=checked':'';
							break;
						case "2":
							$result = $item->value;
							break;
						case "3":
							$result = ($item->value == $radio_compare)? 'checked=checked':'';
							break;
						case "4":
							$result = $item->value;
							break;
					}
					break;
				}
			}
		}
		return $result;
	}
	
	public static function generateQuestion() {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$owner_id = 0;
		if(isset( $_GET ['id'])){
			$owner_id =$_GET ['id'];
		}else{
			$owner_id = UserLoginUtils::getDepartmentId();
		}
		
		$result = "";
		$cri = new CDbCriteria ();
		$cri->condition = " parent =1";
		
		$ques = FormQuestionnaire::model ()->findAll ( $cri );
		if (isset ( $ques )) {
			foreach ( $ques as $q ) {
				$result = $result . '<div class="form-group">';
				$result = $result . '<label>' . $q->number . '  ' . $q->question . '</label>';
				
				$result = $result . '<div class="input-group">';
				$result = $result . '<div class="icheck-list">';
				// #---------------------
				$cri1 = new CDbCriteria ();
				$cri1->condition = " parent =" . $q->id;
				$ques1 = FormQuestionnaire::model ()->findAll ( $cri1 );
				
				if (isset ( $ques1 )) {
					
					foreach ( $ques1 as $q1 ) {
						$criAns = new CDbCriteria ();
						$criAns->condition = " form_questionnaire_id =" . $q1->id . " and owner_department_id=" . $owner_id;
						$ans = FormQuestionnaireAnswer::model ()->findAll ( $criAns );
						
						// echo $q1->id.'---->'.$ans[0]->other_description;
						
						$result = $result . '<table><tr><td>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<label> <input name="answers_choice[' . $q1->id . ']"  type="checkbox" class="icheck" data-checkbox="icheckbox_flat-grey" ' . (isset ( $ans [0] ) ? 'checked' : '') . '></label>';
						
						if ($q1->has_other) {
							$result = $result . '<td> <input name="answers_other[' . $q1->id . ']" type="text" style="width:350px" placeholder="' . $q1->question . '"></td>';
						} else {
							$result = $result . $q1->question;
						}
						'&nbsp;&nbsp;</td>';
						
						if ($q1->has_attach_file) {
							$result = $result . '<td><label> <input name="answers_file[]" type="file" style="display: none;" /><a> &nbsp;&nbsp;แนบไฟล์</a></label>';
							if (isset ( $ans [0]->file_path )) {
								$result = $result . '<a target="_blank" title="Download" class="fa fa-download" href="' . ConfigUtil::getAppName () . '' . $ans [0]->file_path . '">&nbsp;ดาวโหลด</a>';
							}
							$result = $result . '</td>';
						}
						
						$result = $result . '</tr></table>';
						// #---------------------
						$cri2 = new CDbCriteria ();
						$cri2->condition = " parent =" . $q1->id;
						$ques2 = FormQuestionnaire::model ()->findAll ( $cri2 );
						if (isset ( $ques2 )) {
							foreach ( $ques2 as $q2 ) {
								
								$criAns2 = new CDbCriteria ();
								$criAns->condition = " form_questionnaire_id =" . $q2->id . " and owner_department_id=" . $owner_id;
								$ans2 = FormQuestionnaireAnswer::model ()->findAll ( $criAns2 );
								
								$result = $result . '<table><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input name="answers_choice[' . $q2->id . ']"  type="checkbox" class="icheck" data-checkbox="icheckbox_flat-grey" ' . (isset ( $ans2 [0] ) ? 'checked' : '') . '></label>';
								
								if ($q2->has_other) {
									$result = $result . '<td> <input name="answers_other[' . $q2->id . ']" type="text" style="width:318px" placeholder="' . $q2->question . '"></td>';
								} else {
									$result = $result . $q2->question;
								}
								
								'&nbsp;&nbsp;</td>';
								
								if ($q2->has_attach_file) {
									$result = $result . '<td> <label> <input name="answers_file[]" type="file" style="display: none;" /><a> &nbsp;&nbsp;แนบไฟล์</a></label>';
									if (isset ( $ans2 [0]->file_path )) {
										$result = $result . '<a target="_blank" title="Download" class="fa fa-download" href="' . ConfigUtil::getAppName () . '' . $ans2 [0]->file_path . '">&nbsp;ดาวโหลด</a>';
									}
									$result = $result . '</td>';
								}
								$result = $result . '</tr></table>';
							}
						}
						#---------------------
					}
				}
				// #---------------------
				
				$result = $result . '</div>';
				$result = $result . '</div>';
				$result = $result . '</div>';
				$result = $result . '</br>';
			}
		}
		return $result;
	}
}