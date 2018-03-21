<?php
namespace Admin\Controller;
use Think\Controller;
class DefaultController extends Controller{
    public function login(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if($username && $password) {
            $m_operator = M("m_operator");
            $map["operator_name"] = $username;
            $map["operator_pswd"] = $password;
            $use = $m_operator->where($map)->find();
            if (!$use) {
                $this->assign("msg", "用户名或密码错误！");
                $this->display();
            } else {
                session('m_username',$username);
                session('m_password',$password);
                $data =array();
                $data["operator_time"] = date('y-m-d h:i:s',time());;
                $m_operator->where("operator_name = '".$username."'")->save($data);
                redirect('/CloudPlayerServer/index.php/Admin/Default/main');
            }
        }else{
            $this->display();
        }

    }
    public function logout(){
        session(null);
        $this->redirect('Admin/Default/login');
    }
    public function main(){
        if(session('m_username')){

            $this->display();
        }else{
            $this->redirect('Admin/Default/login');
        }

    }
    public function top(){
        $this->display();
    }
    public function index(){
        $m_operator = M("m_operator");
        $m_operators=$m_operator->where("operator_name = '".session('m_username')."'")->field("operator_time")->select();
        $data = array();
        foreach ($m_operators as $m_operator) {
            $data[] = $m_operator;
        }
        $this->assign('time',$data[0]['operator_time']);
        $this->display();
    }
    public function left(){
        $this->display();
    }
}
