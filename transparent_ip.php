<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/31
 * Time: 下午2:23
 */
$html = file_get_contents("https://free-proxy-list.net/");
if(preg_match('/<tbody>(.*?)<\/tbody>/', $html, $matches)){
    $tbody = $matches[1];
}
$fh = fopen('transparent_ip.txt', 'w');
if(preg_match_all('/<tr>(.*?)<\/tr>/', $tbody, $matches)){
    foreach ($matches[1] as $tr){
        preg_match_all('/<td>(.*?)<\/td>/', $tr, $tr_matches);
        if($tr_matches[1][3] == 'transparent'){
            $ip = $tr_matches[1][0];
            $port = $tr_matches[1][1];
            if(check($ip, $port)){
                fwrite($fh, $ip+" "+$port+"\n");
            }
        }
    }
}


function check($ip, $port){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://www.newlife101.com.tw/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_PROXY => "$ip:$port",
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    @curl_setopt($curl, CURLOPT_HEADER  , true);  // we want headers
    @curl_setopt($curl, CURLOPT_NOBODY  , true);  // we don't need body




    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    } else {
        return true;
    }
}