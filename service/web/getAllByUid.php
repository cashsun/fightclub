<?php
function getAllByUid($uid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllByUid($uid);
    $tasks = array();
    $groups = array();
    $user;
    $last_row=array();
    $t_counter=0;
    $g_counter=0;
    $current_tgid=-1;
    while($row =  mysql_fetch_array($result)){
        if($row['tgid']!= $current_tgid){
            $current_tgid = $row['tgid'];
            if($t_counter!=0){
                $last_row['tasks']=$tasks;
                $groups[$g_counter]=new TaskGroup($last_row);
                $last_row = $row;
                $g_counter++;
                $t_counter = 0;
                unset($tasks);
            }
        }
        $row['creatorname'] = $row['firstname'].' '.$row['lastname'];
        $tasks[$t_counter]=new Task($row);
        $last_row = $row;
        $t_counter++;
    }
    $last_row['tasks']=$tasks;
    $groups[$g_counter]=new TaskGroup($last_row);
    $last_row['taskgroups'] = $groups;
    $user=new User($last_row);
    return $user;
}

?>
