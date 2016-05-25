<?php
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * @author NetPuter <netputer@gmail.com>
 */

  require('./wx/Wechat.php');
  require_once('./index.php');

  class MyWechat extends Wechat {
    private $sign = true;//true允许 false不允许管理权限
    private $key = 'hrbust';//秘钥
    private $emun_role = array('0' => '管理员','2' => '教师','3'=>'审核');

    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe() {
      $this->responseText('欢迎关注');
    }

    /**
     * 用户已关注时,扫描带参数二维码时触发，回复二维码的EventKey (测试帐号似乎不能触发)
     *
     * @return void
     */
    protected function onScan() {
      // $this->responseText('二维码的EventKey：' . $this->getRequest('EventKey'));
    }

    /**
     * 用户取消关注时触发
     *
     * @return void
     */
    protected function onUnsubscribe() {
      // 「悄悄的我走了，正如我悄悄的来；我挥一挥衣袖，不带走一片云彩。」
    }

    /**
     * 上报地理位置时触发,回复收到的地理位置
     *
     * @return void
     */
    protected function onEventLocation() {
      // $this->responseText('收到了位置推送：' . $this->getRequest('Latitude') . ',' . $this->getRequest('Longitude'));
    }

    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     *
     * @return void
     */
    protected function onText() {
      $user = $this->getRequest('FromUserName');
      $con = $this->getRequest('content');
      $result = '命令错误';
      switch ($con) {
        case '报到':
        case '新生报到':
          $res =wx_fun($user);
          if($res==4)
            $result = "回复 bd#姓名#身份证号 进行报到\n或点击<a href=\"http://www.7tool.cn/1/index.php?user=".$user."\">链接报到</a>";
          else if($res >10)
            $result = '已经报到，点击<a href="http://www.7tool.cn/1/index.php?user='.$user.'">查看状态</a>';
          break;
        
        default:
          $str_array = explode('#',$con);
          if(is_array($str_array) && $str_array[0] == 'bd'){
              if(count($str_array)>2 &&$str_array[1]!='' && $str_array[2]!='')
              {
                $parm = array('name' => $str_array[1],'id' => $str_array[2]);
                $res = wx_fun($user,$parm);
                if($res == 1) 
                  $result = '报到成功！点击<a href="http://www.7tool.cn/1/index.php?user='.$user.'">查看状态</a>';
                else if($res >10)
                  $result = '已经报到，点击<a href="http://www.7tool.cn/1/index.php?user='.$user.'">查看状态</a>';
                else if($res ==2)
                  $result = '已经通过其他设备进行报到';
                else if($res == 3)
                  $result = '信息有误！';
            }else{
              $result = '信息有误！';
            }
          }else if(is_numeric($con) && strlen($con)==10){
            $parm = array('act' => 'confirm','no' => $con);
            $res =wx_fun($user,$parm);
            if($res == 31)
              $result = '没有权限！';
            else if($res == 21)
              $result = '审核成功！';
            else if($res == 23)
              $result = '学生不存在！';
            else if($res == 22){
              $parm = array('act' => 'complete' , 'no' => $con);
              $res =wx_fun($user,$parm);
              if($res == 21)
                $result = '审核成功！';
              else if($res == 22)
                $result = '学生状态不对！';
            }
          }else if($con == '统计'){
            $parm = array('act' => 'count','no' => $con);
            $res =wx_fun($user,$parm);
            if($res == 31)
              $result = '没有权限！';
            else if(is_array($res)){
              $result = '总人数'.$res['total'].', 待确认'.$res['register'].', 已确认'.$res['confirm'].', 完成报到'.$res['complete'];
            }
          }else if($con == '下载'){
            $parm = array('act' => 'download','no' => $con);
            $res =wx_fun($user,$parm);
            if($res == 31)
              $result = '没有权限！';
            else if(!is_numeric($res)){
              $result = $res;
            }
          }else if($this->sign && is_array($str_array) && $str_array[0] == $this->key){
            $role = $str_array[1];
            $des_role = isset($this->emun_role[$role])?$this->emun_role[$role]:-1;
            if($des_role != -1){
              $res = wx_fun($user,'add_admin',$role);
              if($res == 1)
                $result = $des_role.'设置成功！';
              else 
                $result = '设置失败';
            }else
              $result = '权限命令错误';
          }
          break;
      }


        $this->responseText($result);
    }

    /**
     * 收到图片消息时触发，回复由收到的图片组成的图文消息
     *
     * @return void
     */
    protected function onImage() {
      // $items = array(
      //   new NewsResponseItem('标题一', '描述一', $this->getRequest('picurl'), $this->getRequest('picurl')),
      //   new NewsResponseItem('标题二', '描述二', $this->getRequest('picurl'), $this->getRequest('picurl')),
      // );

      // $this->responseNews($items);
    }

    /**
     * 收到地理位置消息时触发，回复收到的地理位置
     *
     * @return void
     */
    protected function onLocation() {
      // $num = 1 / 0;
      // 故意触发错误，用于演示调试功能

      // $this->responseText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
    }

    /**
     * 收到链接消息时触发，回复收到的链接地址
     *
     * @return void
     */
    protected function onLink() {
      // $this->responseText('收到了链接：' . $this->getRequest('url'));
    }

    /**
     * 收到语音消息时触发，回复语音识别结果(需要开通语音识别功能)
     *
     * @return void
     */
    protected function onVoice() {
      // $this->responseText('收到了语音消息,识别结果为：' . $this->getRequest('Recognition'));
    }

    /**
     * 收到自定义菜单消息时触发，回复菜单的EventKey
     *
     * @return void
     */
    protected function onClick() {
      $this->responseText('你点击了菜单：' . $this->getRequest('EventKey'));
    }

    /**
     * 收到未知类型消息时触发，回复收到的消息类型
     *
     * @return void
     */
    protected function onUnknown() {
      // $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }

  }
