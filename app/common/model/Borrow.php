<?php
namespace app\common\model;
use think\Db;
use think\db\builder\Mysql;
use think\helper\hash\Md5;
use think\Model;
use app\common as Times;

class Borrow extends Model {
    
    public static function borrowBook($owner_id,$user,$date,$decription){
        $sql = 'insert into borrow(owner_id,applier_id,status,end,applier_remark) values('.$owner_id.','.$user .',"申请中","'.$date .'","'.$decription .'")';
        //echo $sql;
        if(parent::query($sql))
            return true;
        return false;
    }

    public static  function getBorrowStatus($owner_id){
        $sql = 'select status from borrow where owner_id = '.$owner_id;
        $res = parent::query($sql);
        if($res)return $res[0];
        return false;
    }

    public static function lendReply($borrow_id,$remark, $status){
        $sql = 'update  borrow set status = "'.$status.'", owner_remark = "'.$remark .'" where borrow_id = '.$borrow_id;
        echo $sql;
        if(parent::query($sql))
            return true;
        return false;
    }

    public static function getBorrowInfo($user){
        if(!session_id()) session_start();
        $applier_id = $_SESSION[Md5('login_account')];
        $sql = 'select *from borrow_view where '.$user. '= '.$applier_id;
        $res = parent::query($sql);
        return $res;
    }

    public static function getAllBorrowInfo($user){
        $sql = 'select *from borrow_view, user where 申请人 = user.user_id';
        $res = parent::query($sql);
        return $res;
    }

    public static function BorrowInfo(){
        $sql = 'select *from borrow_view';
        $res = parent::query($sql);
        return $res;
    }

    public static function getOwnerById($borrow_id){
        $sql = 'select owner_id from borrow where  borrow_id = '.$borrow_id;
        $res = parent::query($sql);
        if($res)return $res[0];
        return false;
    }

    public static function getBorrowInfoById($borrow_id){
        $sql = 'select *from borrow_view where 借书号 = '.$borrow_id;
        $res = parent::query($sql);
        if($res)return $res[0];
        return false;
    }


    public static function getHistory($user){
        if(!session_id()) session_start();
        $applier_id = $_SESSION[Md5('login_account')];
        $sql = 'select *from borrow_view where '.$user. '= '.$applier_id.' and (状态 = "已借未还" or 状态 = "已归还")';
        $res = parent::query($sql);
        return $res;
    }

    public static function getLendInfo(){ 
        if(!session_id()) session_start();
        $sharer_id = $_SESSION[Md5('login_account')];
        $sql = 'select *from borrow_view where 分享人 = '.$sharer_id;
        $res = parent::query($sql);
        return $res;
    }


    //取消借书申请
    public static function borrowCancel($borrow_id){ 
        $sql = 'delete from borrow where borrow_id = '.$borrow_id;
        if(parent::query($sql))return true;
        return false;
    }

    public static function getReturnInfo(){ 
        if(!session_id()) session_start();
        $applier_id = $_SESSION[Md5('login_account')];
        $sql = 'SELECT * FROM borrow_view WHERE TO_DAYS(截止时间) <= TO_DAYS(NOW())+1 and 状态 = "已借未还" and 借书人 = '.$applier_id;
        $res = parent::query($sql);
        return $res;
    }

    public static function returnCommit($borrow_id, $user){
        $role = $user == '借书人'?'applier_confirm':'sharer_confirm';
        $sql = 'update borrow set ' .$role . ' = 1  where borrow_id = '.$borrow_id;
        if(parent::query($sql))return true;
        return false;
    }

    public static function getReturnConfirmInfo(){ //获取“确认还书”信息
        if(!session_id()) session_start();
        $sharer_id = $_SESSION[Md5('login_account')];
        $sql = 'select *from borrow_view where 申请人确认 = 1  and 分享人 = ' .$sharer_id;
        $res = parent::query($sql);
        return $res;
    }

    public static function returnBook($borrow_id){ //还书
        $sql = 'update borrow set status = "已归还"  where borrow_id = '.$borrow_id;
        if(parent::query($sql))return true;
        return false;
    }


    public static function countBorrowByDay(){
        $time = Times::get_weeks();
        $data = array();
        // $time=$time['time'];
        // var_dump($time);
        for($i=1;$i<8;$i++){
            $t = substr($time[$i],2);
            // var_dump($t);
            $sql = 'select count(borrow_id) as num from borrow where DATE_FORMAT(begin,"%y-%m-%d") like "%'.$t.'%"';
            // echo $sql;
            $res = parent::query($sql);
            // var_dump($res);
            $x = $res[0];
            // var_dump($x);
            $data[$i]=$x['num'];
        }
        // return $res;
        // print_r($data);
        return $data;
    }

}
?>
