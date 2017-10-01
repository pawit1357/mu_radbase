<?php
ini_set ( 'max_execution_time', 0 );
class CommonUtil {
	public static function IsNullOrEmptyString($question) {
		return (! isset ( $question ) || trim ( $question ) === '');
	}
	public static function deleteDirectory($dirPath) {
		if (! is_dir ( $dirPath )) {
			throw new InvalidArgumentException ( "$dirPath must be a directory" );
		}
		if (substr ( $dirPath, strlen ( $dirPath ) - 1, 1 ) != '/') {
			$dirPath .= '/';
		}
		$files = glob ( $dirPath . '*', GLOB_MARK );
		foreach ( $files as $file ) {
			if (is_dir ( $file )) {
				self::deleteDirectory ( $file );
			} else {
				unlink ( $file );
			}
		}
		rmdir ( $dirPath );
	}
	function clean($string) {
		$string = str_replace ( ' ', '-', $string ); // Replaces all spaces with hyphens.
		
		return preg_replace ( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
	}
	public static function endsWith($FullStr, $needle) {
		$StrLen = strlen ( $needle );
		$FullStrEnd = substr ( $FullStr, strlen ( $FullStr ) - $StrLen );
		return $FullStrEnd == $needle;
	}
	public static function dateDiff($date1, $date2) {
		// $datetime1 = new DateTime ( $date1 );
		// $datetime2 = new DateTime ( $date2 );
		// $interval = $datetime1->diff ( $datetime2 );
		// return $interval->format ( '%a' );
		$unixOriginalDate = strtotime ( $date1 );
		$unixNowDate = strtotime ( $date2 );
		$difference = $unixNowDate - $unixOriginalDate;
		$days = ( int ) ($difference / 86400);
		$hours = ( int ) ($difference / 3600);
		$minutes = ( int ) ($difference / 60);
		$seconds = $difference;
		return $days;
	} // end function dateDiff
	public static function getDateThai($date) {
		
		list ( $year, $month, $day ) = explode ( "-", $date );
		if($year == '0000' && $day=='00' && $month=='00'){
			return '';
		}else{
			
			return $day . '/' . $month . '/' . ((( int ) $year) + 543);
		}
	}
	
	public static function getDateThaiMoreOne($date) {
		$dateList = explode(",", $date);
		$returnData = '';
		for($i=0;$i<count($dateList);$i++){
			list ( $year, $month, $day ) = explode ( "-", $dateList[$i]);
			$returnData .=$day . '/' . $month . '/' . ((( int ) $year) + 543).',';
		}
		return rtrim($returnData,',');
// 		list ( $year, $month, $day ) = explode ( "-", $date );
// 		if($year == '0000' && $day=='00' && $month=='00'){
// 			return '';
// 		}else{
			
// 			return $day . '/' . $month . '/' . ((( int ) $year) + 543);
// 		}
	}
	
	public static function getInspectionAgencyId($inspection_agency_id){
		$returnData = '';
		$dateList = explode(",", $inspection_agency_id);
		for($i=0;$i<count($dateList);$i++){
			$value = MInspectionAgency::model ()->findbyPk ( $dateList[$i]);
			if(isset($value)){
				$returnData.=$value->name.',';
			}
		}
		
		return rtrim($returnData,',');
	}
	public static function getDate($date) {
		
		list ( $day, $month, $year ) = explode ( "/", $date );
		
		return ((( int ) $year) - 543) . '-' . $month . '-' . $day;
	}
	public static function concatDate($d, $m, $y) {
		return (isset ( $d ) ? $d . "/" : "") . $m . "/" . $y;
	}
	
	// public static function getLastRevision($ref_doc) {
	// $criteria = new CDbCriteria ();
	// $criteria->condition = "refer_doc ='" . $ref_doc . "'";
	// $criteria->order = 'id DESC';
	// $row = Form1::model ()->find ( $criteria );
	// $somevariable = $row->revision;
	// return $somevariable + 1;
	// }
	// public static function getLastRevision_Form2($ref_doc, $type) {
	// $criteria = new CDbCriteria ();
	// $criteria->condition = "ref_doc ='" . $ref_doc . "' AND type=" . $type;
	// $criteria->order = 'id DESC';
	// $row = Form2::model ()->find ( $criteria );
	// $somevariable = $row->revision;
	// return $somevariable + 1;
	// }
	// public static function getLastRevision_Form3($ref_doc) {
	// $criteria = new CDbCriteria ();
	// $criteria->condition = "ref_doc ='" . $ref_doc . "'";
	// $criteria->order = 'id DESC';
	// $row = Form3::model ()->find ( $criteria );
	// $somevariable = $row->revision;
	// return $somevariable + 1;
	// }
	// public static function getLastRevision_Form6($ref_doc) {
	// $criteria = new CDbCriteria ();
	// $criteria->condition = "ref_doc ='" . $ref_doc . "'";
	// $criteria->order = 'id DESC';
	// $row = Form6::model ()->find ( $criteria );
	// $somevariable = $row->revision;
	// return $somevariable + 1;
	// }
	public static function getValue($id) {
		$criteria = new CDbCriteria ();
		$criteria->condition = "id ='" . $id . "'";
		$criteria->order = 'id DESC';
		$row = MSetting::model ()->find ( $criteria );
		$somevariable = $row->value;
		return $somevariable;
	}
	public static function reArrayFiles($file_post) {
		$file_ary = array ();
		$file_count = count ( $file_post ['name'] );
		$file_keys = array_keys ( $file_post );
		
		for($i = 0; $i < $file_count; $i ++) {
			foreach ( $file_keys as $key ) {
				$file_ary [$i] [$key] = $file_post [$key] [$i];
			}
		}
		
		return $file_ary;
	}
	public static function upload($file) {
		$currentdir = getcwd ();
				
		$temp = explode(".", $file["name"]);
		$newfilename = round(microtime(true)) . '.' . end($temp);
		
		$upload_dir = $currentdir . "/uploads/";
		$file_dir = $upload_dir . $newfilename;
		
		
		$move = move_uploaded_file ( $file ["tmp_name"], $file_dir );
		return $newfilename;
	}
	public static function getBranchName($ids, $branchs) {
		$result = '';
		$branchIds = explode ( ",", $ids );
		
		if (isset ( $branchs )) {
			foreach ( $branchs as $item ) {
				foreach ( $branchIds as $id ) {
					if ($id == $item->id) {
						$result .= $item->name . ',';
					}
				}
			}
		}
		return rtrim ( $result, "," );
	}
	public static function getRadMachineName($ids) {
		$result = '';
		$items = explode ( ",", $ids );
		
		if (isset ( $items )) {
			foreach ( $items as $id ) {
				if ($id == "1") {
					$result .= 'เครื่องกำเนิดรังสี,';
				}
				if ($id == "2") {
					$result .= 'วัสดุกัมมันตรังสี,';
				}
			}
		}
		return rtrim ( $result, "," );
	}
	public static function getApproveStatus($status) {
		$result = '';
		switch ($status) {
			case UserLoginUtils::INIT_APPROVE :
				$result = 'waiting user save.';
				break;
			case UserLoginUtils::USER_APPROVE :
				$result = 'waiting staff approve.';
				break;
			case UserLoginUtils::STAFF_APPROVE :
				$result = 'waiting executive approve.';
				break;
			case UserLoginUtils::EXECUTIVE_APPROVE:
				$result = 'executive approved.';
				break;
			default:
				$result = 'waiting user save.';
				break;
			
		}
				
		return $result;
	}
	public static function getSealTypeName($ids) {
		$result = '';
		$items = explode ( ",", $ids );
		
		if (isset ( $items )) {
			foreach ( $items as $id ) {
				if ($id == "1") {
					$result .= 'ปิดผนึก,';
				}
				if ($id == "2") {
					$result .= 'ไม่ปิดผนึก,';
				}
			}
		}
		return rtrim ( $result, "," );
	}
	public static function getMonthById($id) {
		$ThMonth = array (
				"มกราคม",
				"กุมภาพันธ์",
				"มีนาคม",
				"เมษายน",
				"พฤษภาคม",
				"มิถุนายน",
				"กรกฏาคม",
				"สิงหาคม",
				"กันยายน",
				"ตุลาคม",
				"พฤศจิกายน",
				"ธันวาคม" 
		);
		return $ThMonth [$id];
	}
	/* #MASTER# */
	/* #MASTER# */
	const CHECKBOX_TYPE = "1";
	const TEXT_TYPE = "2";
	const TABLE_TYPE = "3";
	const FILE_TYPE = "4";
}
?>