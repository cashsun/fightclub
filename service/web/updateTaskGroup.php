<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['tgid'])&&isset($_POST['title'])&&isset($_POST['priority'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->updateTaskGroup($_POST['tgid'],$_POST['title'],$_POST['priority']);
    echo $result;
}else{
    echo -1;
}
?>
