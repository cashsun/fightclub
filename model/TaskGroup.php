<?php
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
    private $date;
    
    function __construct($groupInfo) {	
        $this->gid = $groupInfo['gid'];
        $this->priority = $groupInfo['priority'];
        $this->title = $groupInfo['title'];
        $this->tasks = $groupInfo['tasks'];
        $this->tasks = $groupInfo['priority'];
        $this->date = $groupInfo['date'];
    }
}
?>
