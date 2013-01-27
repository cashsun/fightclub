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
        case Actions::createTask:
            createTask();
            break;
        case Actions::createTaskGroup:
            createTaskGroup();
            break;
        case Actions::deleteTask:
            deleteTask();
            break;
        case Actions::deleteTaskGroup:
            deleteTaskGroup();
            break;
        case Actions::updateTask:
            updateTask();
            break;
        case Actions::updateTaskGroup:
            updateTaskGroup();
            break;
        case Actions::getFriends:
            getFriends();
            break;
        case Actions::updateTaskGroupTaskOrder:
            updateTaskGroupTaskOrder();
            break;
        case Actions::toggleTaskComplete:
            toggleTaskComplete();
            break;
        case Actions::addFriend:
            addFriend();
            break;
        case Actions::unfollowFriend:
            unfollowFriend();
            break;
        case Actions::updateAvatar:
            updateAvatar();
            break;
        case Actions::fighto:
            fighto();
            break;
        case Actions::gettexpbytgid:
            getTexpbyTgid();
            break;
        case Actions::GET_USER_TASKS:
            getUserTasks();
            break;
        default :echo -1;
    }
}else{
    echo -1;
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
function getFriends(){
    if(isset($_POST['uid'])){
//        $db = new DBadapter();
//        $db->connect();
//        $result = $db->getFriends($_POST['uid']);
//        echo json_encode($result);
		
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
    if(isset($_POST['uid'], $_POST['fuid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->addFriend($_POST['uid'], $_POST['fuid']);
        echo $result;
    }else{
        echo -1;
    }
}
function unfollowFriend(){
    if(isset($_POST['uid'])&&isset($_POST['fuid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->unfollowFriend($_POST['uid'],$_POST['fuid']);
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
function getTexpbyTgid(){
    if(isset($_POST['tgid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->getTexpByTgid($_POST['tgid']);
        $jsonArray = Array();
        $counter = 0;
        while($row = mysql_fetch_assoc($result)){
            $jsonArray[$counter] = $row;
            $counter++;
        }
        echo json_encode($jsonArray);
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
    $isliked = ' like';
    if($task->isLiked()){
        $isliked = ' liked';
    }
    echo '<div class="f_task roundcorner"><div class="f_task_texp">'.$task->getTexp().'</div><div class="f_task_text">'.$task->getContent().'</div><div tid="'.$task->getTid().'" class="fighto'.$isliked.'"></div></div>';
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
