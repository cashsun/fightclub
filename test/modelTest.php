<?php
include_once '../model/User.php';
include_once '../model/Task.php';

$taskInfo = array('tid'=>382,'createrid'=>12312,'content'=>'Get up at 7:00 tomorrow morning','exp'=>324,'date'=>'21-12-2012 23:45:21','isdone'=>false,'creatorname'=>'Cash Sun');

$cashtask=new Task($taskInfo);

echo '<h1>task</h1>';
echo 'TASK ID: '.$cashtask->getTid().'<br/>CONTENT: '.$cashtask->getContent().'  '.$cashtask->getDate().'<br/>';
echo ($cashtask->isDone()?'DONE?: true':'DONE?: false').'<br/>';
echo 'CREATOR: '.$cashtask->getCreatorName().'<br/>';
echo 'EXP: '.$cashtask->getExp();
?>
