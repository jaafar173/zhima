<?php
header("Content-type:text/html;charset=utf-8");
$userAuthUrl = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?";
$data['app_id']       =  '2017122201059023';
$data['scope']        =  'auth_zhima';
$data['redirect_uri'] =  'http://zhima.ingdu.cn/auth.php';
$data['state']        =  '345';
$url = $userAuthUrl.http_build_query($data);
header("Location:".'alipays://platformapi/startapp?appId=20000067&url='.urlencode($url));
    