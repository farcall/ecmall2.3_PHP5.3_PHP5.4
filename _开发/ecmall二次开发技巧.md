###给定一定参数,读取数据库中一条语句###
```
1:创建模型 $model = & m(模型名称);
2:调用get方法
$data = $model->get("字段名称='值'");
```

###ecmall的分页机制###
```
    function user_rechargemoney_log(){
        //1:设置一页中元素数量
        $page = $this->_get_page(5);

        //2:'limit' => $page['limit'],'count' => true这两个限制条件必须存在
        $model = & m("lb_recharge_log");
        $data = $model->find(array(
            'order' => "add_time desc",
            'limit' => $page['limit'],
            'count' => true
        ));

        //3:获取查询总数
        $page['item_count'] = $model->getCount();
        //4:分页相关数据打包格式化
        $this->_format_page($page);
        
        //5:传输到模板
        $this->assign('page_info', $page);
        $this->assign("index",$data);
        $this->display("user_rechargemoney_log.html");
    }
```