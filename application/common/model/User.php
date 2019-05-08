<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/17
 * Time: 22:33
 */

namespace app\common\model;

use think\Model;

class User extends Model
{
    /**
     * 用户登录
     * @param  string $username 用户名
     * @param  string $password 密码
     * @return bool  成功返回true，失败返回false。
     */
    static public function login($id, $password)
    {
        $currentUser = null;
        // 验证用户是否存在
        $map = array('user_id' => $id);
        $currentUser = self::get($map);
        echo 'user.php 中的currentUser';
        dump($currentUser['user_id']);
        if (!is_null($currentUser)) {
            // 验证密码是否正确
            if ($currentUser->getData('user_password') === $password) {
                // 登录
                session('userId', $currentUser->getData('user_id'));
                return $currentUser->getData('user_group');
            }
        }
        return "error";
    }


    /**
     * 注销
     * @return bool  成功true，失败false。
     * @author jf12138
     */
    static public function logOut()
    {
        // 销毁session中数据
        session('userId', null);
        return true;
    }
}