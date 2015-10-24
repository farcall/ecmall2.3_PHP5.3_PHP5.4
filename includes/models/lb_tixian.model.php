<?php
/**
 * Created by PhpStorm.
 * User: xiaokang
 * Date: 2015/10/21
 * Time: 3:39
 */

class Lb_tixianModel  extends BaseModel{
    /* 所映射的数据库表 */
    var $table = 'lb_tixian';

    /* 主键 */
    var $prikey= 'tixian_id';

    /* 模型的名称 */
    var $_name   = 'lb_tixian';

    /**
     * 订单号格式为年月日+5位随机数
     * @return  string
     */
    function build_tixian_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
} 