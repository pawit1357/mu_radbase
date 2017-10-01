<?php
class MTypesOfRadioactiveWaste extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_m_types_of_radioactive_waste';
	}
	public function relations() {
		return array ();
	}
	public function rules() {
		return array (
				array (
						'id,name,branch_group_id,description',
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
						'defaultOrder' => 't.name asc' 
				),
				'pagination' => array (
						'pageSize' => ConfigUtil::getDefaultPageSize ()
						) // ConfigUtil::getDefaultPageSize()
 
		) );
	}
	public static function getMax() {
		$criteria = new CDbCriteria ();
		$criteria->condition = " id <> 999";
		$criteria->order = 'id DESC';
		$row = self::model ()->find ( $criteria );
		$max = $row->id;
		if($max == 999){
			$max = 1000;
		}
		return $max+1;
	}
}