<?php
include_once '../db/DBadapter.php';
if(isset($_POST['uid'])&&isset($_POST['tgid'])&&isset($_POST['content'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->createTask($_POST['uid'],$_POST['tgid'],$_POST['content']);
    echo $result;
}
?>
