<?php
namespace Admin\Controller;
use Think\Controller;
class MusicController extends Controller{
    //搜索
    function search(){
        if(!session("m_username")){
            $this->redirect('Admin/Default/login');
        }
        $keyword=$_GET['keyword'];
        $page=1;
        $limit=10;
        $m_music = M("m_music");
        $sql = "select music_id as id,file_name as fileName,music_name as musicName,music_artist as artist,music_length as time,music_url as url,lrc_url,music_img_url as album_img from m_music where upper(file_name) like upper('%".$keyword."%')";
        $musics=$m_music->query($sql);
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        $count = count($data);
        $this->assign('data',$data);// 赋值分页输出
        $this->assign('totalPages',round($count/$limit));
        $this->assign('count',round($count));
        $this->assign('limit',$limit);
        $this->assign('page',$page);
        $this->assign('keyword',$keyword);
        $this->display(m_list); // 输出模板
    }
    //得到mp3列表
    function m_list() {
        if(!session("m_username")){
            $this->redirect('Admin/Default/login');
        }
        $page=$_GET['p'];
        $limit=10;
        $m_music = M("m_music");
        $musics = $m_music->where("music_enabled=1")->field("music_id as id,file_name as filename,music_name as musicname,music_artist as artist,music_length as time,music_url as url,video_url,lrc_url,music_img_url as album_img")->page($page.','.$limit)->select();
        $data = array();
        foreach ($musics as $music) {
            $data[] = $music;
        }
        $count =count($data);
        $this->assign('data',$data);// 赋值分页输出
        $this->assign('totalPages',round($count/$limit));
        $this->assign('count',round($count));
        $this->assign('limit',$limit);
        $this->assign('page',$page);
        $this->display(); // 输出模板
    }
    function add(){
        $this->display();
    }
    function xiajia(){
        $mid = get_param('mid');
        $m_music = M("m_music");
        $data['music_enabled'] = 0;
        $musics = $m_music->where("music_id =".$mid)->save($data);
        echo C('DOMAIN');

        if ($musics){
            $this->success('下架成功',  C('DOMAIN').'/CloudPlayerServer/index.php/Admin/Music/m_list');
        }else{
            response(-1,"下架失败！");
        }
    }
    function delete(){
        $mid = get_param('mid');
        $murl = get_param('murl');
        $lurl = get_param('lurl');
        $vurl = get_param('vurl');
        $iurl = get_param('iurl');
        //获取最后一个/后边的字符
        $arr=explode("/", $murl);
        $mname=$arr[count($arr)-1];

        $arr=explode("/", $lurl);
        $lname=$arr[count($arr)-1];

        $arr=explode("/", $vurl);
        $vname=$arr[count($arr)-1];

        $arr=explode("/", $iurl);
        $iname=$arr[count($arr)-1];

        //删除歌曲
        $file_url = "./upload/".$mname;
        $mname != null?unlink($file_url):null;
        //删除歌词
        $file_url = "./upload/".$lname;
        $lname != null && $lname != 'public.lrc' ? unlink($file_url):null;
        //删除视频
        $file_url = "./upload/".$vname;
        $vname != null ? unlink($file_url):null;
        //删除封面
        $file_url = "./upload/".$iname;
        $iname != null && $lname != 'public.jpg' ? unlink($file_url):null;

        //删除数据
        $m_music = M("m_music");
        $musics = $m_music->where("music_id =".$mid)->delete();
        if ($musics){
            $this->success('删除成功',  C('DOMAIN').'/CloudPlayerServer/index.php/Admin/Music/m_list');
        }else{
            response(-1,"删除失败！");
        }

    }
}