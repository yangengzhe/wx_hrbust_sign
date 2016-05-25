
<?php
error_reporting(0);
$fp = fopen("lock.txt", "r"); 
if(!$fp) 
     exit;
$file_uin = fgets($fp);
fclose($fp);
if($file_uin == '' || $file_uin == null)
     exit;
if(!isset($_REQUEST['uin']))
     exit;
if($file_uin != $_REQUEST['uin'])
     exit;

header("Content-type:application/vnd.ms-excel"); 
header("Content-Disposition:attachment;filename=hrbust_software.xls");
require_once ('mysql.class.php'); 
$db = new mysql();

$res = $db->get_all('select * from students , log where `students`.`no` = `log`.`no`;');
if(empty($res)) return -1;




// echo 'ID'.chr(9);
// echo  iconv('utf-8', 'gbk', '姓名').chr(9);
// echo '班级'.chr(9);
// echo '学号'.chr(9);
// echo '公寓'.chr(9);
// echo '寝室'.chr(9);
// echo '身份证'.chr(9);
// echo '状态'.chr(9);
// echo '报到时间'.chr(9);
// echo '确认时间'.chr(9);
// echo '入寝时间'.chr(9);
// echo chr(13);


?>


<html xmlns:o="urn:schemas-microsoft-com:office:office" 
 xmlns:x="urn:schemas-microsoft-com:office:excel" 
 xmlns="http://www.w3.org/TR/REC-html40"> 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 <html> 
     <head> 
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
         <style id="Classeur1_16681_Styles"></style> 
     </head> 
     <body> 
         <div id="Classeur1_16681" align=center x:publishsource="Excel"> 
             <table x:str border=1 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse"> 

             <tr>
               <td class=xl2216681 nowrap>姓名</td>
               <td class=xl2216681 nowrap>班级</td>
               <td class=xl2216681 nowrap>学号</td>
               <td class=xl2216681 nowrap>公寓</td>
               <td class=xl2216681 nowrap>寝室</td>
               <td class=xl2216681 nowrap>身份证</td>
               <td class=xl2216681 nowrap>状态</td>
               <td class=xl2216681 nowrap>报到时间</td>
               <td class=xl2216681 nowrap>确认时间</td>
               <td class=xl2216681 nowrap>入寝时间</td>
             </tr>

             <?php
               for($i=0;$i<count($res);$i++){
                    echo '<tr>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['name'].'</td>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['class'].'</td>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['no'].'</td>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['apartment'].'</td>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['room'].'</td>';
                    echo '<td class=xl2216681 nowrap>'.$res[$i]['identity'].'</td>';
                    if($res[$i]['state'] == '1')
                         echo '<td class=xl2216681 nowrap>已报到</td>';
                    else
                         echo '<td class=xl2216681 nowrap>未报到</td>';
                    echo '<td class=xl2216681 nowrap>'.date("Y-m-d H:i:s", $res[$i]['gmt_register']).'</td>';
                    echo '<td class=xl2216681 nowrap>'.date("Y-m-d H:i:s", $res[$i]['gmt_confirm']).'</td>';
                    echo '<td class=xl2216681 nowrap>'.date("Y-m-d H:i:s", $res[$i]['gmt_complete']).'</td>';
                    echo '</tr>';
               }
             ?>
                  
                
             </table> 
         </div> 
     </body> 
 </html> 

 <?php

     create_password();
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

?>
