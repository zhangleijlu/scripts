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
        $src_list = ['frame', 'iframe'];
        $img_urls = [];
        foreach ($src_list as $value){
            $pattern="/<$value.*?src=[\'|\"](.*?)[\'|\"].*?>.*?<\/$value>/";
            preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
            if(isset($match[1])&&!empty($match[1])){
                $src_lists = $match[1];
            }
            foreach ($src_lists as $item) {
                if (strpos($item[1], 'youtube') !== false) {
                    preg_replace($item[0], "", $content);
                }
            }
        }


        return $content;
    }
}