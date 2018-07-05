<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/5
 * Time: 下午4:10
 */
$old_mysqli = new mysqli("67.218.158.33","root","root", "content_ori");
$sql = "select ori_art_id,min(insert_time),site as min_insert_time from content_ori_201804_newlife101 group by site,ori_art_id having count(*) >1";
$ret = $old_mysqli->query($sql);
while ($arr = $ret->fetch_assoc()){
    $ori_art_id = $arr['ori_art_id'];
    $min_insert_time = $arr['min_insert_time'];
    $site = $arr['site'];
    $del_sql ="DELETE FROM content_ori_201804_newlife101 WHERE `ori_art_id` = '$ori_art_id' AND `insert_time`='$min_insert_time' AND `site`='$site'";
    $old_mysqli->query($del_sql);
}