<?php
class service{
	private $_db;

    public function __construct() {
		require_once ('mysql.class.php'); 
		$this->_db = new mysql();
	}
	//查询user -1不存在用户 数组是同学 >=0是管理员
	public function check_user($user){
		$res = $this->_db->get_one('select role,state,no from user where user=\''.$user.'\'');
		if(!empty($res)){
    		if($res['role']==1)
    			return $res;
    		else return $res['role'];
		}
    	else
    		return -1;
	}
	public function check_user_no($no){
		$res = $this->_db->get_one('select state from user where no=\''.$no.'\'');
		if(!empty($res)){
    		return $res;
		}
    	else
    		return -1;
	}
	//增加user
	public function add_user($user,$role,$state=0,$no=''){
		$array = array(
			"user" => $user,
			"role" => $role,
			"state" => $state,
			"no" =>$no
		);
		$this->_db->insert("user",$array);

	}
	//更新user
	public function update_user($no,$state){
		$this->_db->query('update user set state='.$state.' where no=\''.$no.'\'');
	}

	//获取学生信息 -1不存在学生 数组是同学
	public function get_student($name,$identity){
		$res = $this->_db->get_one('select * from students where name=\''.$name.'\' and identity=\''.$identity.'\'');
		if(!empty($res)){
			//更新学生信息，已报到
			$this->_db->query('update students set state=1 where name=\''.$name.'\' and identity=\''.$identity.'\'');
    		return $res;
		}
    	else
    		return -1;
	}
	public function get_student_no($no){
		$res = $this->_db->get_one('select * from students where no=\''.$no.'\'');
		if(!empty($res)){
    		return $res;
		}
    	else
    		return -1;
	}
	//查询student数量
	public function count_student($state){
		$res = $this->_db->get_one('select count(*) as num from students where state='.$state);
		return $res["num"];
	}

	//增加log
	public function add_log($no){
		$array = array(
			"no" => $no,
			"gmt_register" => time()
		);
		$this->_db->insert("log",$array);
	}
	//查询log -1不存在 数组是信息
	public function get_log($no){
		$res = $this->_db->get_one('select * from log where no=\''.$no.'\'');
		if(!empty($res)){
    		return $res;
		}
    	else
    		return -1;
	}
	public function get_all_log(){
		$res = $this->_db->get_all('select * from log');
		if(!empty($res)){
    		return $res;
		}
    	else
    		return -1;
	}
	//更新log_confirm
	public function update_log_confirm($no){
		$this->_db->query('update log set gmt_confirm='.time().' where no=\''.$no.'\'');
	}
	//更新log_complete
	public function update_log_complete($no){
		$this->_db->query('update log set gmt_complete='.time().' where no=\''.$no.'\'');
	}
	
	//查询log数量 0待确认，1已确认，2已完成
	public function count_log($state){
		switch($state){
			case 0:
				$res = $this->_db->get_one('select count(*) as num from log');
				return $res["num"];
				break;
			case 1:
				$res = $this->_db->get_one('select count(*) as num from log where gmt_confirm IS NOT NULL');
				return $res["num"];
				break;
			case 2:
				$res = $this->_db->get_one('select count(*) as num from log where gmt_complete IS NOT NULL');
				return $res["num"];
				break;
		}
	}
}
?>