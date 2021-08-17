<?php

namespace app\admin\model;
use think\Model;
class Admin extends Model{
		//通过ID获取用户信息
	
    public  static function  getOneadmin($id){
        $admin = self::where('admin_id','=',$id)->find();
        if($admin){
            return $admin;
        }
        return "false";
	}
	
	public static function Updateadmin($id, $admin_name, $admin_pass,$sex,$phone, $introduction){
		$sql = "update admin set admin_name='$admin_name'";
		if(!empty($admin_pass)){
			$sql .= ", password=md5('$admin_pass')";
		}
		$sql .= ", sex='$sex'";
		$sql .= ", phone='$phone'";
		$sql .= ", introduction='$introduction'";
		$sql .= " where admin_id = $id";
		$res = parent::query($sql);
		return $res;
	}

}

?>