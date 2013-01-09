<?php
include_once '../../db/DBadapter.php';
if(isset($_POST['tid'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->deleteTask($_POST['tid']);
    echo $result;
}
?>
