<?php
namespace Home\Controller;
use Think\Controller;
class AppController extends Controller{
	function hello(){
//        $mp3 = new \getID3();
//        $fileName=$_SERVER['DOCUMENT_ROOT']."/music/upload/JALDmXPVAlovmV2HuHZTWAAB79FJbN.mp3";
//        $info =  $mp3->analyze($fileName);
//        dump($info);
//        $time=$info['id3v2'];
//        dump($time['APIC']);
//        $file  = 'a.jpg';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
//        if($f  = file_put_contents($file, $time['APIC'][0]['data'],FILE_APPEND)) {// 这个函数支持版本(PHP 5)
//            echo "写入成功。<br />";
//        }
        $str = "曲婉婷-我的歌声里.mp3";
        print_r (explode(".",$str));
        echo preg_replace('/^.+[\\\/]/','.mp3', '曲婉婷-我的歌声里.mp3');

	}

}

//<?php
//namespace Home\Controller;
//use Think\Controller;
//class AppController extends Controller{
//    function hello(){
//        $mp3 = new \getID3();
//        $fileName='G:\wamp64\www\music\a.mp3';
//        $info =  $mp3->analyze($fileName);
////        dump($info);
//        $time=$info['id3v2'];
//        dump($time['APIC']);
//
//    }
//    function random_str($length)
//    {
//        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
//        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
//
//        $str = '';
//        $arr_len = count($arr);
//        for ($i = 0; $i < $length; $i++)
//        {
//            $rand = mt_rand(0, $arr_len-1);
//            $str.=$arr[$rand];
//        }
//        return $str;
//    }
//}