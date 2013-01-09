<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['uid'])&&isset($_POST['tgid'])&&isset($_POST['content'])){
    $db = new DBadapter();
    $db->connect();
    if(!isset($_POST['otid']))
      $result = $db->createOriTask($_POST['uid'],$_POST['tgid'],$_POST['content']);
    else
      $result = $db->createOriTask($_POST['uid'],$_POST['otid'],$_POST['tgid'],$_POST['content']);
    echo $result;
}
?>
