<?php

/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/14
 * Time: 9:50
 */

namespace app\server\controller;

use think\Controller;
use think\Db;
use app\common\model\Teacher;

class TeacherController extends Controller
{

    public function __construct()
    {
        // 调用父类构造函数(必须)
        parent::__construct();

        // 验证用户是否登陆
        if (!Teacher::isLogin()) {
            return $this->error('plz login first', url('Login/index'));
        }
    }

    public function index()
    {
        echo 'index模块下的 Teacher控制器 index方法';

        // 获取教师表中的所有数据
        $Teacher = new Teacher();
        $teachers = $Teacher->select();
        // 获取第0个数据
        $teacher = $teachers[0];

        // 调用上述对象的getData()方法
        dump($teacher->getData());
        // 向V层传数据
        $this->assign('teachers', $teachers);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }

    public function insert()
    {
        echo 'index模块下的 Teacher控制器 insert';
        // 接收传入数据
        $postData = $this->request->post();

        // 实例化Teacher空对象
        $Teacher = new Teacher();

        // 为对象赋值
        $Teacher->name = $postData['name'];
        $Teacher->username = $postData['username'];
        $Teacher->sex = $postData['sex'];
        $Teacher->email = $postData['email'];

        // 新增对象至数据表
        $Teacher->save();

        // 反馈结果
        return '新增成功。新增ID为:' . $Teacher->id;
    }

    public function add()
    {
        echo 'index模块下的 Teacher控制器 add';
        $htmls = $this->fetch();
        return $htmls;
    }

    public function delete()
    {
        echo 'index模块下的 Teacher控制器 delete';
        $postData = $this->request->param();
        // 获取要删除的对象

        $Teacher = Teacher::get($postData['id']);

        // 要删除的对象存在
        if (!is_null($Teacher)) {
            // 删除对象
            if ($Teacher->delete()) {
                return '删除成功';
            }
        }

        return '删除失败';
    }
}