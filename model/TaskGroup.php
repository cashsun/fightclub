<?php
include_once '../db/DBadapter.php';
include_once '../db/Task.php';

class TaskGroup{
    public $gid;
    private $title;
    private $tasks;
    
    function __construct($gid,$groupInfo) {	
        $this->gid = $gid;
        
        $this->title = $groupInfo['title'];
        $this->tasks = $groupInfo['tasks'];
    }
}
?>
