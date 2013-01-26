<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    $friend = null;
    if(isset($_SESSION['uid'])&&isset($_GET['fuid'])){
        $friend = getAllByFuid($_GET['fuid']);
    }else{die(-1);}
    if($friend!=null && $friend->getUid()!=-1){
        echoFriend($friend);
        $groups = $friend->getTaskGroups();
        if($groups[0]->getTgid()!=-1){
            echo '<div id="f_group_wrapper">';
            foreach($groups as $group){
                echoFriendGroup($group);
            }
            echo '</div>';
        }else{
            echo 'No task shared by this user.';
        }
    }else{
        echo -1;
    }
function echoFriendGroup($group){
    $tasks = $group->getTasks();
    if($tasks[0]->getTid()!=-1){
        echo '<div original-title="'.$group->getTitle().'" class="f_group">'.$group->getTitle().'</div>';
        foreach($tasks as $task){
            echoFriendTask($task);
        }
    }
}
function echoFriendTask($task){
    echo '<div class="f_task roundcorner"><div class="f_task_text">'.$task->getContent().'</div><div class="fighto like"></div></div>';
}
function echoFriend($friend){
    echo '<div class="friend_box">';
    echoProfilePic($friend);
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div><div class="f_exp">Exp '.$friend->getExp().'</div></div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image_l" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
function getAllByFuid($fuid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllByFuid($fuid);
    $tasks = array();
    $groups = array();
    $user=null;
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
<script type="text/javascript" src="js/friend_list.js"></script>
