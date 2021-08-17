<?php

namespace app\common\model;
use think\Db;
use think\db\builder\Mysql;
use think\Model;
class User extends Model{
		//通过ID获取用户信息
	
    public  static function  getOneUser($id){
        $user = self::where('user_id','=',$id)->find();
        if($user){
            return $user;
        }
        return "false";
	}

	public  static function  getAllUser(){
		$sql = "select *from user";
		$res = parent::query($sql);
        return $res;
	}
	public  static function  deleteUser($user_id){
		$sql = "delete from user where user_id = ".$user_id;
		$res = parent::query($sql);
        return $res;
	}
	
	
	// public function UpdateUser($user_id)
	// {
	// 	# code...

	// }
	public static function UpdateUser($id, $user_name, $user_pass,$sex,$phone, $introduction){
		$sql = "update user set user_name='$user_name'";
		if(!empty($user_pass)){
			$sql .= ", password=md5('$user_pass')";
		}
		$sql .= ", sex='$sex'";
		$sql .= ", phone='$phone'";
		$sql .= ", introduction='$introduction'";
		$sql .= " where user_id = $id";
		$res = parent::query($sql);
		return $res;
	}

    // function GetAllUser(){
	// 	$sql = "select * from user_list;";
	// 	//$db = MySQLDB::GetInstance($config);
	// 	$data = $this->_dao->GetRows($sql);
	// 	return $data;
	// }
	// function GetUserCount(){
	// 	$sql = "select count(*) as c from user_list;";
	// 	//$db = MySQLDB::GetInstance($config);
	// 	$data = $this->_dao->GetOneData($sql);
	// 	return $data;
	// }
	// function GetUserInfoById($id){
	// 	$sql = "select * from user_list where user_id = $id;";
	// 	$data = $this->_dao->GetOneRow($sql);
	// 	return $data;
	// }
	// function GetUserInfoByUserName($name){
	// 	//......
	// }
	// function delUserById($id){
	// 	$sql = "delete from user_list where user_id = $id";
	// 	$data = $this->_dao->exec($sql);
	// 	return $data;
	// }
	// function InsertUser($username, $password,$age,$xueli, $xingqu,$from){
	// 	$sql = "insert into user_list (user_name,user_pass,age,edu,xingqu,`from`,reg_time)values(";
	// 	$sql .= "'$username',md5('$password'),$age,'$xueli','$xingqu','$from',now())";
	// 	$result = $this->_dao->exec($sql);
	// 	return $result;
	// }

}

?>