<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['tgid'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->deleteTaskGroup($_POST['tgid']);
    echo $result;
}else{
    echo 0;
}
?>
