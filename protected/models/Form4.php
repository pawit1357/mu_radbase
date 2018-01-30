<?php
class Form4 extends CActiveRecord {
	public $rev_id;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_form4';
	}
	public function relations() {
		return array (
				'position' => array (
						self::BELONGS_TO,
						'MPosition',
						'position_id' 
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
				'report_department' => array (
						self::BELONGS_TO,
						'MReportDepartment',
						'report_department_id' 
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
						name,
						hp_10_volume,
						hp_007_volume,rso_level_id,report_department_id,
						hp_3_volume,result,
						report_year,report_month,
						create_by,
						create_date,
						update_by,
						update_date,approve_status,
						revesion,is_rso_staff,owner_department_id,description,file_path,is_rso_actual_work,rso_license_expire,approve_index',
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