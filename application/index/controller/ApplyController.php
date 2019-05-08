<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/23
 * Time: 15:02
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
use app\common\model\Order;
use think\Cache;

class ApplyController extends Controller
{
    public function index()
    {
        echo " ApplyController index";
        // $id = Request::instance()->get('userid');
        // 判断（当前作用域）是否赋值
        if (session('?userId')) {
            echo "session(userID)存在";
            $userid = session('userId');
            dump($userid);
        } else {
            return $this->error('数据错误', url('index\Login\index'));
        }

        $user = User::get($userid);
        $my_orders = Order::all(['repaire_person_id' => $userid]);
        $other_orders = Order::all();
//        foreach($list as $key=>$user){
//            echo $user->repaire_order_id;
//        }
//        dump($user);  dump($orders);
//       向V层传数据
        $this->assign([
            "user" => $user,
            "my_orders" => $my_orders,
            "other_orders" => $other_orders,
        ]);
        // 取回打包后的数据
        $htmls = $this->fetch();
        // 将数据返回给用户
        return $htmls;
    }


    //    获取用户 添加的表单输入
    public function add()
    {
        echo date('Y-m-d H:i:s');
        try {
            $htmls = $this->fetch();
            return $htmls;
        } catch (\Exception $e) {
            return '系统错误' . $e->getMessage();
        }
    }

    //    获取用户 添加的表单输入
    public function applyDetail()
    {
        if (session('?userId')) {
            echo "session(userID)存在";
            $userid = session('userId');
            dump($userid);
        } else {
            return $this->error('数据错误', url('index\Login\index'));
        }
//        获取工单号码查看 详情
        $repaire_order_id = Request::instance()->param('id'); // “/d”表示将数值转化为“整形”
        if (is_null($repaire_order_id) || 0 === $repaire_order_id) {
            return $this->error('未获取到工单信息');
        }
        $user = User::get($userid);
        //获取 要查看 的对象 $data  主键值或者查询条件（闭包）
        $order = Order::get(['repaire_order_id' => $repaire_order_id]);
        // 向V层传数据
        $this->assign([
            "user" => $user,
            "order" => $order]);
        // 取回打包后的数据
        $htmls = $this->fetch();
        // 将数据返回给用户
        return $htmls;
    }

//    将用户输入的表单  插入到数据库
    public function insert()
    {
        // 接收传入数据
        $postData = Request::instance()->post();

        $currnet_date = date('Y-m-d H:i:s');
        echo("接受的数据");
        dump($postData);

        if ($postData['repaire_type'] == '0') {
            $repaire_type = "水";
        } else if ($postData['repaire_type'] == '1') {
            $repaire_type = "电";
        } else {
            $repaire_type = "暖";
        }
        // 实例化Teacher空对象
        $order = new Order();
        // 为对象赋值
        $order->repaire_state = -1;
        $order->repaire_order_id = self::get_sn();

        $order->repaire_area = $postData['repaire_area'];
        $order->repair_location = $postData['repair_location'];
        $order->repaired_date = $postData['repaired_date'];
        $order->repaire_type = $repaire_type;
        $order->repaire_des = $postData['repaire_des'];
        $order->repaire_tel = $postData['repaire_tel'];



        // 新增对象至数据表
        $result = $order->validate(true)->save();
        // 反馈结果
        if (false === $result) {
            // 验证未通过，发生错误
            $message = '新增失败:' . $order->getError();
        } else {
            // 提示操作成功，并跳转至教师管理列表
            return $this->success('报修工单【' . $order->repaire_order_id . '】新增成功。', url('index'));
        }
        return $this->error($message);
    }

//    创建工单号
    function get_sn()
    {
        return date('YmdHis') . rand(100000, 999999);
    }
}