<?php
class Form2 extends CActiveRecord {
	public $rev_id;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_form2';
	}
	public function relations() {
		return array (
				'code_usage' => array (
						self::BELONGS_TO,
						'MCodeUsage',
						'code_usage_id' 
				),
				'bpm_radioactive_elements' => array (
						self::BELONGS_TO,
						'MRadioactiveElement',
						'bpm_radioactive_elements_id' 
				),
				'machine_manufacturer' => array (
						self::BELONGS_TO,
						'Manufacturer',
						'maufacturer_id' 
				),
				'dealer' => array (
						self::BELONGS_TO,
						'MDealerCompany',
						'dealer_id' 
				),
				
				'phisicalStatus' => array (
						self::BELONGS_TO,
						'MPhisicalStatus',
						'bpm_phisical_id' 
				),
				'unit' => array (
						self::BELONGS_TO,
						'MUnit',
						'bpm_volume_unit_id' 
				),
				'unit2' => array (
						self::BELONGS_TO,
						'MUnit2',
						'bpm_volume_unit_id2' 
				),
				'room' => array (
						self::BELONGS_TO,
						'MRoom',
						'room_id' 
				),
				'donateToDepartment' => array (
						self::BELONGS_TO,
						'MDepartment',
						'usage_status_to_department_id' 
				),
				'usageStatus' => array (
						self::BELONGS_TO,
						'MaterialStatus',
						'usage_status_id' 
				),
				'materialStatus' => array (
						self::BELONGS_TO,
						'MaterialStatus',
						'material_status_id' 
				),
				'updateby' => array (
						self::BELONGS_TO,
						'UsersLogin',
						'update_by' 
				),
				'createby' => array (
						self::BELONGS_TO,
						'UsersLogin',
						'create_by' 
				),
				'department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'owner_department_id' 
				),
				'manufacturer' => array (
						self::BELONGS_TO,
						'Manufacturer',
						'manufacturer_id' 
				),
				'owner_department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'owner_department_id' 
				) 
		)
		;
	}
	public function rules() {
		return array (
				array (
						'id,
						type, 
						code_usage_id, 
						code_usage_other,
						bpm_radioactive_elements_id, 
						bpm_radioactive_elements_other,
					
						bpm_model,
						bpm_no,
						manufacturer_id, 
						manufacturer_other,
						dealer_id, dealer_other,
						bpm_phisical_id, 
						bpm_volume,
						bpm_volume_unit_id, 
						bpm_volume2,
						bpm_volume_unit_id2, 
						bpm_as_of_date,
						bpm_number,
						room_id, 
						usage_status_id,material_status_id, 
						license_no,
						license_expire_date,
						supervisor_name,
						supervisor_phone,
						supervisor_email,
						revision, 
						create_by, 
						create_date,
						update_by, 
						update_date,
						owner_department_id, 
						update_from_id, usage_status_to_remark,usage_status_to_department_id,approve_status
						status',
						'safe' 
				) 
		);
	}
	public function attributeLabels() {
		return array ();
	}
	public function getUrl($post = null) {
		if ($post === null)
			$post = $this->post;
		return $post->url . '#c' . $this->id;
	}
	protected function beforeSave() {
		return true;
	}
	public function search($type) {
		$criteria = new CDbCriteria ();
		$criteria->with = array (
				'owner_department'
		);
		if ($type != 0) {
			if (isset ( $this->rev_id )) {
				$criteria->condition = " t.status = 'F' and t.type=" . $type . " and t.update_from_id=" . $this->rev_id;
			} else {
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria->condition = " t.status ='T' and t.type=" . $type;
						break;
					case UserLoginUtils::USER :
						$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T' and t.type=" . $type;
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('STAFF_APPROVE','EXECUTIVE_APPROVE') and t.status ='T' and t.type=" . $type;
						break;
					case UserLoginUtils::STAFF :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('USER_APPROVE','STAFF_APPROVE','EXECUTIVE_APPROVE')  and t.status ='T' and t.type=" . $type;
						break;
				}
			}
		} else {
			if (isset ( $this->rev_id )) {
				$criteria->condition = " t.status = 'F' and t.update_from_id=" . $this->rev_id;
			} else {
				switch (UserLoginUtils::getUserRoleName ()) {
					case UserLoginUtils::ADMIN :
						$criteria->condition = " t.status ='T'";
						break;
					case UserLoginUtils::USER :
						$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
						break;
					case UserLoginUtils::EXECUTIVE :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status='STAFF_APPROVE' and t.status ='T'";
						break;
					case UserLoginUtils::STAFF :
						$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('USER_APPROVE','STAFF_APPROVE','EXECUTIVE_APPROVE')  and t.status ='T'";
						break;
				}
			}
		}
		return new CActiveDataProvider ( get_class ( $this ), array (
				'criteria' => $criteria,
				'sort' => array (
						'defaultOrder' => 't.update_date desc' 
				),
				'pagination' => array (
						'pageSize' => ConfigUtil::getDefaultPageSize () 
				) 
		) );
	}
}