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
    }

    function  index()
    {
        $this->display("index_index.html");
    }


    /**
     * 处理资金列表按钮
     * Created by QQ:710932
     */
    function user_money_list()
    {
        /*1搜索条件*/
        $username = (isset($_GET['soname']) && $_GET['soname'] != '') ? $_GET['soname'] : '';
        $beginmoney = (isset($_GET['somoney']) && $_GET['somoney'] != '') ? $_GET['somoney'] : '';
        $endmoney = (isset($_GET['endmoney']) && $_GET['endmoney'] != '') ? $_GET['endmoney'] : '';

        $page = $this->_get_page(20);
        /*2检查数据合法性*/
        //金币只能是整数
        if ((preg_match("/[^0.-9]/", $beginmoney)) or (preg_match("/[^0.-9]/", $endmoney)))
        {
            $this->show_warning('非法搜索条件_金币只能输入整数');
            return;
        }

        if(empty($beginmoney)){
            $beginmoney = 0;
        }
        if(empty($endmoney)){
            $endmoney = 9999999;
        }

        if($beginmoney>$endmoney){
            $this->show_warning("非法搜索条件_最小金额不能大于最大金额");
            return;
        }




        /*3查询数据*/
        $lb_money_mod =& m('lb_money');
        $userMoneyList = $lb_money_mod->find(array(

            'conditions' => "user_name LIKE '%$username%' and (money_used+money_freeze)>='$beginmoney' and (money_used+money_freeze)<='$endmoney'",//条件
            'limit' => $page['limit'],
            'order' => "(money_used+money_freeze) desc",
            'count' => true));


        foreach( $userMoneyList as  $k=>$v){
            $userMoneyList[$k]['zongzichan'] = sprintf("%01.2f",($v['money_used']+$v['money_freeze']));

        }

        $page['item_count'] = $lb_money_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        $this->assign('index',$userMoneyList);
        $this->display("user_money_list.html");
    }

    /**
     * 处理增加用户资金按钮
     * Created by QQ:710932
     */
    function user_money_add()
    {
        if (IS_POST) {
            $modify_time = time();

            /*获取post参数*/
            $username = (isset($_POST['user_name']) && $_POST['user_name'] != '') ? $_POST['user_name'] : '';
            $money = (isset($_POST['post_money']) && $_POST['post_money'] != '') ? $_POST['post_money'] : '';
            $message = (isset($_POST['log_text']) && $_POST['log_text'] != '') ? $_POST['log_text'] : '';

            /*post参数安全校验*/
            //参数不能为空
            if (empty($username) or empty($money)) {
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
            if($data==false){
               $member_mod = & m('member');
                $memberData = $member_mod->get("user_name='$username'");
                if(!$memberData){
                    $this->show_warning('错误_该用户不存在');
                    return;
                }

                //虚拟账户初始化
                $lb_money_mod = & m('lb_money');
                $lb_money_mod->createaccountByName($username);
                $data = $lb_money_mod->get("user_name='$username'");
            }

            //虚拟账户数组资金修改
            $data['money_used'] = floatval($data['money_used']) + $money;

            //创建日志数组
            $user_id = $data['user_id'];
            $user_name = $data['user_name'];
            $lb_recharge_log_data = array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'recharge_money' => $money,
                'recharge_method' => '管理员充值',
                'recharge_message' => $message,
                'admin_name' => $this->visitor->get('user_name'),
                'add_time' => $modify_time,
            );
            //虚拟账户修改提交

            $lb_money_mod->edit('user_id=' . $data['user_id'], "money_used=$data[money_used],modify_time=$modify_time");
            //日志创建体检
            $lb_recharge_log_mod = &m('lb_recharge_log');
            $lb_recharge_log_mod->add($lb_recharge_log_data);

            //返回
            $this->show_message('充值成功', '返回列表', 'index.php?module=lb_money&act=user_money_list');
            return;
        } else {
            $user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';
            $user_name = isset($_GET['user_name']) ? trim($_GET['user_name']) : '';
            if (!empty($user_id)) {
                $lb_money_mod = & m(lb_money);
                $index = $lb_money_mod->find('user_id=' . $user_id);
            }
            $this->assign('index', $index);
            $this->display("user_money_add.html");
        }

    }

    /**
     * 作用:用户充值日志按钮
     * Created by QQ:710932
     */
    function user_rechargemoney_log(){

        $user_name = (isset($_GET['soname']) && $_GET['soname']!='')?$_GET['soname']:'';
        $begin_time = (isset($_GET['sotime']) && $_GET['sotime']!='')?$_GET['sotime']:'';
        $end_time = (isset($_GET['endtime']) &&  $_GET['endtime']!='')?$_GET['endtime']:'';

        if(empty($begin_time) or $begin_time==false){
            $begin_time = '2000-1-1';
        }

        if(empty($end_time) or $end_time==false) {
            $end_time = date('Y-m-d');
        }

        if ((preg_match("/^(\d+)-(\d{1,2})-(\d{1,2})$/", $begin_time) == 0) or ((preg_match("/^(\d+)-(\d{1,2})-(\d{1,2})$/", $end_time) == 0)))
        {
            $this->show_warning('时间输入不合法');
            return;
        }

        $begin_time = strtotime($begin_time);
        $end_time = strtotime($end_time);
        if($begin_time>$end_time){
            $this->show_warning('警告_开始时间不得大于结束时间');
            return;
        }

        $page = $this->_get_page(20);

        $model = & m("lb_recharge_log");
        $data = $model->find(array(
            'conditions' => "user_name LIKE '%$user_name%' and add_time>=$begin_time and add_time<=$end_time",
            'order' => "add_time desc",
            'limit' => $page['limit'],
            'count' => true
        ));


        $page['item_count'] = $model->getCount();
        $this->_format_page($page);

        $this->assign('page_info', $page);
        $this->assign("index",$data);
        $this->display("user_rechargemoney_log.html");
    }


    /**
     * 作用:针对平台用户的审核申请作出处理
     * Created by QQ:710932
     */
    function user_money_tixianshenqing()
    {
        $user_name = $_GET['soname'];
        $begin_time = $_GET['sotime'];
        $end_time = $_GET['endtime'];
        if(empty($begin_time) or $begin_time==false){
            $begin_time = '2000-1-1';
        }

        if(empty($end_time) or $end_time==false) {
            $end_time = date('Y-m-d');
        }

        if ((preg_match("/^(\d+)-(\d{1,2})-(\d{1,2})$/", $begin_time) == 0) or ((preg_match("/^(\d+)-(\d{1,2})-(\d{1,2})$/", $end_time) == 0)))
        {
            $this->show_warning('时间输入不合法');
            return;
        }

        $begin_time = strtotime($begin_time);
        $end_time = strtotime($end_time);
        if($begin_time>$end_time){
            $this->show_warning('警告_开始时间不得大于结束时间');
            return;
        }


        $page = $this->_get_page(20);

        $model = & m("lb_tixian");
        $data = $model->find(array(
            'conditions' => "user_name LIKE '%$user_name%' and add_time>=$begin_time and add_time<=$end_time",
            'order' => "add_time desc",
            'limit' => $page['limit'],
            'count' => true
        ));


        $page['item_count'] = $model->getCount();
        $this->_format_page($page);

        $this->assign('page_info', $page);

        $this->assign("index",$data);
        $this->display("tx_index_tixianshenhe.html");
    }

    /**
     * 作用:查看提现详情
     * Created by QQ:710932
     */
    function tixianshenhexiangqing(){
        if($_POST){
            $tixian_sn = $_POST['tixian_sn'];
            $istongguo = $_POST['istongguo'];
            if(!isset($tixian_sn) or !isset($istongguo) or empty($tixian_sn) or empty($istongguo)){
                $this->show_warning('非法提交,请与管理员联系');
                return;
            }

            $status = $istongguo=='yes'?1:-2;
            if($istongguo == 'yse'){
                $status = 1;
                $remark = '您的订单已通过管理员'.$this->visitor->get('user_name').'的审核,资金请注意查收';
                $tishi = '已拒绝提现申请';
            }
            else{
                $status = -2;
                $remark = '您的订单已被管理员'.$this->visitor->get('user_name').'拒绝';
                $tishi = '已通过提现申请';
            }


            $tixian_mod = &m('lb_tixian');
            $tixian_data = $tixian_mod->get('tixian_sn='.$tixian_sn);
            if(empty($tixian_data)){
                $this->show_warning('提现订单不存在,如有疑问请与超级管理员联系');
                return;
            }

            $tixian_data['check_time'] = time();
            $tixian_data['remark'] = $remark;
            $tixian_mod->edit('tixian_sn='.$tixian_sn,$tixian_data);

            $this->show_message($tishi);
            return;
        }
        else{
            $tixian_sn = $_GET['sn'];
            if(!isset($tixian_sn) or empty($tixian_sn)){
                $this->show_warning('提现申请不存在');
                return;
            }
            $tixian_mod = &m('lb_tixian');
            $tixian_data = $tixian_mod->get('tixian_sn=' . $tixian_sn);
            if(empty($tixian_data)){
                $this->show_warning('提现申请不存在');
                return;
            }

            $money_mod = &m('lb_money');
            $money_data = $money_mod->get($tixian_data['user_id']);
            $money_data['zongjine'] = $money_data['money_used']+$money_data['money_freeze'];

            $result  =  array_merge ( $tixian_data ,  $money_data );
            $this->assign('result', $result);
            $this->display('tx_shenhe_user.html');
        }
    }

}