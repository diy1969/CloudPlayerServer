<?php

namespace Home\Controller;

use Think\Controller;

class UploadController extends Controller {
    //上传文件
    function upload() {
        if (!isset($_FILES["music"]) && !isset($_FILES["video"]) && !isset($_FILES["lrc"])){
            response(ERRNO_FAIL, "file not found");
            exit();
        }
        $music = $_FILES["music"];
        $video = $_FILES["video"];
        $lrc = $_FILES["lrc"];
        $mname = random_str(30). '.' . pathinfo($music['name'], PATHINFO_EXTENSION);
        $lname = explode(".mp3",$mname)[0].'.' . pathinfo($lrc['name'], PATHINFO_EXTENSION);;
        $vname = random_str(30). '.' . pathinfo($video['name'], PATHINFO_EXTENSION);
        if ($music["error"] > 0) {
            response(ERRNO_FAIL, "file error");
            exit();
        }
        move_uploaded_file($music["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $mname);
        move_uploaded_file($video["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $vname);
        move_uploaded_file($lrc["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $lname);
        $murl = C('DOMAIN') . "CloudPlayerServer/upload" . "/" . $mname;
        $vurl = C('DOMAIN') . "CloudPlayerServer/upload" . "/" . $vname;
        $lurl = C('DOMAIN') . "CloudPlayerServer/upload" . "/" . $lname;

        $mp3 = new \getID3();
        $fileName=$_SERVER['DOCUMENT_ROOT']."/CloudPlayerServer/upload/" . $mname;
        $info =  $mp3->analyze($fileName);
        $time=$info['playtime_string'];
        $imgname=random_str(30).'.jpg';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
        $imgUrl  = './upload/'. $imgname;
        if(!$f  = file_put_contents($imgUrl, $info['id3v2']['APIC'][0]['data'],FILE_APPEND)) {// 这个函数支持版本(PHP 5)
            $imgname=null;
        }
        if($video["error"] > 0){
            $vurl=null;
        }
        if($lrc["error"] > 0){
            $lurl=null;
        }
//id3转码
        $info['id3v2']['comments']['title'][0]==null ? $music_name=$info['id3v1']['title'] : $music_name=$info['id3v2']['comments']['title'][0];
        $info['id3v2']['comments']['artist'][0]==null ? $music_artist=$info['id3v1']['artist'] :  $music_artist= $info['id3v2']['comments']['artist'][0];
        $nencode = mb_detect_encoding($music_name, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $aencode = mb_detect_encoding($music_artist, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $music_name = mb_convert_encoding($music_name, 'utf-8', $nencode) ;
        $music_artist = mb_convert_encoding($music_artist, 'utf-8', $aencode) ;

//保存歌曲信息
        $musicModel = M("m_music");
        $data[file_name] =  explode(".mp3",$music['name'])[0];
        $data[music_name] = $music_name;
        $data[music_artist] = $music_artist;
        $data[music_length] = $time;
        $data[music_url] = $murl;
        $vurl == null ? $data[video_url]  ='' : $data[video_url] = $vurl;
        $lurl == null ? $data[lrc_url] = 'http://192.168.3.23/CloudPlayerServer/upload/public.lrc' : $data[lrc_url] = $lurl;
        $imgname == null?$data[music_img_url] = 'http://192.168.3.23/CloudPlayerServer/upload/public.jpg' :$data[music_img_url] = C('DOMAIN') . "CloudPlayerServer/upload" . "/" .$imgname;
        $musicModel->add($data);
        response(ERRNO_SUCCESS, "success", json_encode(array('singerName' => $data[music_name],'artist' => $data[music_artist],'singerTime'=>$data[music_length],'url' => $murl,'videoUrl'=>$data[video_url],'lrcUrl'=>$data[lrc_url],'imgUrl'=>$data[music_img_url]),JSON_UNESCAPED_UNICODE));
    }

}
