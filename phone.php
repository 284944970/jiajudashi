<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/interface_init.php');
require(dirname(__FILE__) . '/../jpush/autoload.php');
spl_autoload_register('loader');
if(isset($_GET['r'])){
    $temp = $_GET['r'];
} else{
    $temp = $_GET['r'] = 'Index/def';
}

foreach ($_REQUEST as &$v){
    if($v=='#')
        return;
}

//去除伪静态后缀 .html
$temp = str_replace('.html','',$temp);
//获取控制器和其对应的方法
$arr = explode('/',$temp);
$controller = '';
$method = '';
foreach ($arr as $key => $value) {
    switch (true) {
        case $key==0:
            $controller = $value;
            break;
        case $key==1:
            $method = $value;
            break;
        case $key%2==0:
            $newkey = $value;
            break;
        case $key%2==1:
            $_GET[$newkey] = $value;
            break;
    }
}
unset($arr[0],$arr[1]);
/*if(empty($_SERVER['HTTP_USER_AGENT']))
    $userAgent = '';
else
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
$data = [
    'ip_addr'=>$_SERVER['REMOTE_ADDR'],
    'class_name'=>$controller,
    'method_name'=>$method,
    'server_str'=>base64_encode(json_encode($_SERVER)),
    'user_agent'=>$userAgent,
    'add_time'=>time()
];
$option = [
    'table'=>'sys_request'
];
M()->insert($data,$option);*/
$controller = new $controller;
//调用类方法并给该类方法传值
call_user_func_array([$controller, $method], $arr);
