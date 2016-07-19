<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<link rel="stylesheet" type="text/css" href="/static/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/static/css/app.min.css">
<body class=" pace-done" ui-class=""><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
<br/>
<br/>
<br/>
<br/>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->
<div class="app-body indigo bg-auto w-full">
  <div class="text-center pos-rlt p-y-md">
    <h1 class="text-shadow text-white text-4x">
      	<span class="text-2x font-bold block m-t-lg">
      		<?php if(isset($message)) {?>
      		<?php echo($message); ?>
			<?php }else{?>
			<?php echo($error); ?>
			<?php }?>
		</span>
    </h1>
    <p class="h5 m-y-lg text-u-c font-bold"><p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p></p>
  </div>
</div>

<!-- ############ LAYOUT END-->

  </div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>
