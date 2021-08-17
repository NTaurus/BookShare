<?php
namespace app\common\controller;
session_start();
use think\Controller;
use think\helper\hash\Md5;
use think\Request;
use think\Session;

class Common extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        // 验证前后台
        if(isset($_SESSION[Md5('is_login')])){
            if($_SESSION[Md5('is_login')] != '1'){
                // $this->error('没有权限进行访问,请正确登陆后在进行访问','http://bookshare5.com');
                return $this->fetch('http://bookshare5.com/');
                exit;
            }
        }
        else {
            $this->error('没有权限进行访问,请正确登陆后在进行访问','http://bookshare5.com');
            exit;
        }
    }
}
