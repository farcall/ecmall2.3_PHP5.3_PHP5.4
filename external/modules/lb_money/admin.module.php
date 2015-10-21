<?php

/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/20
 * Time: 22:10
 */
class Lb_moneyModule extends AdminBaseModule
{


    function __construct()
    {
        $this->Lb_money();
    }

    function  Lb_money()
    {
        parent::__construct();

        $this->lb_money_mod =& m('lb_money');
        $this->lb_line_order_mod =& m('lb_line_order');
        $this->lb_reward_log_mod =& m('lb_reward_log');
        $this->lb_rewardrule_config_mod =& m('lb_rewardrule_config');
        $this->lb_tixian_mod =& m('lb_tixian');
        $this->lb_transfer_money_log_mod =& m('lb_transfer_money_log');
    }

    function  index()
    {
        $this->display("index_index.html");
    }


function user_money_list()
    {
        var_dump($this);
    }

    function user_money_add()
    {
        if(IS_POST){
            //获取post参数
            //post参数安全校验
            //用户名读取虚拟账户
            //虚拟账户数组资金修改
            //创建日志数组
            //虚拟账户修改提交
            //日志创建体检
        }else{
            $this->display("user_money_add.html");
        }

    }
}