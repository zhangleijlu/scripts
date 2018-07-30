<?php

$curl = curl_init();
$path = '/root/o2GPSJe.jpg';
$data = file_get_contents($path);
$base64 = base64_encode($data);

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.imgur.com/3/image",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_PROXY => '127.0.0.1:3128',
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"image\"\r\n\r\n$base64\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer 6bf95d8dfdf6cef43afb9ea9a8d05dcef6ab79eb",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
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