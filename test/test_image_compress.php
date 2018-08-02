<?php
/**
 * Created by PhpStorm.
 * User: zhanglei02
 * Date: 2018/8/1
 * Time: 下午6:53
 */
require __DIR__.'/../libs/ImgCompressor.class.php';

compressImg(realpath(__DIR__.'/../flower.jpg'));
function compressImg($img){
    $setting = array(
        'directory' => realpath(__DIR__.'/../image/'), // directory file compressed output
        'file_type' => array( // file format allowed
            'image/jpeg',
            'image/png',
            'image/gif'
        )
    );
    $ImgCompressor = new ImgCompressor($setting);
    $result = $ImgCompressor->run($img, 'jpg', 5);

    return $result['data']['compressed']['image'];
}