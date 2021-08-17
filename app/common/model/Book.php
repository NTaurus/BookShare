<?php
namespace app\common\model;
use think\Db;
use think\db\builder\Mysql;
use think\Model;

class Book extends Model {
    
    // 判断book表里是否已经存在此书
    public static function isExist($isbn){
        $book = self::where('book_id','=',$isbn)->find();
        if($book){
            return true;
        }
        return false;
        // return parent::query("select book_id from tb_book where boo_id=".$isbn);
    }


    // 根据所属号，获取图书信息以及分享人的信息
    public static function getOneBook($owner_id){        
        $sql = "select *from owner_view where 所属号=".$owner_id;
        $data=parent::query($sql);
        return $data;
    }

    // 根据ISBN获取图书信息
    public static function getBookByIsbn($isbn){        
        $sql = "select *from book where book_id=".$isbn;
        $data = parent::query($sql);
        $res = $data[0];
        return $res;
    }

    // 获取所有可借的图书
    public static function getAllBooks(){
        $sql = "select 所属号,书名,图片,分享者 from owner_view where 图书状态 =0 ";
        $data=parent::query($sql);
        return $data;
    }
    

    // 获取所有可借的图书
    public static function getRecomendBooks(){
        if(!session_id()) session_start();
        $user_id = $_SESSION[Md5('login_account')];
        $sql = "SELECT class FROM book,borrow,OWNER WHERE book.book_id = owner.book_id AND owner.owner_id = borrow.owner_id AND applier_id = {$user_id} GROUP BY class LIMIT 1";
        $data = parent::query($sql);
        $data = $data[0];
        $class = $data['class'];
        // $sql = "select class,count(borrow_id) as num from book,borow where applier_id = {$user_id} group by class ";
        $sql = "SELECT 所属号,书名,图片,分享者 FROM owner_view, OWNER WHERE 图书状态 =0 AND 类别 = '{$class}' AND owner.owner_id = owner_view.所属号 ORDER BY share_time DESC";
        $data=parent::query($sql);
        return $data;
    }

    // 获取我上传的图书
    public static function getMyBooks(){
        if(!session_id()) session_start();
        $sharer_id = $_SESSION[Md5('login_account')];
        $sql = "select 所属号,书名,图片,图书状态 from owner_view,owner where owner.owner_id = owner_view.所属号 and user_id = ".$sharer_id;
        $data=parent::query($sql);
        return $data;
    }

    // 添加新的书，往book表里插入图书信息
    public static function addBook($book)
    {

        // $book = self::where(['book_id'=>$book[0],''])->find();

        $sql='insert into book(book_id,book_name,author,publisher,class,description,url) values("'
        .$book[0].'","'.$book[1].'","'.$book[2].'","'.$book[3].'","'.$book[4].'","'.$book[5].'","'.$book[6].'")';
        // echo $sql;
        if(parent::query($sql))
            return true;
        return false;
        // $db->query($sql);
    }

    // 分享图书，添加所属关系
    public static function addOwner($user_id,$book_id){
        $sql = 'insert into owner(user_id,book_id) values("' . $user_id . '","' . $book_id .'")';
        if(parent::query($sql))
            return true;
        return false;
    }
    
    // 获取借阅次数最多的类别的图书
    public static function getTopBook(){
        $sql = 'SELECT DISTINCT(class), COUNT(借书号) AS num FROM book, borrow_view WHERE   borrow_view.书名 = book.book_id AND borrow_view.状态!="不借了" GROUP BY(class) ORDER BY num DESC';
        $data=parent::query($sql);
        $res = array();
        for($i=0;$i<count($data);$i++){
            $t = $data[$i];
            // var_dump($t);
            // echo '<br>';
            if($t['class']=='计算机'){
                $res = array_merge(array('计算机' => $t['num']), $res);
            }
            else if($t['class']=='小说'){
                $res = array_merge(array('小说' => $t['num']), $res);
            }
            else if($t['class']=='青春文学'){
                $res = array_merge(array('青春文学' => $t['num']), $res);
            }
            else if($t['class']=='人文社科'){
                $res = array_merge(array('人文社科' => $t['num']), $res);
            }
            else if($t['class']=='科技'){
                $res = array_merge(array('科技' => $t['num']), $res);
            }
            else if($t['class']=='自然科学'){
                $res = array_merge(array('自然科学' => $t['num']), $res);
            }
            else if($t['class']=='教育'){
                $res = array_merge(array('教育' => $t['num']), $res);
            }
            // $res += $data[$i];
        }
        if(!isset($res['计算机'])){
            $res = array_merge(array('计算机' => 0), $res);
        }
        if(!isset($res['小说'])){
            $res = array_merge(array('小说' => 0), $res);
        }
         if(!isset($res['青春文学'])){
            $res = array_merge(array('青春文学' => 0), $res);
        }
         if(!isset($res['人文社科'])){
            $res = array_merge(array('人文社科' => 0), $res);
        }
         if(!isset($res['科技'])){
            $res = array_merge(array('科技' => 0), $res);
        }
         if(!isset($res['自然科学'])){
            $res = array_merge(array('自然科学' => 0), $res);
        }
         if(!isset($res['教育'])){
            $res = array_merge(array('教育' => 0), $res);
        }
        // var_dump($res);
        return $res;
    }

    public static  function getPopularBook()
    {
        $sql = 'SELECT  book_name, COUNT(借书号) AS num FROM  borrow_view,book WHERE borrow_view.书名 = book_id GROUP BY (书名) ORDER BY COUNT(借书号) DESC LIMIT 10';
        $data=parent::query($sql);
        return $data;
    }

    // public static function countBookByClass(){
    //     $sql = '';
    //     $data=parent::query($sql);
    //     return $data;
    // }

}

?>
