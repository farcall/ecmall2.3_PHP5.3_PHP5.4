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
        if(IS_POST){
            $tx_money = isset($_POST['tx_money'])?($_POST['tx_money']):'';
            $pass_pay = isset($_POST['pass_pay'])?($_POST['pass_pay']):'';
            if(empty($tx_money) or empty($pass_pay)){
                $this->show_warning("cuowu_jinqianshulianghezhifumimabunengweikong");
                return;
            }

            if(!is_numeric($tx_money) or ($tx_money <= 0)){
                $this->show_warning("cuowu_nishurudebushishuzilei");
                return;
            }

            $user_id = $this->visitor->get('user_id');
            $lb_money_mod = &m('lb_money');
            $data = $lb_money_mod->get_info($user_id);


            if($this->empty_bank($data)){
                $this->show_warning('cuowu_nihaimeiyoushezhiyinhangxinxi');
                return;
            }
            if($data['money_used'] < $tx_money){
                $this->show_warning("duibuqi_zhanghuyuebuzu");
                return;
            }

            if(md5($pass_pay) != $data['pass_pay']){
                $this->show_warning("cuowu_zhifumimayanzhengshibai");
                return;
            }

            //修改金额数量
            $data['money_used'] = $data['money_used'] - $tx_money;
            $data['money_freeze'] = $data['money_freeze'] + $tx_money;
            $lb_money_mod->edit('user_id=' . $user_id, $data);

            $lb_tixian_mod = &m('lb_tixian');
            //提交等待管理员审核
            $lb_tixian_log = array(
                'user_id'=>$user_id,
                'tixian_sn'=>$lb_tixian_mod->build_tixian_sn(),
                'user_name'=>$data['user_name'],
                'bank_name'=>$data['bank_name'],
                'bank_card'=>$data['bank_card'],
                'bank_username'=>$data['bank_username'],
                'remark'=>'',
                'add_time'=>time(),
                'check_time'=>0,
                'money'=>$tx_money,
                'status'=>0  //状态(体现中0;取消提现-1;提现成功1)
            );


            $lb_tixian_mod->add($lb_tixian_log);
            $this->show_message(Lang::get('tixian_chenggong'));
            return;
        }
        else{

            $user_id = $this->visitor->get('user_id');
            $lb_money_mod = &m('lb_money');
            $data = $lb_money_mod->get_info($user_id);
            $this->_curitem(Lang::get('tixianshenqing'));

            $data['zongjine'] = $data['money_used']+$data['money_freeze'];
            $this->assign('money',$data);
            $this->display("lb_money.tixian.html");
        }

    }

    /**
     * 作用:提现日志列表
     * Created by QQ:710932
     */
    function tixianlog(){
        //todo 提现日志
        $user_id = $this->visitor->get('user_id');
        $lb_tixian_mod = &m('lb_tixian');

        $data = $lb_tixian_mod->findAll(array(
            'conditions' => "user_id=$user_id",
            'order' => "add_time desc",
        ));
        $this->_curitem(Lang::get('tixianrizhi'));

        $this->assign('log',$data);
        $this->display("lb_money.tixianlog.html");
    }

    /**
     * 作用:用户自己取消提现
     * Created by QQ:710932
     */
    function tixianquxiao(){
        $tixian_id = $_GET['id'];

        $user_id = $this->visitor->get('user_id');
        $lb_tixian_mod = &m('lb_tixian');

        $data = $lb_tixian_mod->get($tixian_id);
        /*检查是否在取消自己的申请*/
        if($data['user_id'] != $user_id){
            $this->show_warning(Lang::get('jinggao_qingbuyaochangshiquxiaobierendeshenqing'));
            return;
        }


        $this->_curitem(Lang::get('tixianrizhi'));

        $editdata['status'] = -1;
        $editdata['check_time'] = time();

        $lb_tixian_mod->edit('tixian_id='.$tixian_id,$editdata);

        $this->show_message(Lang::get('tixianquxiaoshenqingchenggong'));
        return;
    }

    /**
     * 作用:提现重新申请
     * Created by QQ:710932
     */
    function chongxinshenqing(){
        $tixian_id = $_GET['id'];

        $user_id = $this->visitor->get('user_id');
        $lb_tixian_mod = &m('lb_tixian');

        $data = $lb_tixian_mod->get($tixian_id);
        /*检查是否在取消自己的申请*/
        if($data['user_id'] != $user_id){
            $this->show_warning(Lang::get('jinggao_qingbuyaochangshiquxiaobierendeshenqing'));
            return;
        }


        $this->_curitem(Lang::get('tixianrizhi'));

        $editdata['status'] = 0;
        $editdata['check_time'] = time();

        $lb_tixian_mod->edit('tixian_id='.$tixian_id,$editdata);
        $this->show_message(Lang::get('tixianshenqingtijiaochenggong'));
        return;
    }
    /**
     * 作用:账户设置
     * Created by QQ:710932
     */
    function zhanghushezhi()
    {
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

    /**
     * @param $moneyAccount 一个会员的虚拟账户信息
     * 作用:检查银行信息是否为空,如果为空则返回TRUE 否则返回FLASE
     * Created by QQ:710932
     */
    private function empty_bank($moneyAccount)
    {
        if(empty($moneyAccount['bank_user']) or empty($moneyAccount['bank_card']) or empty($moneyAccount['bank_username'])){
            return false;
        }

        return true;
    }
}