<?php
// session_start ();
class UserLoginUtils {
	const ADMIN = "ADMIN";
	const USER = "USER";
	const STAFF = "STAFF";
	const EXECUTIVE = "EXECUTIVE";
	const INIT_APPROVE = "";
	const USER_APPROVE = "USER_APPROVE";
	const STAFF_APPROVE = "STAFF_APPROVE";
	const EXECUTIVE_APPROVE = "EXECUTIVE_APPROVE";
	public static function isLogin() {
		return isset ( $_SESSION ['USER_LOGIN_ID'] );
	}
	public static function logout() {
		unset ( $_SESSION ['USER_LOGIN_ID'] );
		unset ( $_SESSION ['USER_INFO'] );
		
		unset ( $_SESSION ['USER_ROLE_ID'] );
		unset ( $_SESSION ['USER_ROLE_NAME'] );
		
		unset ( $_SESSION ['FAIL_MESSAGE'] );
		unset ( $_SESSION ['MENU_IN_ROLE'] );
		unset ( $_SESSION ['DEPARTMENT_ID'] );
		unset ( $_SESSION ['BRANCH_ID'] );
		unset ( $_SESSION ['FACULTY_ID'] );
	}
	public static function authen($username, $password) {
		$criteria = new CDbCriteria ();
		$criteria->condition = "username = '" . $username . "' and password='" . md5 ( $password ) . "' and status='A'";
		$UsersLogin = UsersLogin::model ()->findAll ( $criteria );
		if (isset ( $UsersLogin [0] )) {
			$_SESSION ['USER_INFO'] = serialize ( $UsersLogin [0] );
			
			$_SESSION ['USER_LOGIN_ID'] = $UsersLogin [0]->id;
			$_SESSION ['USER_ROLE_ID'] = $UsersLogin [0]->role_id;
			$_SESSION ['USER_ROLE_NAME'] = $UsersLogin [0]->users_role->variable_name;
			$_SESSION ['IS_FORCE_CHANGE_PASSWORD'] = ($UsersLogin [0]->is_force_change_password == "1" ? true : false);
			$_SESSION ['DEPARTMENT_ID'] = $UsersLogin [0]->department->id;
			$_SESSION ['BRANCH_ID'] = CommonUtil::IsNullOrEmptyString ( $UsersLogin [0]->branch_group_id ) ? "1" : $UsersLogin [0]->branch_group_id;
			$_SESSION ['FACULTY_ID'] = $UsersLogin [0]->department->faculty_id;
			
			// Get Menu By Role
			$cri = new CDbCriteria ();
			$cri->condition = " ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE='1'";
			
			$mroles = MenuRole::model ()->findAll ( $cri );
			if (isset ( $mroles )) {
				$listOfMenuInRole = '';
				foreach ( $mroles as $mr ) {
					$listOfMenuInRole .= $mr->MENU_ID . ',';
				}
				if (strlen ( $listOfMenuInRole ) > 0) {
					$criMP = new CDbCriteria ();
					$criMP->condition = " MENU_ID in (" . rtrim ( $listOfMenuInRole, ',' ) . ")";
					$criMP->order = 'DISPLAY_ORDER ASC';
					$Menus = Menu::model ()->findAll ( $criMP );
					$_SESSION ['MENU_IN_ROLE'] = serialize ( $Menus );
				}
			}
			
			// -------------------
			return true;
		} else {
			$_SESSION ['FAIL_MESSAGE'] = 'Incorrect Username or Password!';
			return false;
		}
	}
	public static function isForceChangePassword() {
		if (isset ( $_SESSION ['IS_FORCE_CHANGE_PASSWORD'] )) {
			return $_SESSION ['IS_FORCE_CHANGE_PASSWORD'];
		} else {
			return false;
		}
	}
	public static function getUserRole() {
		if (isset ( $_SESSION ['USER_ROLE_ID'] )) {
			return $_SESSION ['USER_ROLE_ID'];
		} else {
			return - 1;
		}
	}
	public static function getUsersLoginId() {
		if (isset ( $_SESSION ['USER_LOGIN_ID'] )) {
			return $_SESSION ['USER_LOGIN_ID'];
		} else {
			return - 1;
		}
	}
	public static function getUserInfo() {
		if (isset ( $_SESSION ['USER_INFO'] )) {
			return unserialize ( $_SESSION ['USER_INFO'] );
		} else {
			return null;
		}
	}
	public static function getUserRoleName() {
		if (isset ( $_SESSION ['USER_ROLE_NAME'] )) {
			return $_SESSION ['USER_ROLE_NAME'];
		} else {
			return null;
		}
	}
	public static function getMenuInRole() {
		if (isset ( $_SESSION ['MENU_IN_ROLE'] )) {
			return unserialize ( $_SESSION ['MENU_IN_ROLE'] );
		} else {
			return null;
		}
	}
	public static function getDepartmentId() {
		if (isset ( $_SESSION ['DEPARTMENT_ID'] )) {
			return $_SESSION ['DEPARTMENT_ID'];
		} else {
			return null;
		}
	}
	public static function getBranchId() {
		if (isset ( $_SESSION ['BRANCH_ID'] )) {
			return $_SESSION ['BRANCH_ID'];
		} else {
			return null;
		}
	}
	public static function getFacultyId() {
		if (isset ( $_SESSION ['FACULTY_ID'] )) {
			return $_SESSION ['FACULTY_ID'];
		} else {
			return null;
		}
	}
	public static function getLoginInfo() {
		if (self::isLogin ()) {
			$UsersLogin = UsersLogin::model ()->findByPk ( self::getUsersLoginId () );
// 			$branchs = MBranch::model ()->findAll ();
			return "<i class=\"fa fa-user\"></i>" . $UsersLogin->first_name . "  ". $UsersLogin->last_name . " <i class=\"fa fa-key\"></i>(" .  strtolower ( $UsersLogin->users_role->variable_name ).MApprover::getAproverInfo(self::getUsersLoginId())  . ") <i class=\"fa fa-university\"></i> สาขา " .$UsersLogin->department->branch_id. ' ภาควิชา ' .  $UsersLogin->department->name .' '. $UsersLogin->department->faculty->name . (isset($UsersLogin->branch_group->name)? '  (ด้าน' .$UsersLogin->branch_group->name.')':'') ; // .' ['.CommonUtil::getBranchName($UsersLogin->branch_group_id, $branchs).']';
		} else {
			return '';
		}
	}
	public static function canCreate($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		// echo "<font color='red'>".$link [1] . '/' . $link [2]."</font>";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			foreach ( $menus as $menu ) {
				
				$cri1 = new CDbCriteria ();
				$cri1->condition = " MENU_ID = " . $menu->MENU_ID . " AND ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE=1";
				$menuRoles = MenuRole::model ()->findAll ( $cri1 );
				if (isset ( $menuRoles [0] )) {
					return $menuRoles [0]->IS_CREATE;
				} else {
					// return null;
				}
			}
			return null;
		} else {
			return null;
		}
		
		return $result;
	}
	public static function canUpdate($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			foreach ( $menus as $menu ) {
				
				$cri1 = new CDbCriteria ();
				$cri1->condition = " MENU_ID = " . $menu->MENU_ID . " AND ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE=1";
				$menuRoles = MenuRole::model ()->findAll ( $cri1 );
				if (isset ( $menuRoles [0] )) {
					return $menuRoles [0]->IS_EDIT;
				} else {
					// return null;
				}
			}
			return null;
		} else {
			return null;
		}
		
		// $menus = Menu::model ()->findAll ( $cri );
		// if (isset ( $menus [0] )) {
		
		// $cri1 = new CDbCriteria ();
		// $cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole ();
		// $menuRoles = MenuRole::model ()->findAll ( $cri1 );
		// if (isset ( $menuRoles )) {
		
		// $result = $menuRoles [0]->IS_EDIT;
		// }
		// }
		
		return $result;
	}
	public static function canDelete($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			foreach ( $menus as $menu ) {
				
				$cri1 = new CDbCriteria ();
				$cri1->condition = " MENU_ID = " . $menu->MENU_ID . " AND ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE=1";
				$menuRoles = MenuRole::model ()->findAll ( $cri1 );
				if (isset ( $menuRoles [0] )) {
					return $menuRoles [0]->IS_DELETE;
				} else {
					// return null;
				}
			}
			return null;
		} else {
			return null;
		}
		
		// $menus = Menu::model ()->findAll ( $cri );
		// if (isset ( $menus [0] )) {
		
		// $cri1 = new CDbCriteria ();
		// $cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole ();
		// $menuRoles = MenuRole::model ()->findAll ( $cri1 );
		// if (isset ( $menuRoles )) {
		
		// $result = $menuRoles [0]->IS_DELETE;
		// }
		// }
		
		return $result;
	}
	public static function getUsersLoginById($user_id) {
		$UsersLogin = UsersLogin::model ()->findByPk ( $user_id );
		return $UsersLogin;
	}
	public static function authorizePage($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$cur_page = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $cur_page );
		
		// echo "<font color='red'>" . $cur_page . "</font>";
		
		$cri = new CDbCriteria ();
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			foreach ( $menus as $menu ) {
				
				$cri1 = new CDbCriteria ();
				$cri1->condition = " MENU_ID = " . $menu->MENU_ID . " AND ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE=1";
				$menuRoles = MenuRole::model ()->findAll ( $cri1 );
				if (isset ( $menuRoles [0] )) {
					return $menuRoles [0]->IS_ACTIVE;
				} else {
					// return null;
				}
			}
			return null;
		} else {
			return null;
		}
	}
	public static function getApprover($status) {
		$criteria = new CDbCriteria ();
		$criteria->with = array (
				'department',
				'users_role' 
		);
		$criteria->condition = "department.faculty_id = " . self::getFacultyId () . " and users_role.variable_name='" . $status . "'";
		$usersLogin = UsersLogin::model ()->findAll ( $criteria );
		return (isset($usersLogin [0])? $usersLogin [0]:null);
	}
}
?>