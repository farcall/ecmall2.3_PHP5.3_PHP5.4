<?php
/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/21
 * Time: 1:41
 */
$filename = ROOT_PATH . '/data/datacall.inc.php';
file_put_contents($filename, "<?php return array(); ?>");
$db=&db();
//lb_money
$db->query("CREATE TABLE `".DB_PREFIX."lb_money` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `pass_pay` varchar(255) NOT NULL DEFAULT '123456' COMMENT '支付密码默认123456',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '历史消费总额',
  `money_used` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可用资金',
  `money_freeze` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结资金',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `modify_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `bank_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用于提现的银行名称',
  `bank_card` varchar(32) NOT NULL DEFAULT '' COMMENT '用于提现的银行卡编号',
  `bank_username` varchar(32) NOT NULL DEFAULT '' COMMENT '用于提现的银行持有人姓名',
    `bank_address` varchar(255) NOT NULL DEFAULT '' COMMENT '所在地区,非银行可为空',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='虚拟金币表';");
//lb_line_order
$db->query("CREATE TABLE `".DB_PREFIX."lb_line_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID主键',
  `buy_id` int(10) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `buy_name` varchar(60) NOT NULL DEFAULT '' COMMENT '买家名称',
  `buy_tel` varchar(60) NOT NULL DEFAULT '' COMMENT '买家电话',
  `sell_id` int(10) NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `sell_name` varchar(255) NOT NULL DEFAULT '' COMMENT '卖（商）家名称',
  `sell_shoper` varchar(255) NOT NULL DEFAULT '' COMMENT '经手人名称',
  `sell_tel` varchar(60) NOT NULL DEFAULT '' COMMENT '卖家联系方式',
  `product_name` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名称',
  `product_money` varchar(255) NOT NULL DEFAULT '' COMMENT '产品单价',
  `total_money` varchar(255) NOT NULL DEFAULT '' COMMENT '消费金额',
  `order_time` int(10) NOT NULL DEFAULT '0' COMMENT '消费时间',
  `add_time` varchar(255) NOT NULL DEFAULT '' COMMENT '订单提交时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:卖家提交1;管理员审核通过2;管理员审核拒绝0',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='线下消费单据';
");
//lb_reward_log
$db->query("CREATE TABLE `".DB_PREFIX."lb_reward_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `reward_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '奖励序列号',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '奖励用户名称',
  `reward_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '奖励金额',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '奖励发放时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奖励(返现)日志';
");
//lb_rewardrule_config
$db->query("CREATE TABLE `".DB_PREFIX."lb_rewardrule_config` (
  `deal_payoff_rate` tinyint(3) NOT NULL DEFAULT '10' COMMENT '交易抽成比例;平台从每笔交易中抽取的比例',
  `profit_assign_rate` tinyint(3) NOT NULL DEFAULT '50' COMMENT '从每日的平台收入中用于分配的比例',
  `reward_rate` tinyint(3) NOT NULL DEFAULT '60' COMMENT '将用户历史消费以该百分比于不确定时间内返还给客户',
  `reward_time` int(10) NOT NULL DEFAULT '0' COMMENT '每天奖励(返现)时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奖励规则（管理员操作）';
");
//lb_recharge_log
$db->query("CREATE TABLE `".DB_PREFIX."lb_recharge_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID 主键',
  `recharge_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '充值序列号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `recharge_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '转账额度',
  `recharge_method` varchar(100) NOT NULL DEFAULT '' COMMENT '充值方式(支付宝,管理员充值）',
  `recharge_code` varchar(20) NOT NULL DEFAULT '' COMMENT '充值编码',
  `recharge_message` varchar(255) NOT NULL DEFAULT '' COMMENT '充值消息',
  `admin_name` varchar(100) NOT NULL DEFAULT '' COMMENT '充值经手人(管理员)',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '充值时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值记录表';
");
//lb_tixian
$db->query("CREATE TABLE `".DB_PREFIX."lb_tixian` (
  `tixian_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '提现表主键',
  `tixian_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '系统生成的提现号',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '申请提现用户ID',
  `user_name` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '申请提现用户',
  `bank_name` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行名称(比如工商银行,支付宝)',
  `bank_card` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡号码',
  `bank_username` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行开户名称',
  `remark` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请提现时间',
  `check_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核提现时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态(体现中0;取消提现-1;提现成功1)',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '提现金额',
  PRIMARY KEY (`tixian_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf32 COMMENT='提现申请表';
");
//lb_transfer_money_log
$db->query("CREATE TABLE `".DB_PREFIX."lb_transfer_money_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志ID主键',
  `transfer_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '系统生成转账订单号',
  `startuser_id` int(10) NOT NULL DEFAULT '0' COMMENT '转账发起人ID',
  `startuser_name` varchar(60) NOT NULL DEFAULT '' COMMENT '转账发起人名称',
  `enduser_id` int(10) NOT NULL DEFAULT '0' COMMENT '转账接受人ID',
  `enduser_name` varchar(60) NOT NULL DEFAULT '' COMMENT '转账接收人名称',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '转账金额',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '转账时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '转账状态:成功1;失败0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='转账日志表';
");