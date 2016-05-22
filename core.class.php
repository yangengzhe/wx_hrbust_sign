<?php
class core{
	private $user="";
	private $_service;

    public function __construct() {
		require_once ('service.class.php'); 
		$this->_service = new service();

		// $res = $this->_service->get_log('s');
		// echo $res['gmt_register'];
		// $this->_service->update_user('李四','0');
		// $this->_service->update_log_complete('s');
		// echo $this->_service->count_log(1);
	}

	//-1新同学 1已存在同学 >1非同学
	public function check_user($user){
		$result = $this->_service->check_user($user);
		if(is_array($result))
			return 1;
		else 
			return $result;
	}
	//新同学 报到 .-1 不存在 0已报到  1成功
	public function register($user,$name,$identity){
		$res = $this->_service->get_student($name,$identity);
		if($res>0){
			if($res['state']==1)
				return 0;
			$this->_service->add_user($user,1,1,$res['no']);
			$this->_service->add_log($res['no']);
		}else
			return -1;
		return 1;
	}
	//已存在同学 返回数组，包含信息和状态
	public function find_user($user){
		$res = $this->_service->check_user($user);
		$result = $this->_service->get_student_no($res['no']);
		$result['state'] = $res['state'];
		return $result;
	}
	//审核 确认 -1不存在 0状态不对 1成功
	public function confirm($no){
		$res = $this->_service->check_user_no($no);
		if($res == -1) return -1;
		if($res['state']==1){
			$this->_service->update_user($no,2);
			$this->_service->update_log_confirm($no);
			return 1;
		}
		return 0;
	}
	//审核 成功 -1不存在 0状态不对 1成功
	public function complete($no){
		$res = $this->_service->check_user_no($no);
		if($res == -1) return -1;
		if($res['state']==2){
			$this->_service->update_user($no,3);
			$this->_service->update_log_complete($no);
			return 1;
		}
		return 0;
		
	}
	//老师 签到人数 总人数/待确认/已确认/完成报到
	public function _count(){
		$un_register = $this->_service->count_student(0);
		$register = $this->_service->count_student(1);
		$total = $un_register + $register;
		$confirm = $this->_service->count_log(1);
		$complete = $this->_service->count_log(2);
		$array = array(
			"total" => $total,
			"register" => $register,
			"confirm" => $confirm,
			"complete" =>$complete
		);
		return $array;
	}
	//老师 报表下载

}
?>