<?php
class sourcePost{
    private $transparent_ip = [];
    private $seret_list_keys = ['20a353aa591e9029e92ca7d49515e81fce3677fb'=>1, '897c052631ad9697f65de97656c3e9e39d17587b'=>1, 'c03390c710b2b66e6a21e96b8374b8a1651d7e17'=>1, '9f639e26788b3e29fbac35dd65919675acbe790a'=>1,
                                'f9de32b21e416889826b44fe8751dc1f95441a1f'=>1, '0b773d3980a1761e823ad2e6365d9a04ff4efaf8'=>1,'486177d38f67567baaa7dc4a03157f6b5668fc10'=>1, 'd2d66be94417b4b57444214d50e80f7ea2f52a19'=>1,
                                '57c2fd641a26cd1d798221d3694ff5d0a8d6d1bf'=>1, '6bf95d8dfdf6cef43afb9ea9a8d05dcef6ab79eb'=>1,
                                ];
    public function __construct()
    {
        $transparent_ip_file = realpath(__DIR__."/../transparent_ip.txt");
        $fh = fopen($transparent_ip_file, 'r');
        $proxies = [];
        while ($line = trim(fgets($fh))){
            $arr = explode(' ', $line);
        }
        $proxies[] = $arr;
        $this->transparent_ip = $proxies;
    }

    public function image_post($path, $seret){
        $curl = curl_init();
        $data = file_get_contents($path);
        $base64 = base64_encode($data);
        $proxy_key = array_rand($this->transparent_ip);
        $proxy = $this->transparent_ip[$proxy_key];
        $ip = $proxy[0];
        $port = $proxy[1];
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.imgur.com/3/image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PROXY => "$ip:$port",
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"image\"\r\n\r\n$base64\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $seret",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return false;
        } else {
            echo $response;
            return $response;
        }
    }

    private function getProxy($type="transparent"){


    }

    public function img_upload($old_url = '', $title){
        $path_parts = pathinfo($old_url);
        echo $old_url;
        $ext = $path_parts['extension'];
        $img = realpath(__DIR__ . "/../") .'/flower.'.$ext;
        unlink($img);
        file_put_contents($img, file_get_contents($old_url));
        if(!file_exists($img)){
            return false;
        }
        $seret_list_keys = ['20a353aa591e9029e92ca7d49515e81fce3677fb'=>1, '897c052631ad9697f65de97656c3e9e39d17587b'=>1, 'c03390c710b2b66e6a21e96b8374b8a1651d7e17'=>1, '9f639e26788b3e29fbac35dd65919675acbe790a'=>1,
            'f9de32b21e416889826b44fe8751dc1f95441a1f'=>1, '0b773d3980a1761e823ad2e6365d9a04ff4efaf8'=>1,'486177d38f67567baaa7dc4a03157f6b5668fc10'=>1, 'd2d66be94417b4b57444214d50e80f7ea2f52a19'=>1,
            '57c2fd641a26cd1d798221d3694ff5d0a8d6d1bf'=>1, '6bf95d8dfdf6cef43afb9ea9a8d05dcef6ab79eb'=>1,
        ];
        $seret_rand = rand(0, 7);
        $seret = array_keys($seret_list_keys)[$seret_rand];
        $seret_list_keys[$seret] +=  1;
        if(!filesize($img)){
            return $old_url;
        }
        //  $shell = "curl --proxy \"http://127.0.0.1:3128\"  --compressed  -fsSL --stderr - -F 'title=${title}' -F 'image=@\"$img\"'  -H \"Authorization: Bearer $seret\" https://api.imgur.com/3/image";
        $new_url = '';

        $i =0;
        while(!$new_url && $i<4){
            $ret =  $this->image_post($img, $seret);
            $ret = stripslashes($ret);
            if(!$ret){
                exit();
            }
            $ret_arr = json_decode($ret);
            var_dump($ret_arr);
            $new_url = $ret_arr->data->link; echo  $new_url;
            $i++;
        }
        if($i==4){
            var_dump($seret_list_keys);
            exit();
        }
        sleep(1);
        return $new_url;
    }




}

