<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/4/26
 * Time: 下午4:01
 */
require "./config.php";
require "./selector.inc";
require "./garbageRemove.php";

$hostname = gethostname();
$old_mysqli = new mysqli("67.218.158.33","root","root", "content_ori");
if(strpos($hostname,"instance") !==false){
    $new_mysqli = new mysqli("180.76.174.128","root","root", "yascmf_base");
}else{
    $new_mysqli = new mysqli("180.76.174.128","root","asdf", "yascmf_base");
}
$redis = new Redis();
if(strpos($hostname,"instance") !==false){
    $redis->connect("180.76.174.128");
}else{
//    $redis->connect("180.76.174.128");
}

$old_mysqli->query("set names utf8");
$new_mysqli->query("set names utf8");
$sql = "select * from `content_ori_201804_newlife101` WHERE  `transfer_status` = 0";

$ret = $old_mysqli->query($sql);
  $config = $site_config;
while ($arr = $ret->fetch_assoc()){
    $ori_content = $arr['content'];
    $ori_title = $arr['title'];
    $ori_site =  $arr['site'];
echo $arr['title'];
    $cn_title = transfer_cn($ori_title);
    $cn_content = transfer_cn($ori_content);
    $no_img_content = img_transfer($cn_content, $ori_site);
    $three_step_content = inner_url_transfer($no_img_content, $config[$ori_site]['cat_slug']);
    $four_step_content = outer_url_transfer($three_step_content);
    $final_data = [];
    $final_data['title'] = $cn_title;
    $garbageRemove = new garbageRemove();
    $final_data['content'] = $garbageRemove->remove($four_step_content);
    $final_data['slug'] =  $arr['slug'];
    $final_data['sub_cid'] =  $arr['slug'];
    
    $final_data['cat'] =  $config[$ori_site]['cat'];

    $final_data['uid'] = $config[$ori_site]['uid'];
    $final_data['author_name'] = $config[$ori_site]['author_name'];
    $final_data['cat_slug'] = $config[$ori_site]['cat_slug'];
var_dump($final_data);
    insert_text($final_data);
 change_status( $arr['slug']);
//   die();
}

function img_transfer($cn_content, $ori_site){
   // echo $cn_content;
//echo $cn_content;
    $src_list = ['src', 'data-original'];
    $img_urls = [];
    while ( empty($img_urls) && list($key,$value) = each($src_list)) {
        $pattern="/<img.*?$value=[\'|\"](.*?)[\'|\"].*?[\/]>/";
        preg_match_all($pattern, $cn_content,$match, PREG_PATTERN_ORDER);
        if(isset($match[1])&&!empty($match[1])){
                $img_urls = $match[1];
        }
    }
   
    var_dump($img_urls);
    foreach ($img_urls as $img_url){
    if(strpos($img_url,'http')===false){
    	$img_url = $ori_site.$img_url;
    }

        $new_url = img_upload($img_url);
	 //$new_url = img_upload($img_url);
if($new_url){
        $cn_content = str_replace($img_url, $new_url, $cn_content);
}
    }

    return $cn_content;
}

function transfer_cn($text){
    file_put_contents("./tmp1.txt", $text);
    // shell_exec("echo 123");
    exec(" opencc -i ./tmp1.txt  -o ./tmp2.txt -c zht2zhs.ini", $output );
    //var_dump($output);
    $cn_text = file_get_contents("./tmp2.txt");
    return $cn_text;
}


function inner_url_transfer($content, $cat_slug){
    $pattern="/<a.*?href=[\'|\"](.*?lm_news_view.php\?.*?cid=(\d+).*?)[\'|\"].*?\?>/";
    preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
    foreach ($match as $k=>$v){
        $ori_art_cid = $v[2];
        $sql = "select * from content_ori_201804_newlife101 WHERE  `ori_art_cid`=$ori_art_cid";
        global $old_mysqli;
        $ret = $old_mysqli->query($sql);
        $slug=$ret->fetch_assoc()['id'];
        $new = "http://www.zawenblog.com/$cat_slug/$slug";
        $content = str_replace($new, $v[1], $content);
    }
    return $content;
}

