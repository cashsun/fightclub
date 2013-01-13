<?php
include_once '../../db/DBadapter.php';
if(isset($_GET['uid'])){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getFriends($_GET['uid']);
    echo json_encode($result);
}
?>