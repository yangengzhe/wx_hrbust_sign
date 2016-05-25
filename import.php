<?php
error_reporting(0);
if(!empty($_FILES['myfile']['tmp_name'])){
	$row = 0;
	$handle = fopen($_FILES['myfile']['tmp_name'],"r");
	$sql = 'INSERT INTO students (name, class,no,apartment,room,identity) VALUES ';

	//fgetcsv() 解析读入的行并找出 CSV格式的字段然后返回一个包含这些字段的数组。 
	while ($data = fgetcsv($handle, 1000, ",")) {
		$row++;
		if($row == 1) continue;
	    $num = count($data);
	    if($row != 2)
	    	$sql = $sql.',';
	    $sql = $sql.'(';
	    for ($c=0; $c < $num; $c++) {
			//注意中文乱码问题
			$data[$c]=iconv("gbk", "utf-8//IGNORE",$data[$c]);  
	        $sql = $sql."'".$data[$c]."'";
	        if($c != $num-1)
	        	$sql = $sql.',';
	    }
	    $sql = $sql.')';
	}
	fclose($handle);
	// echo $sql;
	require_once ('mysql.class.php'); 
	$db = new mysql();
	if($db->inserts($sql)) echo '导入成功';
	else echo '导入失败';
	exit;
}else{
	echo'请选择文件';
}
?> 
<form enctype="multipart/form-data" action="?" method="post"> 
<p>导入cvs数据 <input name="myfile" id="myfile" type="file"> <input id="send" value="提交" type="submit"> 
</p> 
</form>

<script>
var send=document.getElementById("send");
send.onclick=function(){
	var file=document.getElementById("myfile").value;

	if(file=='' || file.length<1)
	{
		alert('请选择要上传的csv文件');
		return false;
	}
	var result =/\.[^\.]+/.exec(file);
	if(result !='.csv'){
		alert('请选择文件格式为csv的文件');
		return false;
	}
}
</script>