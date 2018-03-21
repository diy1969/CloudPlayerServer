<?php
namespace Home\Controller;
use Think\Controller;
class MusicController extends Controller{
    function log(){
        $keyword = get_param('keyword');
        $myfile = fopen("testfile.txt", "a+");
        $txt = $keyword."\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    function getlog(){
        $myfile = fopen("testfile.txt", "r");
        $data=fread($myfile,filesize("testfile.txt"));
        fclose($myfile);
        echo $data;
    }
    //搜索mp3列表
    function search(){
        $keyword = get_param('keyword');
        $keyword == null?response(ERRNO_SUCCESS, "success", json_encode(array(),JSON_UNESCAPED_UNICODE)):null;
        $m_music = M("m_music");
//        $map['file_name'] = array('like','%'.$keyword.'%');
//        $musics = $m_music->where($map)->field("music_id as id,file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,music_url as url,lrc_url,music_img_url as album_img")->select();
        $sql = "select music_id as id,file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,music_url as url,lrc_url,music_img_url as album_img from m_music where upper(file_name) like upper('%".$keyword."%')";
        $musics=$m_music->query($sql);
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        response(ERRNO_SUCCESS, "success", json_encode($data,JSON_UNESCAPED_UNICODE));
    }
    //得到mp3列表
    function get_music_list() {
        $m_music = M("m_music");
        $musics = $m_music->where("music_enabled=1")->field("music_id as id,file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,music_url as url,lrc_url,music_img_url as album_img")->select();
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        response(ERRNO_SUCCESS, "success", json_encode($data,JSON_UNESCAPED_UNICODE));
    }
    //得到视频列表
    function get_video_list() {

        $m_music = M("m_music");
        $musics = $m_music->field("music_id as id,file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,video_url,music_img_url as album_img")->where("video_url != ''")->select();
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        response(ERRNO_SUCCESS, "success", json_encode($data,JSON_UNESCAPED_UNICODE));
    }
}