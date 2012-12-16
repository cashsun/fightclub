<?php
include_once '../db/DBadapter.php';
include_once '../db/Task.php';

class TaskGroup{
    public $gid;
    private $title;
    /**
     *  array of Task
     * @var Task
     */
    private $tasks;
    private $priority;
    
    function __construct($gid,$groupInfo) {	
        $this->gid = $gid;
        $this->priority = $groupInfo['priority'];
        $this->title = $groupInfo['title'];
        $this->tasks = $groupInfo['tasks'];
    }
}
?>
