<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/4
 * Time: 11:12
 */

namespace app\index\controller;

use think\Db;       // 数据库操作类
use app\common\model\User;       // 教师模型
use think\Controller;
use think\Request;            // 引用Request
class TeacherController extends Controller
{
    public function __construct()
    {
        // 调用父类构造函数(必须)
        parent::__construct();
        // 验证用户是否登陆
        if (!User::isLogin()) {
            return $this->error('plz login first', url('Login/index'));
        }
    }

    public function index()
    {
        //         验证用户是否登录
        if (!User::isLogin()) {
            return $this->error('plz login first', url('Login/index'));
        }
        // 获取查询信息
        $name = input('get.name');
        // 验证用户是否登录
        $UserId = session('UserId');
        if ($UserId === null) {
            return $this->error('plz login first', url('Login/index'));
        }
        // 获取查询信息
        $name = Request::instance()->get('id');
        $pageSize = 5; // 每页显示5条数据
        // 实例化Teacher
        $Teacher = new Teacher;
        // 按条件查询数据并调用分页
        $users = $Teacher->where('name', 'like', '%' . $name . '%')->paginate($pageSize, false, [
            'query' => [
                'name' => $name,
            ],
        ]);
        // 向V层传数据
        $this->assign('teachers', $users);
        // 取回打包后的数据
        $htmls = $this->fetch();
        // 将数据返回给用户
        return $htmls;
    }

//    获取用户 添加的表单输入
    public function add()
    {
        try {
            $htmls = $this->fetch();
            return $htmls;
        } catch (\Exception $e) {
            return '系统错误' . $e->getMessage();
        }
    }

//    将用户输入的表单  插入到数据库
    public function insert()
    {
         try {
            // 接收传入数据
            $postData = Request::instance()->post();

            // 实例化Teacher空对象
            $Teacher = new Teacher();

            // 为对象赋值

            $Teacher->username = $postData['username'];
            $Teacher->sex = $postData['sex'];
            $Teacher->email = $postData['email'];

            // 新增对象至数据表
            $result = $Teacher->validate(true)->save();

            // 反馈结果
            if (false === $result) {
                // 验证未通过，发生错误
                $message = '新增失败:' . $Teacher->getError();
            } else {
                // 提示操作成功，并跳转至教师管理列表
                return $this->success('用户' . $Teacher->username . '新增成功。', url('index'));
            }
        } catch (\Exception $e) {
            // 发生异常
            return $e->getMessage();
        }

        return $this->error($message);

    }

//    删除
    public function delete()
    {
        $id = Request::instance()->param('id/d'); // “/d”表示将数值转化为“整形”
        if (is_null($id) || 0 === $id) {
            return $this->error('未获取到ID信息');
        }
        //获取要删除的对象 $data  主键值或者查询条件（闭包）
        $Teacher = Teacher::get($id);
        // 要删除的对象不存在
        if (is_null($Teacher)) {
            return $this->error('不存在id为' . $id . '的教师，删除失败');
        }
        // 删除对象
        if (!$Teacher->delete()) {
            return $this->error('删除失败:' . $Teacher->getError());
        }
        // 进行跳转
        return $this->success('删除成功', url('index'));

    }

//    编辑老师信息
    public function edit()
    {
        // 获取传入ID
        $id = Request::instance()->param('id/d');
        // 在Teacher表模型中获取当前记录
        if (is_null($Teacher = Teacher::get($id))) {
            return '系统未找到ID为' . $id . '的记录';
        }
        // 将数据传给V层
        $this->assign('Teacher', $Teacher);
        // 获取封装好的V层内容
        $htmls = $this->fetch();
        // 将封装好的V层内容返回给用户
        return $htmls;
    }

//    接受编辑后的教师信息 更新数据库表格
    public function update()
    {
        dump(Request::instance()->post());
    }

//    合法 测试 方法
    public function test()
    {
        $data = array();
        $data['username'] = '2485';

        $data['sex'] = '1';
        $data['email'] = 'hello@hello.com';
        var_dump($this->validate($data, 'Teacher'));
    }

}