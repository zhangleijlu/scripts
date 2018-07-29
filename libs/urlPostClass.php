<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/28
 * Time: 下午10:30
 */
class urlPostClass{
    private $redis;
    const BATCH_API = "http://data.zz.baidu.com/urls?appid=1604937856523122&token=1Hb3zOUfPH2Uh9bb&type=batch";
    const REALTIME_API = "http://data.zz.baidu.com/urls?appid=1604937856523122&token=1Hb3zOUfPH2Uh9bb&type=realtime";

    public function __construct(){
        $this->redis = new Redis();
        $this->redis->connect("180.76.174.128");
    }

    public function main($url){
        $zhanzhang_api = " http://data.zz.baidu.com/urls?site=www.zawenblog.com&token=Ny7YSKqDi9LbLbeR";
        $this->post($url, $zhanzhang_api);

        $realtime_key = "DAY_REALTIME";
        $realtime_string = $this->redis->get($realtime_key);
        if(!$realtime_string){
            $realtime_num = 0;
        }else{
            $realtime_arr = explode('-', $realtime_string);
            if($realtime_arr[0] == date('Ymd')){
                $realtime_num = $realtime_arr[1];
            }else{
                $realtime_num = 0;
            }
        }

        if($realtime_num<10){
            $this->post($url, self::REALTIME_API);
        }else{
           $this->post($url, self::BATCH_API);
        }
        $this->redis->setex($realtime_key,24*3600, date('Ymd')."-".($realtime_num+1));
    }

    public function  post($url,$api){
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
}