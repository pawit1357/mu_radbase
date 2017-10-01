<?php
class AjaxRequestController extends CController {
	public $layout = 'ajax';
	private $_model;
	
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex() {
	}
	
	public function actionGetMachine($branch_group_id,$code_usage_id) {
	

		$query = "select id,name from tb_m_rad_machine where branch_group_id like '%".$branch_group_id."%' and code_usage_id=".$code_usage_id." order by name ";
		$command = Yii::app()->db->createCommand($query);
		$result = $command->queryAll();
		
		$ret = array_values($result);
		

		
		echo CJSON::encode($ret);
		Yii::app()->end();

		echo json_encode ( $machines );
	}
	
	
	public function actionIsAlreadyAsnwer($department) {
		
		$json = array ();

		$cri = new CDbCriteria ();
		$cri->condition = " owner_department_id=".$department;
		
		$qas = FormQuestionnaireAnswer::model ()->findAll ( $cri );
		if (isset ( $qas )) {
			
			$json ["result"] =count($qas);
		}
		
		echo json_encode ( $json );
		
	}
	public function actionGetRayGenerator() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_rad_machine";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetRadMachine() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT f1.id,rm.name FROM tb_form1 f1 left join tb_m_rad_machine rm on f1.rad_machine_id = rm.id";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetForm2() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT f2.id,re.name FROM tb_form2 f2 left join tb_m_radioactive_elements re on f2.radioactive_elements_id = re.id";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetRadioactive() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_radioactive_elements";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetCodeUsage() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_code_usage";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetManufacturer() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_manufacturer";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetUseType() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_use_type";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetDepartment() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_department";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetPower() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_power";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetDealer() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_dealer";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetUsageStatus() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_usage_status";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetDealerCompany() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_dealer_company";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetRoom() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_room";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetMaterialStatus() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_material_status";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetPhisicalStatus() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_phisical_status";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetPosition() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_position";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetMaterialType() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,name FROM tb_m_material_type";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetUsers() {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT id,username,first_name,last_name FROM users_login";
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
}