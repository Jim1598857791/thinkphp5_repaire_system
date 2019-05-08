<?php
/**
 * Created by PhpStorm.
 * User: 42282
 * Date: 2019/4/17
 * Time: 23:23
 */

namespace app\index\controller;


use think\Controller;
use   think\Request;
use app\common\model\User;
use app\common\model\Order;
use think\Cache;

class RepaireController extends Controller
{
    public function index()
    {
        echo "RepaireController index";
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

//       向V层传数据
        $this->assign([
            "user" => $user,
            "all_orders" => $all_orders
        ]);
        // 取回打包后的数据
        $htmls = $this->fetch();
        // 将数据返回给用户
        return $htmls;
    }

    public function repaireDetail()
    {
        if (session('?userId')) {
            echo "session(userID)存在";
            $userid = session('userId');
            dump($userid);
        } else {
            return $this->error('数据错误', url('index\Login\index'));
        }
        // 获取工单号码查看 详情
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

    public function doRepaire()
    {
        echo "我要维修啦,".date('Y-m-d H:i:s');
        $repaire_state = Request::instance()->param('state'); // “/d”表示将数值转化为“整形”
        $repaire_order_id = Request::instance()->param('id'); // “/d”表示将数值转化为“整形” = Request::instance()->param('state'); // “/d”表示将数值转化为“整形”
        if (is_null($repaire_order_id) || 0 === $repaire_order_id) {
            return $this->error('未获取到工单信息');
        }
        $order = Order::get(['repaire_order_id' => $repaire_order_id]);
        $order->repaire_state = $repaire_state;
        $order->repaire_finish_time = date('Y-m-d H:i:s');

        $order->save();
        return $this->success('已提交', url('index\Repaire\index'));
    }


}
