<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 获取一个指定范围内的伪随机数，用来快速评分测试。
 * @param $score int 正整数
 * @return int 随机分数
 * @author 零五科技
 */

function judgeOrderState($stateNumber)
{
    if (is_numeric($stateNumber) && !is_null($stateNumber)) {
        if (@$stateNumber == -2) {
            return "未维修";
        } else if (@$stateNumber == -1) {
            return "师傅已接单";
        } else if (@$stateNumber == 0) {
            return "正在维修";
        } else if (@$stateNumber == 1) {
            return "已维修";
        } else if (@$stateNumber == 2) {
            return "工单结束";
        } else {
            return "状态出错";
        }
    } else {
        return "参数错误";
    }
}

function orderTimeLine($stateNumber, $repaire_start_time, $repaire_finish_time)
{
    if (is_numeric($stateNumber) && !is_null($stateNumber)) {
        if (@$stateNumber >= -1) {
            echo "<li>
                <a href=\"#\">开始维修</a>
                <a href=\"#\" class=\"float-right\">{$repaire_start_time}</a>
                <p>师傅用尽了洪荒之力，刚刚修好！</p>
                </li>";
        }
        if (@$stateNumber >= 0) {
            echo "<li>  
               <a href=\"#\">维修中</a>
               <p>正在维修，稍安勿躁！</p>
               </li>";
        }
        if (@$stateNumber >= 1) {
            echo "<li>
                <a href=\"#\">维修结束</a>
                <a href=\"#\" class=\"float-right\">{$repaire_finish_time}</a>
                <p>师傅用尽了洪荒之力，刚刚修好！</p>
            </li>";
        }
        if (@$stateNumber >= 2) {
            echo "<li >
                <a href=\"#\">工单关闭</a>
                <p>谢谢使用！</p>
            </li>";
        }
        return "";
    } else {
        return "参数错误";
    }
}