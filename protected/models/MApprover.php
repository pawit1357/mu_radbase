<?php
class MApprover extends CActiveRecord {
    public $approver_index;
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'tb_approver';
	}
	public function relations() {
		return array ();
	}
	public function rules() {
		return array (
				array (
						'id,user_id,department_id,approver_index',
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
	public function searchByIndex() {

	    $criteria = new CDbCriteria();
	    $criteria->condition = " approver_index=".$this->approver_index;
	    $criteria->order = 'id DESC';
	    return self::model()->find($criteria);
	}
	public function search() {
		$criteria = new CDbCriteria ();
// 		return new CActiveDataProvider ( get_class ( $this ), array (
// 				'criteria' => $criteria,
// 				'sort' => array (
// 						'defaultOrder' => 't.name asc' 
// 				),
// 				'pagination' => array (
// 						'pageSize' => ConfigUtil::getDefaultPageSize ()
// 						) // ConfigUtil::getDefaultPageSize()
 
// 		) );
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
	
	public static function isMyTask($user,$approve_index)
	{
// 	    echo 'xxx:'.$user->department_id.','.$user->id.','.$approve_index;
	    //fix department equal SI
	    if($user->department_id != 39){
	        return true;
	    }
	    //validate value;
	    $approverIndex = isset($approverIndex) ? $approverIndex : 1;
	    
	    $criteria = new CDbCriteria();
	    $criteria->condition = " user_id =".$user->id." and approver_index=".$approve_index;
	    
	    if (self::model()->exists($criteria)) {
// 	        echo 'TRUE:'.$user->department_id.','.$user->id;
	       return true;
	    } else {
// 	        echo 'FALSE:'.$user->department_id.','.$user->id;
	        
	       return false;
	    }
	    
	}
	
	public static function getAproverInfo($user_id)
	{

	    
	    $criteria = new CDbCriteria();
	    $criteria->condition = " user_id =".$user_id;
	    
	    $row = self::model()->find($criteria);
	    if(isset($row)){
	        return ':'.$row->approver_index.'';
	    }else{
	        return '';
	    }

	    
	}
}