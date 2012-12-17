<?php
include_once '../model/User.php';
include_once '../model/Task.php';
include_once '../model/TaskGroup.php';

$taskInfo = array('tid'=>382,'createrid'=>12312,'content'=>'Get up at 7:00 tomorrow morning','exp'=>324,'date'=>'21-12-2012 23:45:21','isdone'=>false,'creatorname'=>'Cash Sun');

$cashtask=new Task($taskInfo);
renderTask($cashtask);
//$db = new $DBadapter();
//$taskFromDb= new $taskFromDb($db->getTask(0,1));
//renderTask($taskFromDb);


$groupinfo = array('gid'=>1,'priority'=>5,'title'=>'Test List','task'=>$cashtask,'date'=>'21-12-2012 24:45:21','tasks'=>array($cashtask));
$group = new TaskGroup($groupinfo);
renderGroup($group);
//$GroupFromDb= new $taskFromDb($db->getTaskGroup(0));
//renderGroup($GroupFromDb);

function renderTask($cashtask){
    echo '<h1>Task</h1>';
    echo 'TASK ID: '.$cashtask->getTid().'<br/>CONTENT: '.$cashtask->getContent().'  <br/>'.$cashtask->getDate().'<br/>';
    echo ($cashtask->isDone()?'DONE?: true':'DONE?: false').'<br/>';
    echo 'CREATOR: '.$cashtask->getCreatorName().'<br/>';
    echo 'EXP: '.$cashtask->getExp();
    echo '<br/>';
}
function renderGroup($group){
    echo '<h1>Group</h1>';
    echo 'GROUP ID: '.$group->getGid().'<br/>TITLE: '.$group->getTitle().'  <br/>'.$group->getDate().'<br/>';
    $task = $group->getTasks();
    echo ''.$task[0]->getContent().'<br/>';
    echo 'Priority: '.$group->getPriority();
    echo '<br/>';
}
?>

