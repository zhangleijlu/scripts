<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/7/10
 * Time: 下午5:29
 */
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$header = [
    "content-type" => "application/x-www-form-urlencoded; charset=UTF-8",
    "accept-encoding" => "gzip, deflate, br",
    "cookie" => "__cfduid=d78faf71788442b2c727642ec49682b451517239306; IMGURUIDJAFO=d4b398f31a749d9d568c53fc5835f7fdd63cc6ad8f14d2cfb104211673b94736; __qca=P0-1764616063-1524143646464; G_ENABLED_IDPS=google; AZUSER=ue1-e54cd897ca584263a5aaab54ccf7e6e2; __gads=ID=108a12f9ea8ec43b:T=1524194563:S=ALNI_MZXMhZhOr1ko8q15Jkrarl88c_MIA; _ga=GA1.2.1062118777.1527862983; fpb-roll=97.10578302141755; fp=2383986330095200; frontpagebeta=1; MWONBOARDING={\"swipeNextPost\":true}; m_section=hot; m_sort=time; introduction=1; _gid=GA1.2.2147168065.1530978158; d7s_uid=jjcbqfh73o64pz; IMGURSESSION=c6db6282276d278a8e996970fee14037; retina=1; IMGURUIDLOTAME=d4b398f31a749d9d568c53fc5835f7fdd63cc6ad8f14d2cfb104211673b94736; UPSERVERID=upload.i-0519091e6ba2b8a5e.production; MWSESSIONDATA={\"sessionCount\":9,\"sessionTime\":1531131892264}; sortable-roll=58.27282958527058; SESSIONDATA={\"sessionCount\":25,\"sessionTime\":1531215984370}",
    "referer" => "https://imgur.com/upload",
    "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36",
    "x-requested-with" => "XMLHttpRequest",
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

curl_setopt($ch, CURLOPT_URL, 'https://imgur.com/upload/checkcaptcha');

$data = array('total_uploads' => 1, 'create_album' => 'true');

curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


$ret = curl_exec($ch);
var_dump($ret);

echo 'Curl error: ' . curl_error($ch);

