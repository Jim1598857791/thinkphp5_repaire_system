<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/14
 * Time: 16:51
 */

namespace app\index\controller;


use think\Controller;
use app\common\model\User;
use think\Request;
use think\Config;

class LoginController extends Controller
{

    public function index()
    {
        echo 'index模块下的  Login 控制器 index';
        // 显示登录表单
        return $this->fetch();
    }

    // 处理用户提交的登录数据
    public function login()
    {
//      echo 'index模块下的  Login 控制器 login';// 接收post信息
        $postData = Request::instance()->param();
        $loginResult = User::login($postData['userid'], $postData['password']);
        if ($loginResult == "admin") {
            return $this->success('login success，admin.你是管理员', url('Admin/index'));
//            url('project/editMovie', ['id' => $vo['mov_id']]) ,['userid'=>$postData['userid']]
        } else if ($loginResult == "user") {
            return $this->success('login success,user. 你是用户 ', url('Apply/index'));
        } else if ($loginResult == "worker") {
            return $this->success('login success,worker.你是维修人员', url('Repaire/index'));
        } else {
            return $this->error(' userid or password incorrent', url('index'));
        }
        echo "登陆成功";
    }

    /**
     * 判断身份
     * @return
     * @author jf12138
     */
    public function group($id)
    {
        $map = array('user_id' => $id);
        $currentUser = User::get($map);
        $user_group = $currentUser->getData('user_group');

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