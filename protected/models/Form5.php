<?php
class Form5 extends CActiveRecord {
	public $rev_id;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_form5';
	}
	public function relations() {
		return array (
				'phisical_status' => array (
						self::BELONGS_TO,
						'MPhisicalStatus',
						'phisical_status_id' 
				),
				'unit' => array (
						self::BELONGS_TO,
						'MUnit',
						'rad_or_maximum_weight_unit_id' 
				),
				'rad' => array (
						self::BELONGS_TO,
						'Form2',
						'form2_id' 
				),
				'types_of_radioactive_waste' => array (
						self::BELONGS_TO,
						'MTypesOfRadioactiveWaste',
						'types_of_radioactive_waste_id' 
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
				'department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'owner_department_id' 
				),
				'company_operates' => array (
						self::BELONGS_TO,
						'MCompanyOperates',
						'company_operates_id' 
				),
				'responsible_department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'responsible_department_id'
				),
				'owner_department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'owner_department_id'
				)
				
		);
	}
	public function rules() {
		return array (
				array (
						'id,
					clear_date,
					form2_id,types_of_radioactive_waste_id,
					phisical_status_id,
					rad_or_maximum_weight,
					rad_or_maximum_weight_unit_id,
					company_operates_id,
					company_operates_other,
					inspection_agency_id,
					inspection_agency_other,
					rid_method,
					update_from_id,
					owner_department_id,
					create_by,
					create_date, 
					update_by, 
					update_date, 
					revision,approve_status,
					status,responsible_department_id,approve_index',
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
					$criteria->condition = " owner_department.faculty_id = " . UserLoginUtils::getFacultyId () . " and t.approve_status in ('STAFF_APPROVE','EXECUTIVE_APPROVE') and t.status ='T'";
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