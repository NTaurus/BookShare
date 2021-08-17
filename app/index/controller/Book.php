<?php
namespace app\index\controller;
use app\common;
use app\common\model\Book as ModelBook;
use app\common\model\Borrow as ModelBorrow;

use think\Db;
use think\Controller;
use think\helper\hash\Md5;
use think\Session;
use app\common\controller\Common as ControllerCommon;

class Book extends ControllerCommon
{
  // 共享图书列表视图
  public function list(){
    $data=null;
    $book = new ModelBook();
    $data = $book->getAllBooks();
    if(empty($_POST)){
        $data = $book->getAllBooks();
      // echo "asd";
    }
    else {
      // echo '12424';
      if($_POST['sort']=='推荐')
        $data = $book->getRecomendBooks();
      else if($_POST['sort']=='默认')
        $data = $book->getAllBooks();
    }
    $this->assign("data", $data);//控制器传数据到视图层
    return $this->fetch('books/list');
  }

  // 推荐列表
  public function recommendList(){
    return $this->fetch('books/list');
  }

  // 图书详情视图
  public function BookDetail(){
    return $this->fetch('books/BookDetail');
  }

  // 分享视图
  public function share(){

    return $this->fetch('books/share');
    
  }
  //调用API获取图书信息 
  public function query(){

    $isbn = $_POST['isbn'];
    $class = $_POST['class'];
    $isbn = str_replace(' ','',$isbn);
    $q = new common();
    // 调用API获取图书信息
    $res = $q->queryISBN($isbn);
    // 添加分类
    $res = array_merge(array('class' => $class), $res);
    // var_dump($res);
    $this->assign("res", $res);//控制器传数据到视图层
    return $this->fetch('books/result');
  }

  // 借书视图
  public function borrow(){
    return $this->fetch('books/borrow');
  }

  public function shareList(){

    return $this->fetch('books/shareList');

  }



  // 借书申请
  public function borrowAction(){
      if(!session_id()) session_start();
      $owner_id = $_GET['owner_id'];
      $date = $_POST['return_date'];
      $user = $_SESSION[Md5('login_account')];
      $book_description = $_POST['book_description'];
      ModelBorrow::borrowBook($owner_id,$user,$date,$book_description);
      echo "<script> alert('申请提交成功！') </script>";

      $book = new ModelBook();
      $data = $book->getAllBooks();
      $this->assign("data", $data);//控制器传数据到视图层
      return $this->fetch('books/list');
  }

  // 图书分享
  public function addAction(){
    if(!session_id()) session_start();
    $data=array();
    $data[0] = $_POST['isbn'];
    $data[1] = $_POST['book_name'];
    $data[2] = $_POST['book_author'];
    $data[3] = $_POST['book_publisher'];
    $data[4] = $_POST['book_class'];
    $data[5] = $_POST['book_description'];
    $data[6] = $_POST['img_url'];
    $book = new ModelBook();
    if($book->isExist($data[0])==false){//书未存在 则需要插入到book表
        $book->addBook($data);
    }
    $book->addOwner($_SESSION[Md5('login_account')],$data[0]);
    echo "<script> alert('分享成功！') </script>";

    $book = new ModelBook();
    $data = $book->getAllBooks();
    $this->assign("data", $data);//控制器传数据到视图层
    return $this->fetch('books/list');
  }
}