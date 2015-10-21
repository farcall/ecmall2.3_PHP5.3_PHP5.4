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
            $modify_time = time();

            /*获取post参数*/
            $username = (isset($_POST['user_name'])&&$_POST['user_name']!='') ? $_POST['user_name']:'';
            $money = (isset($_POST['post_money'])&&$_POST['post_money']!='') ? $_POST['post_money']:'';
            $message = (isset($_POST['log_text'])&&$_POST['log_text']!='') ? $_POST['log_text']:'';

            /*post参数安全校验*/
            //参数不能为空
            if(empty($username) or empty($money)){
                $this->show_warning('亲,请完整输入!');
                return;
            }
            //金币只能是整数
            if (preg_match("/[^0.-9]/", $money)) {
                $this->show_warning('错误_请正确输入金币数量');
                return;
            }
            //todo 其他安全手段

            //用户名读取虚拟账户
            $lb_money_mod =& m('lb_money');
            $data = $lb_money_mod->get("user_name='$username'");

            //虚拟账户数组资金修改
            $data['money_used'] = floatval($data['money_used']) + $money;

            //创建日志数组
            $user_id = $data['user_id'];
            $user_name = $data['user_name'];
            $lb_recharge_log_data = array(
                'user_id'=> $user_id,
                'user_name' => $user_name,
                'recharge_money'=>$money,
                'recharge_method'=>'管理员充值',
                'recharge_message'=>$message,
                'admin_name'=>$this->visitor->get('user_name'),
                'add_time'=>$modify_time,
            );
            //虚拟账户修改提交

            $lb_money_mod->edit('user_id='.$data['user_id'],"money_used=$data[money_used],modify_time=$modify_time");
            //日志创建体检
            $lb_recharge_log_mod = & m('lb_recharge_log');
            $lb_recharge_log_mod->add($lb_recharge_log_data);

            //返回
            $this->show_message('充值成功', '返回列表', 'index.php?module=lb_money&act=user_money_list');
            return;
        }else{
            $this->display("user_money_add.html");
        }

    }
}