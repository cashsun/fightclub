<?php
session_start();
include_once '../../db/DBadapter.php';
include_once '../Actions.php';
include_once '../../model/User.php';
if(isset($_SESSION['uid'])){
    $action = -1;
    if(isset($_POST['webaction'])){
        $action= $_POST['webaction'];
    }
    if(isset($_GET['webaction'])){
        $action= $_GET['webaction'];
    }
    switch ($action){
        case Actions::CREATE_TASK:
            createTask();
            break;
        case Actions::CREATE_TASK_GROUP:
            createTaskGroup();
            break;
        case Actions::DELETE_TASK:
            deleteTask();
            break;
        case Actions::DELETE_TASK_GROUP:
            deleteTaskGroup();
            break;
        case Actions::UPDATE_TASK:
            updateTask();
            break;
        case Actions::UPDATE_TASK_GROUP:
            updateTaskGroup();
            break;
        case Actions::GET_FRIENDS:
            getFriends();
            break;
        case Actions::UPDATE_TASK_GROUP_TASK_ORDER:
            updateTaskGroupTaskOrder();
            break;
        case Actions::TOOGLE_TASK_COMPLETE:
            toggleTaskComplete();
            break;
        case Actions::ADD_FRIEND:
            addFriend();
            break;
        case Actions::UNFOLLOW_FRIEND:
            unfollowFriend();
            break;
        case Actions::UPDATE_AVATAR:
            updateAvatar();
            break;
        case Actions::FIGHTO:
            fighto();
            break;
        case Actions::GET_USER_TASKS:
            getUserTasks();
            break;
        case Actions::CREATE_COMMENT:
            createComment();
            break;
        case Actions::DELETE_COMMENT:
            deleteComment();
            break;
        case Actions::GET_FRIEND_FOLLOWS:
            getFriendFollows();
            break;
        case Actions::GET_COMMENTS:
            getComments();
            break;
        case Actions::GET_FIGHTO_LIST:
            getFightoList();
            break;
        case Actions::GET_ALERM:
            getAlarmByUid();
            break;
        default :echo -1;
    }
}else{
    echo -2;
}

