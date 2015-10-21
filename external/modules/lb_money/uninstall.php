<?php
/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/21
 * Time: 1:42
 */

$db=&db();
$db->query("DROP TABLE ".DB_PREFIX."lb_money");
$db->query("DROP TABLE ".DB_PREFIX."lb_line_order");
$db->query("DROP TABLE ".DB_PREFIX."lb_reward_log");
$db->query("DROP TABLE ".DB_PREFIX."lb_rewardrule_config");
$db->query("DROP TABLE ".DB_PREFIX."lb_tixian");
$db->query("DROP TABLE ".DB_PREFIX."lb_transfer_money_log");
$db->query("DROP TABLE ".DB_PREFIX."lb_recharge_log");

