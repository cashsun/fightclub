<?php
include_once '../db/DBadapter.php';
include_once '../model/User.php';
include_once '../model/Task.php';
include_once '../model/TaskGroup.php';

function getAllByUid($uid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllByUid($uid);
    $tasks = array();
    $groups = array();
    $user = new User(array());
    $t_counter=0;
    $g_counter=0;
    $current_tgid=-1;
    while($row =  mysql_fetch_row($result)){
        if($current_tgid == -1)
        {
            //first time access
            //copy user data
            $user = new User($row);
            
        }
        
        if($row['tgid']!= $current_tgid){
            $current_tgid = $row['tgid'];
            if($g_counter!=0)
              $groups[$g_counter].setTasks($tasks);
            $groups[++$g_counter] = new TaskGroup($row);
        }
        $tasks[$t_counter++] = new Task($row);
    }
    $user.setTaskGroups($groups);
    return $user;
}
?>
