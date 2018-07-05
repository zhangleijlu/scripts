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
        $content = $this->rmTag($content);
        return $content;
    }

    public function  rmYoutubeFrame($content){
        $src_list = ['frame', 'iframe'];
        $src_lists = [];
        foreach ($src_list as $value){
            $pattern="/<$value.*?src=[\'|\"](.*?)[\'|\"].*?>.*?<\/$value>/";
            preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
            foreach ($match as $item) {
                if (strpos($item[1], 'youtube') !== false) {
                    preg_replace($item[0], "", $content);
                }
            }
        }

        foreach ($src_list as $value){
            $pattern="/<$value.*?src=[\'|\"](.*?)[\'|\"].*?>/";
            preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
            foreach ($match as $item) {
                if (strpos($item[1], 'youtube') !== false) {
                    preg_replace($item[0], "", $content);
                }
            }
        }

        return $content;
    }



    public function  rmTag($content){
        $tag_list = ['script'];
        $img_urls = [];
        foreach ($tag_list as $value){
            $pattern="/<$value.*?>.*?<\/$value>/";
            preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
            foreach ($match as $item) {
                    preg_replace($item[0], "", $content);
            }
        }


        return $content;
    }
}