<?php
class UsersRole extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'users_role';
	}
	public function relations() {
		return array (
// 				'branch_group' => array (
// 						self::BELONGS_TO,
// 						'MBranch',
// 						'branch_group_id'
// 				),
		);
	}
	public function rules() {
		return array (
				array (
						'ROLE_ID,
						ROLE_NAME,variable_name,
						ROLE_DESC,
						UPDATE_BY,
						CREATE_DATE,
						UPDATE_DATE,branch_group_id',
						'safe' 
				) 
		);
	}
	public function attributeLabels() {
		return array ()

		;
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
		return new CActiveDataProvider ( get_class ( $this ), array (
				'criteria' => $criteria,
				'sort' => array (
						'defaultOrder' => 't.create_date desc' 
				),
				'pagination' => array (
						'pageSize' => ConfigUtil::getDefaultPageSize ()
						) // ConfigUtil::getDefaultPageSize()
 
		) );
	}
	public static function getMax() {
		$criteria = new CDbCriteria ();
		$criteria->order = 'ROLE_ID DESC';
		$row = self::model ()->find ( $criteria );
		$max = $row->ROLE_ID;
		return $max+1;
	}
}