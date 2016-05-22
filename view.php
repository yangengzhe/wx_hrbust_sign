<?php
	function view_head(){
		echo '<!DOCTYPE html>';
		echo '<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->';
		echo '<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->';
		echo '<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->';
		echo '<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->';
		echo '<head><meta charset="utf-8">';
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		echo '<meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />';
		echo '<meta name="format-detection" content="telephone=no" />';
		echo '<title>软件学院</title>';
		echo '<link rel="stylesheet" href="css/style.css"><link rel="stylesheet" type="text/css" href="css/structure.css">';
		echo '</head>';
	}
	function view_body($style,$title,$content,$msg,$array){
		echo '<body><div style="text-align: center;">';
		echo '<div class="plan plan-highlight-'.$style.'">';
		echo '<p class="plan-recommended">'.$title.'</p>';
		echo '<h3 class="plan-title">'.$array["name"].'</h3>';
		echo '<p class="plan-price"><span class="plan-unit">学号</span>'.$array["no"].'</p>';
		echo '<ul class="plan-features">';
		echo '<li class="plan-feature"><span class="plan-unit">班级</span>'.$array["class"].'</li>';
		echo '<li class="plan-feature"><span class="plan-unit">公寓</span>'.$array["apartment"].'</li>';
        echo '<li class="plan-feature"><span class="plan-unit">寝室</span>'.$array["room"].'</li>';
      	echo '</ul><div class="plan-button">'.$content.'</div><span class="plan-unit">提示：'.$msg.'</span></div></div></body></html>';
	}
	function view_register($array){
		view_head();
		view_body("confirm","待确认","待信息确认","前往报到处确认",$array);
	}
	function view_confirm($array){
		view_head();
		view_body("login","已确认","待办理入寝","前往五公寓办理寝室入住",$array);
	}
	function view_success($array){
		view_head();
		view_body("success","已报到","已完成报到","已经完成报到",$array);
	}
	function view_login_body($user,$msg=''){
		echo '<script type="text/javascript">';
		echo 'function submit(){';
		echo 'document.getElementById("argform").submit();';
		echo '}</script>';

		echo '<body><div style="text-align: center;"><div class="plan plan-highlight-login"><p class="plan-recommended">新生报到</p><h3 class="plan-title">哈尔滨理工大学</h3><p class="plan-price">软件学院</p>';
		echo '<form class="box login" id="argform">';
		echo '<ul class="plan-features">';
		echo '<input type="hidden" name="user" value="'.$user.'">';
		echo '<li class="plan-feature"><span class="plan-unit">姓名</span><input type="text" name="name" placeholder="张三" class="login_text" required></li>';
		echo '<li class="plan-feature"><span class="plan-unit">身份证</span><input type="text" name="id" placeholder="230101xxxxxxxxxxxx" class="login_text" required></li>';
		echo '<input type="submit" class="plan-button" value="立即报到"/>';
		echo '</ul>';
		echo '</form>';
		if($msg!='')
      		echo '<span class="plan-unit">提示：'.$msg.'</span>';
      	echo ' </div></div></body></html>';
	}
	function view_login($user,$msg=''){
		view_head();
		view_login_body($user,$msg);
	}
?>