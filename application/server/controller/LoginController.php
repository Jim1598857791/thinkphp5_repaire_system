<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/14
 * Time: 16:51
 */

namespace app\server\controller;


use think\Controller;
use app\common\model\Teacher;
use app\common\model\User;
use think\Request;

class LoginController extends Controller
{
    // 处理用户提交的登录数据
    public function login()
    {
        $postData = Request::instance()->param();
        $loginResult = User::login($postData['userid'], $postData['password']);

        if ($loginResult == "admin") {
            $loginResultReturn = array(
                "loginState" => "1",
                "loginUserName" => $postData['id'],
                "loginPassWord" => $postData['password'],
                "loginUserRole" => "admin",
                "msg" => "login successful!",
            );
        } else if ($loginResult == "user") {
            $loginResultReturn = array(
                "loginState" => "1",
                "loginUserName" => $postData['id'],
                "loginPassWord" => $postData['password'],
                "loginUserRole" => "user",
                "msg" => "login successful!",
            );
        } else if ($loginResult == "worker") {
            $loginResultReturn = array(
                "loginState" => "1",
                "loginUserName" => $postData['id'],
                "loginPassWord" => $postData['password'],
                "loginUserRole" => "worker",
                "msg" => "login successful!",
            );
        } else {
            $loginResultReturn = array(
                "loginState" => "0",
                "msg" => "username or password is wrong",
            );
        }
        return json_encode($loginResultReturn);
    }

    // 注销
    public function logOut()
    {
        echo 'index模块下的  Login 控制器 logOut';
        if (User::logOut()) {
            return $this->success('logout success', url('index'));
        } else {
            return $this->error('logout error', url('index'));
        }
    }
}