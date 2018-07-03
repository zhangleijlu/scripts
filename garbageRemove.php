<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/3
 * Time: 下午4:39
 */
class garbageRemove {
    public function  remove($content){
        $garbage = ["脸书粉丝页", "部落格"];
        foreach ($garbage as $k=>$v){
                $content = str_replace("", $v, $content);

        }
        $content = $this->rmYoutubeFrame($content);
        return $content;
    }

    public function  rmYoutubeFrame($content){
        foreach (["frame", "iframe"] as $value){
            $selector = $value;
            $frame = select_elements($selector, $content);
            if(strpos($frame, "youtube")){
                $content = str_replace($frame, "", $content);
            }
        }

        return $content;
    }
}