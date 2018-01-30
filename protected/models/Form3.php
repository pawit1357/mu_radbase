<?php
class Form3 extends CActiveRecord {
	public $rev_id;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_form3';
	}
	public function relations() {
		return array (
				
				'rad' => array (
						self::BELONGS_TO,
						'Form2',
						'form2_id' 
				),
				'from_room' => array (
						self::BELONGS_TO,
						'MRoom',
						'from_room_id' 
				),
				'to_room' => array (
						self::BELONGS_TO,
						'MRoom',
						'to_room_id' 
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
				'material_type' => array (
						self::BELONGS_TO,
						'MaterialType',
						'material_type_id' 
				),
				'position' => array (
						self::BELONGS_TO,
						'MPosition',
						'position_id' 
				),
				'strengthRadiationUnit' => array (
						self::BELONGS_TO,
						'MUnit',
						'strength_radiation_unit_id' 
				),
				'owner_department' => array (
						self::BELONGS_TO,
						'MDepartment',
						'owner_department_id' 
				) ,
				
				
		);
	}
	public function rules() {
		return array (
				array (
						'id,
							form2_id,material_type_id,
							from_room_id,
							to_room_id, 
							date_from,
							date_to,
							supervisor_name,
							supervisor_phone,
							supervisor_email,
							create_by, 
							create_date,
							update_by,
							update_date,
							revision,
							owner_department_id,
							status,approve_status,
							update_from_id,position_id,strength_radiation_unit_id,strength_radiation,check_date,approve_index',
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