<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace app;

// 应用公共文件
class common{

    

    public function captcha(){
        session_start();
        $image = imagecreatetruecolor(100,30);
        $bgcolor=imagecolorallocate($image,255,255,255);

        imagefill($image, 0, 0, $bgcolor);

        $captch_code='';

        for ($i=0; $i < 4; $i++) { 

            $fontsize=6;

            $fontcolor=imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));

            //产生随机数字0-9

            $fontcontent = rand(0,9);

            $captch_code.= $fontcontent;

        //数字的位置，0，0是左上角。不能重合显示不完全

            $x=($i*100/4)+rand(5,10);

            $y=rand(5,10);

            imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);

        }
        $_SESSION['authcode'] = $captch_code;
        for ($i=0; $i < 3; $i++) { 

            $linecolor = imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));

            imageline($image, rand(1,99), rand(1,29),rand(1,99), rand(1,29) ,$linecolor);

        }
        header('content-type:image/png');
        imagepng($image);
        //销毁

        imagedestroy($image);
    }

    public function queryISBN($isbn){
        // 云市场分配的密钥Id
        $secretId = 'AKID9gkejZlqp6sb7lbz4i8ti8bs86zCjdozn5dg';
        // 云市场分配的密钥Key
        $secretKey = '60fmNkrE4bHwhKq1arU87206cUea4D61gnGd8q2e';
        $source = 'market';

        // 签名
        $datetime = gmdate('D, d M Y H:i:s T');
        $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
        $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
        $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);

        // 请求方法
        $method = 'GET';
        // 请求头
        $headers = array(
            'X-Source' => $source,
            'X-Date' => $datetime,
            'Authorization' => $auth,
        );
        // 查询参数
        $queryParams = array (
            'isbn' => $isbn,
        );
        // body参数（POST方法下）
        $bodyParams = array (
        );
        // url参数拼接
        $url = 'http://service-osj3eufj-1255468759.ap-shanghai.apigateway.myqcloud.com/release/isbn';
        if (count($queryParams) > 0) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
            return $k . ': ' . $v;
        }, array_values($headers), array_keys($headers)));
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
        }

        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch);
        } else {
            $res=json_decode($data,true);
            $res=$res['showapi_res_body'];
            $res=$res['data'];
            return $res;
        }
        curl_close($ch);
    }

    public static function  get_weeks($time = '', $format='Y-m-d'){

        $time = $time != '' ? $time : time();
      
        //组合数据
      
        $date = [];
      
        for ($i=1; $i<=7; $i++){
      
          $date[$i] = date($format ,strtotime( '+' . $i-7 .' days', $time));
      
        }
      
        return $date;
      
      }
}