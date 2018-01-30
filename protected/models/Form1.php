<?php
class Form1 extends CActiveRecord {
	public $rev_id;
	public $department_id;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_form1';
	}
	public function relations() {
		
		// ',
		// ,
		// ,
		// ,
		// ,
		// ,
		// ,
		// ,
		// room_id,
		// inspection_agency_id
		return array (
				
				'code_usage' => array (
						self::BELONGS_TO,
						'MCodeUsage',
						'code_usage_id' 
				),
				'rad_machine' => array (
						self::BELONGS_TO,
						'MRadMachine',
						'rad_machine_id' 
				),
				'power_unit' => array (
						self::BELONGS_TO,
						'MPowerUnit',
						'power_unit_id' 
				),
				'power_unit2' => array (
						self::BELONGS_TO,
						'MPowerUnit',
						'power_unit_id2' 
				),
				'maufacturer' => array (
						self::BELONGS_TO,
						'Manufacturer',
						'maufacturer_id' 
				),
				'dealer' => array (
						self::BELONGS_TO,
						'MDealerCompany',
						'dealer_id' 
				),
				'use_type' => array (
						self::BELONGS_TO,
						'MUseType',
						'use_type_id' 
				),
				'utilization' => array (
						self::BELONGS_TO,
						'MUtilization',
						'utilization_id' 
				),
				'usage_status' => array (
						self::BELONGS_TO,
						'MUsageStatus',
						'usage_status_id' 
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
				'inspection_agency' => array (
						self::BELONGS_TO,
						'MInspectionAgency',
						'inspection_agency_id' 
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
						'id,code_usage_id,
						rad_machine_id,
						rad_machine_other,
						model,
						serial_number,
						power_unit_id,
						maufacturer_id,maufacturer_other,
						dealer_id,dealer_other,owner_department_id,
						use_type_id,
						utilization_id,utilization_other,
						license_no,
						license_expire_date,
						machine_owner,
						machine_owner_phone,
						machine_owner_email,
						usage_status_id,
						room_id,
						delivery_date_year,delivery_date_month,delivery_date_day,
						inspection_agency_id,inspection_agency_other,room_plan,
						quality_check_date,file_path,usage_status_remark,power,power2,power_unit_id2,usage_status_remark,usage_status_to_department_id,approve_index',
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
	public function search() {
		$criteria = new CDbCriteria ();
		$criteria->with = array (
				'owner_department' 
		);
		if (isset ( $this->rev_id )) {
			$criteria->condition = " t.status='F' and t.update_from_id=" . $this->rev_id;
		} else {
			switch (UserLoginUtils::getUserRoleName ()) {
				case UserLoginUtils::ADMIN :
					$criteria->condition = " t.status ='T'";
					break;
				case UserLoginUtils::USER :
					$criteria->condition = " t.owner_department_id = " . UserLoginUtils::getDepartmentId () . " and t.status ='T'";
					break;
				case UserLoginUtils::EXECUTIVE :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('STAFF_APPROVE','EXECUTIVE_APPROVE')  and t.status ='T'";
					break;
				case UserLoginUtils::STAFF :
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('USER_APPROVE','STAFF_APPROVE','EXECUTIVE_APPROVE')  and t.status ='T'";
					break;

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