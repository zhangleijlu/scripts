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
    $sql = " update yascmf_articles_$i set content=REPLACE(content, \"data-original=\", \"src=\")";
    $old_mysqli->query($sql);
}