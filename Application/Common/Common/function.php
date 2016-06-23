<?php
//判断是否登录
function islogin(){
  $user=session('user');
  if(!$user) gologin();
}

//跳转登录页面
function gologin(){
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: /home/login');
	exit;
}
function ajax($status = null, $msg = null, $data = null) {
    $arr['status'] = $status;
    $arr['data'] = $data;
    $arr['info'] = $msg;
    echo _json($arr);
    exit;
}

function _json($arr) {
    if (is_array($arr))
        return json_encode($arr);
}