function createTask(){
    if(isset($_POST['uid'])&&isset($_POST['tgid'])&&isset($_POST['content'])){
        $db = new DBadapter();
        $db->connect();
        if(!isset($_POST['otid']))
        $result = $db->createOriTask($_POST['uid'],$_POST['tgid'],$_POST['content']);
        else
        $result = $db->createOriTask($_POST['uid'],$_POST['otid'],$_POST['tgid'],$_POST['content']);
        echo $result;
    }
}
function createTaskGroup(){
    if(isset($_POST['uid'])&&isset($_POST['title'])&&isset($_POST['priority'])&&isset($_POST['type'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->createTaskGroup($_POST['uid'],$_POST['title'],$_POST['priority'], $_POST['type']);
        echo $result;
    }
}
function deleteTask(){
    if(isset($_POST['tid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->deleteTask($_POST['tid']);
        echo $result;
    }
}
function deleteTaskGroup(){
    if(isset($_POST['tgid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->deleteTaskGroup($_POST['tgid']);
        echo $result;
    }   
}
function updateTask(){
    if(isset($_POST['tid'])&&isset($_POST['content'])&&isset($_POST['privacy'])&&isset($_POST['deadline'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->updateTask($_POST['tid'],$_POST['content'],$_POST['privacy'], $_POST['deadline']);
        echo $result;
    }
}
function updateTaskGroup(){
    if(isset($_POST['tgid'])&&isset($_POST['title'])&&isset($_POST['priority'])&&isset($_POST['type'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->updateTaskGroup($_POST['tgid'],$_POST['title'],$_POST['priority'], $_POST['type']);
        echo $result;
    }
}
function updateTaskGroupTaskOrder(){
    if(isset($_POST['tgid'])&&isset($_POST['t_order'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->updateTaskGroupTaskOrder($_POST['tgid'],$_POST['t_order']);
        echo $result;
    }
}
function toggleTaskComplete(){
    if(isset($_POST['tid'], $_POST['isdone'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->toggleTaskComplete($_POST['tid'], $_POST['isdone']);
        echo $result;
    }
}
function addFriend(){
    if(isset($_SESSION['uid'], $_POST['fuid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->addFriend($_SESSION['uid'], $_POST['fuid']);
        echo $result;
    }else{
        echo -1;
    }
}
function unfollowFriend(){
    if(isset($_SESSION['uid'])&&isset($_POST['fuid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->unfollowFriend($_SESSION['uid'],$_POST['fuid']);
        echo $result;
    }
}
function updateAvatar(){
    if(isset($_POST['uid'])&&isset($_POST['avatar'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->updateAvatar($_POST['uid'],$_POST['avatar']);
        echo $result;
    }
}

function fighto(){
    if(isset($_POST['uid'])&&isset($_POST['tid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->fighto($_POST['uid'],$_POST['tid']);
        echo $result;
    }
}

function getUserTasks(){
    if(isset($_GET['fuid'])){
        $user = getAllByFuid($_GET['fuid'],$_SESSION['uid']);
        $groups = $user->getTaskGroups();
        if($groups[0]->getTgid()!=-1){
            foreach($groups as $group){
                echoFriendGroup($group);
            }
        }
    }
}
function createComment(){
    if(isset($_POST['tid'])&&isset($_POST['content'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->CreateComment($_SESSION['uid'], $_POST['tid'], $_POST['content']);
        echo $result;
    }
}
function deleteComment(){
    if(isset($_POST['commentid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->DeleteComment($_POST['commentid']);
        echo $result;
    }
}

function getComments(){
    if(isset($_POST['tid'])&&isset($_POST['lastcid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->GetComments($_POST['tid'], $_POST['lastcid'], $_SESSION['uid']);
        echo $result;
    }
}


function getFriendFollows(){
    if(isset($_GET['fuid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->getMyFollows($_GET['fuid']);
        $friends = Array();
        $counter = 0;
        while($row =  mysql_fetch_array($result)){
            $friends[$counter]=new User($row);
            $counter++;
        }
        if(!isset($friends[0])){
        echo '<br/><br/><div style="font-size:0.75em;color:#8d8f90;margin-left:10px;text-align:center;width:200px">Oops, no result.<br/><br/>T3T</div>';
        }else{
            foreach($friends as $friend){
                echoFriend($friend);
            }
        }
    }
}
/* utility functions */
function echoFriendGroup($group){
    $tasks = $group->getTasks();
    if($tasks[0]->getTid()!=-1){
        echo '<div original-title="'.$group->getTitle().'" class="f_group">'.$group->getTitle().'</div>';
        foreach($tasks as $task){
            echoFriendTask($task);
        }
    }
}
function echoFriendTask(Task $task){
    $isliked = ' like';
    if($task->isLiked()){
        $isliked = ' liked';
    }
    $str = htmlspecialchars($task->getContent());
    echo '<div class="f_task roundcorner"><div tid="'.$task->getTid().'" class="comment_btn"></div><div class="f_task_text" title="'.$str.'">'.$str.'</div><div tid="'.$task->getTid().'" class="f_task_texp">'.$task->getTexp().'</div><div tid="'.$task->getTid().'" class="fighto'.$isliked.'"></div></div>';
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
function getAlarmByUid(){
    
}
function echoFriend($friend){
    echo '<div class="friend_box">';
    echoProfilePic($friend);
    $isfriend = '<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="unfollow_friend"><img src="image/delfriend.png" alt=""/></button></span></div>';
    if(!$friend->isFriend()){
        $isfriend='<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="add_friend"><img src="image/addfriend.png" alt=""/></button></span></div>';
    }
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div><div class="f_exp">Exp '.$friend->getExp().'</div>'.$isfriend.'</div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
?>
