<?php
/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/23
 * Time: 4:55
 */

class Lb_moneyApp extends MemberbaseApp{

    function __construct(){
        parent::__construct();
     //   header("Content-type: text/html; charset=utf-8");
    }
    /**
     * 作用:转账设置
     * Created by QQ:710932
     */
    function myzhanghu(){
        $user_id = $this->visitor->get('user_id');
        $lb_money_mod = &m('lb_money');
        $data = $lb_money_mod->get_info($user_id);
        $this->_curitem('交易查询');

        $data['zongjine'] = $data['money_used']+$data['money_freeze'];
        $this->assign('money',$data);
        $this->display("lb_money.myzhanghu.html");
        return;
    }

    /**
     * 作用:交易查询
     * Created by QQ:710932
     */
    function jiaoyichaxun(){
        //todo 交易查询
        echo 'jiaoyichaxun';
        return;
    }

    /**
     * 作用:余额查询
     * Created by QQ:710932
     */
    function  yuechaxun(){
        //todo 余额查询
        echo 'yuechaxn';
        return;
    }

    /**
     * 作用:在线充值
     * Created by QQ:710932
     */
    function zaixianchongzhi(){
        //todo 在线充值
        echo 'zaixianchongzhi';
        return;
    }

    /**
     * 作用:转账
     * Created by QQ:710932
     */
    function zhuanzhang(){
        //todo 转账
        echo 'zhuanzhang';
        return;
    }

    /**
     * 作用:提现
     * Created by QQ:710932
     */
    function tixian(){
        //todo 提现
        if(IS_POST){

        }
        else{

            $user_id = $this->visitor->get('user_id');
            $lb_money_mod = &m('lb_money');
            $data = $lb_money_mod->get_info($user_id);
            $this->_curitem('提现申请');

            $data['zongjine'] = $data['money_used']+$data['money_freeze'];
            $this->assign('money',$data);
            $this->display("lb_money.tixian.html");
        }

    }

    /**
     * 作用:账户设置
     * Created by QQ:710932
     */
    function zhanghushezhi()
    {

        //todo 账户设置
        if(IS_POST){
            $bank_name = isset($_POST['yes_bank_name'])?($_POST['yes_bank_name']):'';
            $bank_address = isset($_POST['yes_bank_address'])?($_POST['yes_bank_address']):'';
            $bank_card = isset($_POST['yes_bank_card'])?($_POST['yes_bank_card']):'';
            $bank_card2 = isset($_POST['yes_bank_card2'])?($_POST['yes_bank_card2']):'';
            $bank_username = isset($_POST['yes_bank_username'])?($_POST['yes_bank_username']):'';


            if(empty($bank_name) or empty($bank_card) or empty($bank_card2) or empty($bank_username)){
                $this->show_warning('警告_银行名称_银行账户_开户名不能为空');
                return;
            }
            if($bank_card != $bank_card2){
                $this->show_warning('警告_两次输入的银行卡号不一致');
                return;
            }

            $user_id = $this->visitor->get('user_id');
            $lb_money_mod = &m('lb_money');
            $data = $lb_money_mod->get_info($user_id);
            $data['bank_name'] = $bank_name;
            $data['bank_address'] = $bank_address;
            $data['bank_card'] = $bank_card;
            $data['bank_username'] = $bank_username;

            $lb_money_mod->edit('user_id=' . $user_id, $data);
            $this->show_message('zhanghuyijinggengxinwancheng');
            return;
        }
        else{

            $user_id = $this->visitor->get('user_id');
            $lb_money_mod = &m('lb_money');
            $data = $lb_money_mod->get_info($user_id);

            $this->_curitem(Lang::get("zhanghushezhi"));
            $this->assign('money',$data);
            $this->display("lb_money.zhanghushezhi.html");
        }
    }

}