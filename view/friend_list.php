<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    $friend = null;
    if(isset($_SESSION['uid'])&&isset($_GET['fuid'])){
        $friend = getAllByFuid($_GET['fuid'],$_SESSION['uid']);
    }else{die(-1);}
    if($friend!=null && $friend->getUid()!=-1){
        echoFriend($friend);
        echo '<div id="f_group_wrapper">';
        
        echo '</div>';
    }else{
        echo -1;
    }
function echoFriend($friend){
    echo '<div fuid="'.$friend->getUid().'" class="friend_box">';
    echoProfilePic($friend);
    $isfriend = '<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="unfollow_friend"><img src="image/delfriend.png" alt=""/></button></span></div>';
    if(!$friend->isFriend()){
        $isfriend='<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="add_friend"><img src="image/addfriend.png" alt=""/></button></span></div>';
    }
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div><div class="f_exp">Exp '.$friend->getExp().'</div>'.$isfriend.'</div><div id="f_opt"><select class="f_toggle"><option>Tasks</option><option>Follows</option></select></div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image_l" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
function getAllByFuid($fuid,$uid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllByFuid($fuid,$uid);
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
