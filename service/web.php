<?php
include_once '../db/DBadapter.php';
include_once '../model/User.php';
include_once '../model/Task.php';
include_once '../model/TaskGroup.php';

function getAllMyOriTasksByUid($uid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllMyOriTasks($uid);
    $tasks = array();
    $groups = array();
    $t_counter=0;
    $g_counter=0;
    $current_tgid=-1;
    while($row=  mysql_fetch_row($result)){
        if($row['tgid']!= $current_tgid){
            $current_tgid = $row['tgid'];
        }
        $tasks[$t_counter] = new Task($row);
        $t_counter++;
    }
    
}
?>
