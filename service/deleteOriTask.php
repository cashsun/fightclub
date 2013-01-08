<?php
include_once '../db/DBadapter.php';
if(isset($_POST['tid'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->deleteOriTask($_POST['tid']);
    echo $result;
}
?>
