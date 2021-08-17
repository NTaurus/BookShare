<?php
// 声明命名空间

namespace app\admin\controller;

// 引入系统控制器

use think\Controller;
use think\helper\hash\Md5;
use think\Session;
use app\common;
use app\admin\controller\Common as ControllerCommon;
use app\common\model\Borrow as ModelBorrow;
// 声明控制器

class Analysis extends ControllerCommon{
 
    public function borrowAnalysis(){

        return $this->fetch('Analysis/borrowAnalysis');
    }


    public function countBorrowByDay()
    {
        ModelBorrow::countBorrowByDay();
        return $this->fetch('Analysis/countBorrowByDay');

    }

    public function bookAnalysis()
    {
        ModelBorrow::countBorrowByDay();
        return $this->fetch('Analysis/bookAnalysis');

    }
    
}