function outer_url_transfer($content){
    $pattern="/<a.*?href=[\'|\"](.*?)[\'|\"].*?>(.*?)<\/a>/";
    preg_match_all($pattern, $content,$match, PREG_SET_ORDER);
    foreach ($match as $k=>$v){
        $outer_url = $v[0];
        if(strpos($v[0],"zawenblog") === false){
            $content = str_replace($v[0], '', $content);
        }

    }
    return $content;
}



function img_upload($old_url = ''){
    $path_parts = pathinfo($old_url);
echo $old_url;
    $ext = $path_parts['extension'];
    $img = __DIR__."/".'flower.'.$ext;
    unlink($img);
    file_put_contents($img, file_get_contents($old_url));
if(!file_exists($img)){
return false;
}
    $title = "test";
    $shell = "curl --compressed  -fsSL --stderr - -F 'title=${title}' -F 'image=@\"$img\"'  -H \"Authorization: Bearer 20a353aa591e9029e92ca7d49515e81fce3677fb\" https://api.imgur.com/3/image";
    echo $shell;
$new_url = "";
$i =0;
while(!$new_url && $i<4){
    $ret = shell_exec($shell);
    $ret = stripslashes($ret);
    $ret_arr = json_decode($ret);

    $new_url = $ret_arr->data->link;
$i++;
}
  //  sleep(1);
    return $new_url;
}

function insert_text($data){
    global $new_mysqli;
    $article_table = "yascmf_articles_".($data['slug']%100);
    $art_cat_table = "yascmf_articles_cat_".($data['cat']%10);
    $art_user_table = "yascmf_articles_user_".($data['uid']%10);
    $index_table = 'yascmf_articles_index_1';
    $title = $data['title'];
    $slug = $data['slug'];
    $cat = $data['cat'];
    $content = $data['content'];
    $uid = $data['uid'];
    $author_name = $data['author_name'];
    $cat_slug = $data['cat_slug'];
    $content = iconv(mb_detect_encoding($content), "UTF-8", $content);
    //var_dump(strip_tags($content));die();
    $description = mb_substr(strip_tags($content), 0 ,200); var_dump($article_table,$art_cat_table,$art_user_table,$index_table);


    $sql = "INSERT INTO `$article_table`(`title`,`slug`,`cid`,`description`,`content`,`uid`,`author_name`) ".
        " values(?, ?, ?, ?, ?, ?, ?)";
    $stmt = $new_mysqli->prepare($sql);
    $stmt->bind_param('sddssds', $title, $slug, $cat, $description,$content,$uid,$author_name);
    $stmt->execute();

    $sql = "INSERT INTO `$art_cat_table`(`uid`, `cid`, `slug`, `title`, `description`)"
    ." values(?, ?, ?, ?, ?)";
    $stmt = $new_mysqli->prepare($sql); var_dump($stmt);
    $stmt->bind_param('dddss', $uid, $cat, $slug, $title, $description);
    $stmt->execute();
    $sql = "INSERT INTO `$art_user_table`(`uid`, `cid`, `slug`, `title`,`description`)"
        ." values(?, ?, ?, ?, ?)";
    $stmt = $new_mysqli->prepare($sql);
    $stmt->bind_param('dddss', $uid, $cat, $slug, $title, $description);
    $stmt->execute();

    $sql = "INSERT INTO `$index_table`(`uid`, `cid`, `slug`, `title`,`description`)"
        ." values(?, ?, ?, ?, ?)";
    $stmt = $new_mysqli->prepare($sql);
    $stmt->bind_param('dddss', $uid, $cat, $slug, $title, $description);
    $stmt->execute();
  //  global $redis;
//    $redis->set("YASCMF_BASE_INDEZ_TABLE_NAME",$index_table);
    $url = "http://www.zawenblog.com/$cat_slug/$slug.html";
    echo $url;
    url_post($url);
}

function url_post($url){
    $api = 'http://data.zz.baidu.com/urls?site=www.zawenblog.com&token=Ny7YSKqDi9LbLbeR';
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

function change_status($slug){
    global  $old_mysqli;
    $sql = "update `content_ori_201804_newlife101` set  `transfer_status` = 2 WHERE `slug`=$slug";
    $old_mysqli->query($sql);
}

/**
 * curl --compressed  -fsSL --stderr - -F "title=${title}" -F "image=@\"/root/base/a.png\""  -H "Authorization: Bearer 20a353aa591e9029e92ca7d49515e81fce3677fb" https://api.imgur.com/3/image
 */
//img_upload();


?>
