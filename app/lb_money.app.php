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
        header("Content-type: text/html; charset=utf-8");
    }
    /**
     * 作用:转账设置
     * Created by QQ:710932
     */
    function zhanghushezhi(){
        //todo 转账设置
        echo '转账设置';
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

}