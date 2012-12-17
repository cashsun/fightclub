<?php
include_once '../model/User.php';
include_once '../model/Task.php';
include_once '../model/TaskGroup.php';
include_once '../db/DBadapter.php';
include_once '../service/web.php';

$taskInfo = array('tid'=>382,'creatorid'=>12312,'content'=>'Get up at 7:00 tomorrow morning','exp'=>324,'date'=>'21-12-2012 23:45:21','isdone'=>false,'creatorname'=>'Cash Sun');

$cashtask=new Task($taskInfo);
renderTask($cashtask);
$db = new DBadapter();
$taskFromDb= new Task($db->getTask(1,true));
renderTask($taskFromDb);


$groupinfo = array('tgid'=>1,'priority'=>5,'title'=>'Test List','task'=>$cashtask,'date'=>'21-12-2012 24:45:21','tasks'=>array($cashtask));
$group = new TaskGroup($groupinfo);
renderGroup($group);
//$GroupFromDb= new $taskFromDb($db->getTaskGroup(1));
//renderGroup($GroupFromDb);

$userinfo = array('uid'=>1,'firstname'=>'Cash','lastname'=>'Sun','username'=>'mr.cashsun','email'=>'mr.cashsun@gmail.com','exp'=>320, 'level'=>30, 'taskgroups'=>array($group));
$user = new User($userinfo);
renderUser($user);

//$userFromDb = User(getAllMyOriTasksByUid(1));
//renderUser($userFromDb);

function renderTask(Task $cashtask){
    echo '<h1>Task</h1>';
    echo 'TASK ID: '.$cashtask->getTid().'<br/>CONTENT: '.$cashtask->getContent().'  <br/>'.$cashtask->getDate().'<br/>';
    echo ($cashtask->isDone()?'DONE?: true':'DONE?: false').'<br/>';
    echo 'CREATOR: '.$cashtask->getCreatorName().'<br/>';
    echo 'EXP: '.$cashtask->getExp();
    echo '<br/>';
}
function renderGroup(TaskGroup $group){
    echo '<h1>Group</h1>';
    echo 'GROUP ID: '.$group->getTgid().'<br/>TITLE: '.$group->getTitle().'  <br/>'.$group->getDate().'<br/>';
    $tasks = $group->getTasks();
    echo '<center>';
    foreach($tasks as $task){
        renderTask($task);
    }
    echo '</center>';
    echo ''.$tasks[0]->getContent().'<br/>';
    echo 'Priority: '.$group->getPriority();
    echo '<br/>';
}

function renderUser(User $user){
    echo '-------------------------------------------<br/>';
    echo '<h1>User</h1>';
    echo 'UID: '.$user->getUid().'<br/>';
    echo 'FIRST: '.$user->getFirstname().'<br/>';
    echo 'LAST: '.$user->getLastname().'<br/>';
    echo 'USERNAME: '.$user->getUsername().'<br/>';
    echo 'EMAIL: '.$user->getEmail().'<br/>';
    echo 'EXP: '.$user->getExp().'<br/>';
    echo 'LEVEL: '.$user->getLevel().'<br/>';
    $groups = $user->getTaskGroups();
    foreach($groups as $group){
        renderGroup($group);
    }
    echo '-------------------------------------------<br/>';
}

?>

