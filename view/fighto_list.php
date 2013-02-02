<?php
session_start();
include_once '../db/DBadapter.php';
if(isset($_SESSION['uid'])&&isset($_GET['tid'])){
    $db = new DBadapter();
    $result = $db->getFightoList($_GET['tid']);
    while($row =  mysql_fetch_array($result)){
        echo '<img uid="'.$row['uid'].'" original-title="'.$row['firstname'].' '.$row['lastname'].'" class="friend_image_ss" src="image/'.$row['avatar'].'.png" alt=""/>';
    }
}else{
    echo -1;
}
?>
