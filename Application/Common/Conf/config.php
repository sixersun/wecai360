<?php
return array(

	'DB_TYPE' => 'mysql', //数据库类型
    'DB_PORT' => '3306', //端口

	'DB_DEPLOY_TYPE'	=> 1,
	'DB_RW_SEPARATE'	=> true,
	'DB_HOST'			=> '127.0.0.1', //服务器地址
	'DB_NAME'			=> 'wecai360', //数据库名
	'DB_USER'			=> 'wecai360',			//用户名
	'DB_PWD'			=> 'aNMYHWQSWTnr2QYD',		//密码
    // 设置默认模块
    'DEFAULT_MODULE' => 'Home',
    
    //设置禁止访问的模块列表
    'MODULE_DENY_LIST' => array('Common'),
    'ROLE_NAME'=>array(
    	1=>'管理员',
    	2=>'采购',
    	3=>'销售',
    	4=>'仓储',
    	5=>'财务',
    	6=>'其他'
    	),
);