<?php
namespace Home\Controller;
use Think\Controller;
class MusicController extends Controller{
    //得到mp3列表
    function get_music_list() {
//          $token = get_param('token', null);
//        $user = check_user_by_usertoken($token);
        $m_music = M("m_music");
        $musics = $m_music->field("file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,music_url as url,music_img_url as album_img")->select();
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        response(ERRNO_SUCCESS, "success", json_encode($data,JSON_UNESCAPED_UNICODE));
    }
}