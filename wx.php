<?php
  require_once('MyWechat.php');

  $wechat = new MyWechat(array(
      'token' => 'weixin',
      'aeskey' => 'X6PxNjxAMQAvgUXkVJ77bmERbKWKFociWI78aSHdzLk',
      'appid' => 'wx3084b898f1e50c78',
      'debug' => '44a6c855b7c59078c34bf1005988f054'
  ));
  $wechat->run();
?>