<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['tid'])&&isset($_POST['content'])&&isset($_POST['privacy'])){
    $db = new DBadapter();
    $db->connect();
      $result = $db->updateTask($_POST['tid'],$_POST['content'],$_POST['privacy']);
    echo $result;
}
?>
