<?php
// 声明命名空间

namespace app\index\controller;

// 引入系统控制器

use think\Url;
use think\Controller;
use think\helper\hash\Md5;
use app\common\model\User;
use think\Session;
use think\Cookie;
use app\common\model\Book as ModelBook;
// use app\common\controller\Common as ControllerCommon;

// 声明控制器

class Index extends Controller{
 
    // 登录页面

    public function index(){
        // 加载登录页面
        return view('index');

    }

    public function logout(){
        // 清除session（当前作用域）
        Session::clear();
        Session::destroy();
        echo "<script> alert('退出成功！正在跳转...') </script>";
        return $this->fetch('index');
    }

    public function register(){
        return $this->fetch('user/register');
    }

    // 处理登录的提交页面

    public function check(){

        if (isset($_REQUEST['authcode'])) {
            
            // session_start();
            Session::start();
            if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {
    
                        // 接收数据
                $username = $_POST['username'];
                $password = $_POST['password'];
                // $remeber=$_POST['remeber'];
                // 判断是否登录成功
                $user=User::getOneUser($username);
                if(!isset($user['password'])){
                    echo "<script> alert('账号不存在,请重新输入！') </script>";
                    return $this->fetch('index/index');
                }
                if ($user['password'] == Md5($password)) {
                    echo "<script> alert('登录成功！正在跳转...') </script>";
                    $is='1';
                    Session::set(Md5('login_account'),$username);
                    Session::set(Md5('login_pw'),$password);
                    Session::set(Md5('is_login'),$is);
                    Session::set(Md5('user_name'),$user['user_name']);
                    $book = new ModelBook();
                    $data = $book->getAllBooks();
                    $this->assign("data", $data);//控制器传数据到视图层
                    return $this->fetch('books/list');
                }
                else{
                    // 失败之后跳转
                    echo "<script> alert('账号或密码错误，请重新输入！') </script>";
                    return $this->fetch('index');
                }
            }
            else{
                echo "<script> alert('验证码错误！') </script>";
                return $this->fetch('index');
            }    
        }

    }
}