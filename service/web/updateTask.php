<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['tid'])&&isset($_POST['content'])){
    $db = new DBadapter();
    $db->connect();
      $result = $db->updateTask($_POST['tid'],$_POST['content']);
    echo $result;
}
?>
