<?php

namespace Home\Controller;

use Think\Controller;

class UploadController extends Controller {
    //上传文件
    function upload() {
	    if (!isset($_FILES["file"])) {
            response(ERRNO_FAIL, "file not found");
            exit();
        }
        $file = $_FILES["file"];
        $name = random_str(30). '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($file["error"] > 0) {
            response(ERRNO_FAIL, "file error");
            exit();
        }
        move_uploaded_file($file["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $name);
        $url = C('DOMAIN') . "CloudPlayerServer/upload" . "/" . $name;

        $mp3 = new \getID3();
        $fileName=$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $name;
        $info =  $mp3->analyze($fileName);
        $time=$info['playtime_string'];
        $imgname=random_str(30).'.jpg';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
        $imgUrl  = './upload/'. $imgname;
        if(!$f  = file_put_contents($imgUrl, $info['id3v2']['APIC'][0]['data'],FILE_APPEND)) {// 这个函数支持版本(PHP 5)
            $imgname=null;
        }
//id3转码
        $info['id3v2']['comments']['title'][0]==null ? $music_name=$info['id3v1']['title'] : $music_name=$info['id3v2']['comments']['title'][0];
        $info['id3v2']['comments']['artist'][0]==null ? $music_artist=$info['id3v1']['artist'] :  $music_artist= $info['id3v2']['comments']['artist'][0];
        $nencode = mb_detect_encoding($music_name, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $aencode = mb_detect_encoding($music_artist, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $music_name=mb_convert_encoding($music_name, 'utf-8', $nencode) ;
        $music_artist=mb_convert_encoding($music_artist, 'utf-8', $aencode) ;

//保存歌曲信息
        $music = M("m_music");
        $data[file_name] =  explode(".mp3",$file['name'])[0];
        $data[music_name] = $music_name;
        $data[music_artist] = $music_artist;
        $data[music_length] = $time;
        $data[music_url] = $url;
        $imgname==null?$data[music_img_url] ='' :$data[music_img_url]=C('DOMAIN') . "CloudPlayerServer/upload" . "/" .$imgname;
        $music->add($data);
        response(ERRNO_SUCCESS, "success", json_encode(array('singerName' => $data[music_name],'artist' => $data[music_artist],'singerTime'=>$data[music_length],'url' => $url,'imgUrl'=>$data[music_img_url]),JSON_UNESCAPED_UNICODE));
    }

}
