<?php
	require_once ('core.class.php'); 
	require_once ('view.php');

	if(isset($_REQUEST['user']) && $_REQUEST['user']!=''){
		$user = $_REQUEST['user'];
		fun($user);
	}
	else
		echo '非法界面';
?>

<?php

	function fun($user){
		$core = new core();
		$res = $core->check_user($user);
		switch($res){
			case -1://新同学
				if(isset($_REQUEST['name']) && isset($_REQUEST['id'])){
					$result = $core->register($user,$_REQUEST['name'],$_REQUEST['id']);
					if($result == 1)
						//成功
						_href("./?user=".$user);
					else if ($result == 0)
						// echo '已报到';
						view_login($user,"已通过其他微信报到");
					else
						view_login($user,"不存在");
				}else
					view_login($user);
				break;
			case 1://已存在同学
				$result = $core->find_user($user);
				if($result['state'] == 1)
					view_register($result);
				else if($result['state'] == 2)
					view_confirm($result);
				else if($result['state'] == 3)
					view_success($result);
				break;
			case 0://管理员
			case 2://老师
			case 3://审核
				echo 'hello administrator';
				break;
		}
	}
	//1报到成功 2已通过其他微信报到 3不存在 4提示报到
	//11待确认 12已确认 13 完成
	//21审核成功 22状态不对 23用户不存在
	//31无权限
	function wx_fun($user,$parm=null){
		$core = new core();
		$res = $core->check_user($user);
		switch($res){
			case -1://新同学
				if(is_array($parm)){
					$result = $core->register($user,$parm['name'],$parm['id']);
					if($result == 1)
						//成功
						return 1;
					else if ($result == 0)
						// echo '已报到';
						return 2;
					else
						return 3;
				}else
					return 4;
				break;

			case 1://已存在同学
				$result = $core->find_user($user);
				if($result['state'] == 1)
					return 11;
				else if($result['state'] == 2)
					return 12;
				else if($result['state'] == 3)
					return 13;
				break;
			case 0://管理员
			case 2://老师
			case 3://审核
				$r = _act($parm['act'],$core,$res,$parm['no']);
					if(is_array($r)|| !is_numeric($r))
						return $r;
					else if($r == 100){
						return 31;
					}else if($r == 1){
						return 21;
					}else if($r == 0){
						return 22;
					}else if($r == -1){
						return 23;
					}
				
			break;
		}
	}
	function _act($act,$core,$role,$no=null){
		switch ($act) {
			case 'confirm'://审核
				if($role!=0 && $role!=3)
					break;
				$r = $core->confirm($no);
				return $r;//-1不存在 0状态不对 1成功
				break;
			
			case 'complete'://完成
				if($role!=0 && $role!=3)
					break;
				$r = $core->complete($no);
				return $r;//-1不存在 0状态不对 1成功`
				break;

			case 'count'://统计
				if($role!=0 && $role!=2)
					break;
				$r = $core->_count();
				return $r;
				break;

			case 'download'://下载
				if($role!=0 && $role!=2)
					break;
				return dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/excel.php?uin='.create_password(16);
				break;
		}
		return 100;
	}

	function create_password($len = 16, $keyword = '')
	{
	    $randpwd = '';
	        if (strlen($keyword) > $len) {//关键字不能比总长度长
	        return false;
	    }
	    $str = '';
	    $chars = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKMNPQRSTUVWXYZ'; //去掉1跟字母l防混淆            
	    if ($len > strlen($chars)) {//位数过长重复字符串一定次数
	        $chars = str_repeat($chars, ceil($len / strlen($chars)));
	    }
	    $chars = str_shuffle($chars); //打乱字符串
	    $str = substr($chars, 0, $len);
	    if (!empty($keyword)) {
	        $start = $len - strlen($keyword);
	        $str = substr_replace($str, $keyword, mt_rand(0, $start), strlen($keyword)); //从随机位置插入关键字
	    }
	    $randpwd = $str;

	    $fp = fopen("lock.txt", "w");//文件被清空后再写入 
		if($fp) 
		{ 
		     fwrite($fp,$randpwd); 
		}
		fclose($fp); 			
	    return $randpwd;
	}

	function _href($url){
		echo '<script language="javascript">';
		echo 'document.location="'.$url.'";';
		echo '</script>';
	}

?>