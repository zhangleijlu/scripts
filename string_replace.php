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
    while ($arr = $ret->fetch_assoc()){
        $content = $arr['content'];
        $id = $arr['id'];
        if(strpos($content, "data-original=") !==false){
            $new_content = str_replace("data-original=", "", $content);
        }
        $update_sql = "UPDATE yascmf_articles_$i set content = '$new_content' WHERE `id`='$id'";
        $old_mysqli->query($update_sql);
    }
}