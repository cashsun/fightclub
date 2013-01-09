<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['uid'])&&isset($_POST['title'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->createTaskGroup($_POST['uid'],$_POST['title']);
    echo $result;
}else{
    echo 0;
}
?>
