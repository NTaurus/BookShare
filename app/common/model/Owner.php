<?php
namespace app\common\model;
use think\Db;
use think\db\builder\Mysql;
use app\common as Times;
use think\Model;

class Owner extends Model {
    
    public function addOwner($user_id,$book_id){
        $sql = 'insert into owner(user_id,book_id) values("' . $user_id . '","' . $book_id .'")';
        if(parent::query($sql))
            return true;
        return false;
    }

    public static function updateStatus($owner_id,$status){
        $sql = 'update owner set status = '.$status.' where owner_id = '.$owner_id;
        if(parent::query($sql))
            return true;
        return false;
    }
    
    public static function countShareByDay(){
        $time = Times::get_weeks();
        $data = array();
        for($i=1;$i<8;$i++){
            $t = substr($time[$i],2);
            $sql = 'select count(owner_id) as num from owner where DATE_FORMAT(share_time,"%y-%m-%d") like "%'.$t.'%"';
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
