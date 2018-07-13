<?php

$curl = curl_init();

$data = ['image' => 'https://ibw.bwnet.com.tw/image/pool/2018/03/b139a8a0dd2f8f8021c28c8ced2d7250.png'];

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.imgur.com/3/image",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Client-ID 7a8d9c2cddc8d04",
        "Authorization: Bearer 20a353aa591e9029e92ca7d49515e81fce3677fb"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}