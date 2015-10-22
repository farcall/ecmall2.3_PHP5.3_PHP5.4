<?php
/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/21
 * Time: 3:35
 */

class Lb_moneyModel extends BaseModel {
    /* 所映射的数据库表 */
    var $table = 'lb_money';
    /* 主键 */
    var $prikey = 'user_id';
    /* 模型的名称 */
    var $_name = 'lb_money';

    /**
     * @static* @param $user_id
     * 虚拟账户初始化
     * 给定一个创建账户的限制条件，为该用户创建账户
     * Created by QQ:710932
     */
    static function createaccountByid($user_id)
    {

        $db = &db();
        $lb_money_row = $db->getAll("select * from ".DB_PREFIX."lb_money where user_id='$user_id'");
        if(empty($lb_money_row)){
            $member_row = $db->getrow("select * from ".DB_PREFIX."member where user_id='$user_id'");
            $lb_money_mod = & m('lb_money');
            $ntime = time();
            $money_data = array(
                'user_id'=>$member_row['user_id'],
                'user_name'=>$member_row['user_name'],
                'pass_pay' => $member_row['password'],

                'add_time' => $ntime,
                'modify_time'=> $ntime,

            );

            $lb_money_mod->add($money_data);
        }
    }

    static function createaccountByName($user_name)
    {
        $db = &db();
        $lb_money_row = $db->getAll("select * from ".DB_PREFIX."lb_money where user_name='$user_name'");
        if(empty($lb_money_row)){
            $member_row = $db->getrow("select * from ".DB_PREFIX."member where user_name='$user_name'");
            $lb_money_mod = & m('lb_money');
            $ntime = time();
            $money_data = array(
                'user_id'=>$member_row['user_id'],
                'user_name'=>$member_row['user_name'],
                'pass_pay' => $member_row['password'],

                'add_time' => $ntime,
                'modify_time'=> $ntime,

            );

            $lb_money_mod->add($money_data);
        }
    }
} 