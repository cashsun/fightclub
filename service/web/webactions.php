<?php
include_once '../../db/DBadapter.php';
include_once 'Actions.php';
if(isset($_POST['webaction'])){
    switch ($_POST['webaction']){
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
        case Actions::toggleTaskComplete:
            toggleTaskComplete();
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
    if(isset($_POST['tid'])&&isset($_POST['content'])&&isset($_POST['privacy'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->updateTask($_POST['tid'],$_POST['content'],$_POST['privacy']);
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
?>
