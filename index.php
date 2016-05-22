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
			break;
			case 2://老师
			var_dump($core->_count()); 
			break;
			case 3://审核
				if(isset($_REQUEST['act'])){
					$r = _act($_REQUEST['act'],$core,3);
					if($r == 1){
						echo '成功';
					}else if($r == 0){
						echo '状态不对';
					}else if($r == -1){
						echo '用户不存在';
					}
				}
				
			break;
		}
	}
	function _act($act,$core,$role){
		switch ($act) {
			case 'confirm'://审核
				if($role!=0 && $role!=3)
					break;
				if(isset($_REQUEST['no'])){
					$r = $core->confirm($_REQUEST['no']);
					return $r;//-1不存在 0状态不对 1成功
				}
				break;
			
			case 'complete'://完成
				if($role!=0 && $role!=3)
					break;
				if(isset($_REQUEST['no'])){
					$r = $core->complete($_REQUEST['no']);
					return $r;//-1不存在 0状态不对 1成功
				}
				break;

			case 'count'://统计
				if($role!=0 || $role!=2)
					break;
				# code...
				break;

			case 'download'://下载
				if($role!=0 || $role!=2)
					break;
				# code...
				break;
		}
	}

	function _href($url){
		echo '<script language="javascript">';
		echo 'document.location="'.$url.'";';
		echo '</script>';
	}

?>