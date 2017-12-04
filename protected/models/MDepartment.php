<?php
class MDepartment extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_m_department';
	}
	public function relations() {
		return array (
				
				'branch' => array (
						self::BELONGS_TO,
						'MBranch',
						'branch_id'
				),
				'faculty' => array (
						self::BELONGS_TO,
						'MFaculty',
						'faculty_id'
				)
		);
	}
	public function rules() {
		return array (
				array (
						'id,
						name,isQues,
						faculty_id,
						branch_id',
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
		return new CActiveDataProvider ( get_class ( $this ), array (
				'criteria' => $criteria,
				'sort' => array (
						'defaultOrder' => 't.name asc' 
				),
				'pagination' => array (
						'pageSize' => 100 
				) 
		) ) // ConfigUtil::getDefaultPageSize()

		;
	}
	public function search1() {
		$criteria = new CDbCriteria ();
		
		$criteria->condition = " t.id = " . UserLoginUtils::getDepartmentId ();
		
		return new CActiveDataProvider ( get_class ( $this ), array (
				'criteria' => $criteria,
				'sort' => array (
						'defaultOrder' => 't.name asc' 
				),
				'pagination' => array (
						'pageSize' => ConfigUtil::getDefaultPageSize () 
				) 
		) ) 

		;
	}
	public static function getMax()
	{
	    $criteria = new CDbCriteria();
	    $criteria->condition = " id <> 999";
	    $criteria->order = 'id DESC';
	    $row = self::model()->find($criteria);
	    if (isset($row)) {
	        $max = $row->id;
	        if ($max == 999) {
	            $max = 1000;
	        }
	        return $max + 1;
	    } else {
	        return 1;
	    }
	}
}