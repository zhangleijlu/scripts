<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/6
 * Time: 上午11:49
 */
$old_mysqli = new mysqli("180.76.174.128","root","asdf", "yascmf_base");
$old_mysqli->query("set names utf8");
for ($i=0;$i<100;$i++){
    $sql = "SELECT * FROM  yascmf_articles_$i WHERE author_name= \"businessweekly\")";
    $ret = $old_mysqli->query($sql);
    while ($ret && $arr = $ret->fetch_assoc()){
        $content = $arr['content'];
        $id = $arr['id'];
        if(strpos($content, "data-original=") !==false){
            $new_content = str_replace("data-original=", "src=", $content);
        }
        $new_content = addTag($new_content);
        $update_sql = "UPDATE yascmf_articles_$i set content = '$new_content' WHERE `id`='$id'";
        $old_mysqli->query($update_sql);
    }
}

function addTag($content){
    $src_list = ['frame', 'iframe'];
    foreach ($src_list as $value){
        $pattern="/<$value(.*?src=[\'|\"].*?)\'|\"].*?)\/>/";
        preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
        foreach ($match as $item) {
            $new_frame = "<$value ".$item[1]. "> </$value>";
            $content = str_replace($item[0], $new_frame, $content);
        }
    }
    return $content;
}