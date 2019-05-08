<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/23
 * Time: 15:03
 */

namespace app\index\controller;

use think\ Controller;

use app\common\model\User;
use app\common\model\Order;
use think\Request;
use think\Config;

class AdminController extends Controller
{
    public $userid = "1";
    public function index()
    {
        echo "AdminController index";
        // 判断（当前作用域）是否赋值
        if (session('?userId')) {
            echo "session(userID)存在";
            $userid = session('userId');
            dump($userid);
        } else {
            return $this->error('数据错误', url('index\Login\index'));
        }
        $user = User::get($userid);
        $all_orders = Order::all();
        // 向V层传数据
        $this->assign([
            "user" => $user,
            "all_orders" => $all_orders,
        ]);
        // 取回打包后的数据
        $htmls = $this->fetch();
        // 将数据返回给用户
        return $htmls;
    }

    public function adminDetail()
    {
        echo "AdminController  detail";
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

    public function delete($order_id)
    {
        echo "AdminController  delete";
        $order = Order::get($order_id);
        $order->delete();
    }

    public function edit()
    {
        echo "AdminController  edit";
    }
    public function worker()
    {
        echo "AdminController  worker";
    }
}