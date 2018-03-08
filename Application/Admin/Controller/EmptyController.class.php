<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller{
    function _empty(){
        response(-9,  " HTTP/1.0  404  Controller Not Found");
    }
}
