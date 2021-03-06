<?php
header("Content-type:text/html;charset=utf-8");

$refre = $_SERVER['HTTP_REFERER'];
//&& stripos('shopyz', $refer) === false
if(stripos('shopyz',$refer) === false ){
    // header("Location:http://www.ingdu.cn");
}
// $result = http_request('http://www.shopyz.cn/index.php/home/Public/zhima_test', ['zm_score'=>100]);
// print_r($result);
$userAuthUrl = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?";
$data['app_id']       =  '2017122201059023';
$data['scope']        =  'auth_zhima';
$data['redirect_uri'] =  'http://zhima.ingdu.cn/auth.php';
$data['state']        =  $_GET['uid']; //uid赋在state里面
$url = $userAuthUrl.http_build_query($data);
if(is_mobile()){
    header("Location:".'alipays://platformapi/startapp?appId=20000067&url='.urlencode($url));
}else{
    header("Location:".$url);
}

function is_mobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 * CURL
 * @param $url
 * @param array $data
 * @return bool|mixed
 */
function http_request($url,$data=array()){
    $header = ["Content-Type: application/x-www-form-urlencoded; charset=utf-8"];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $information = curl_getinfo($curl);
    curl_close($curl);
//    print_r($information);
    //返回结果
    if($information['http_code'] == 200){
        $output = json_decode($output,true);
        return $output;
    } else {
        $error = curl_errno($curl);
        echo "curl出错，错误码:$error"."<br>";
        echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a><br>";
        return false;
    }
}
    