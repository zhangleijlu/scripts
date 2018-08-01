<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/6
 * Time: 上午11:49
 */
error_reporting(E_ALL);

$old_mysqli = new mysqli("180.76.174.128","root","asdf", "yascmf_base");
$old_mysqli->query("set names utf8");
for ($i=0;$i<100;$i++){
    $sql = "SELECT * FROM  yascmf_articles_$i ";
    $ret = $old_mysqli->query($sql);
    while ($ret && $arr = $ret->fetch_assoc()){ echo 123;
        $slug = $arr['slug'];
        $cid = $arr['cid'];
        $cat_slug_list = ['2'=>'health', '3'=>'business'];
        if(isset($cat_slug_list[$cid])){
            $cat_slug = $cat_slug_list[$cid];

        }else{
            continue;
        }
        $url = "http://www.zawenblog.com/$cat_slug/$slug.html";
        echo $url;
        url_post($url);
    }
}

function addTag($content){
    $src_list = ['frame', 'iframe', 'script'];
    foreach ($src_list as $value){
        $pattern="/<$value.*?src=[\'|\"](.*?)[\'|\"].*?\/>/";
        preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
        foreach ($match as $item) {
            $new_frame = str_replace("/>", ">", $item[0])." "."</$value>";
            $content = str_replace($item[0], $new_frame, $content);
        }
    }
    return $content;
}

function url_post($url){
    $api = 'http://data.zz.baidu.com/urls?appid=1604937856523122&token=1Hb3zOUfPH2Uh9bb&type=batch';
    $ch = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    echo $url;
    $result = curl_exec($ch);
    echo $result;
}

function xiongzhang_url_post($url){
    $api = 'http://data.zz.baidu.com/urls?appid=1604937856523122&token=1Hb3zOUfPH2Uh9bb&type=batch';
    $ch = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    echo $url;
    $result = curl_exec($ch);
    echo $result;
}