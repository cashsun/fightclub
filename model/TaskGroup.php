<?php
include_once 'Task.php';

class TaskGroup{
    private $gid;
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
        $this->date = $groupInfo['date'];
    }
    
    function getGid(){
        return $this->gid;
    }


    function getTitle(){
        return $this->title;
    }
    
    function getPriority(){
        return $this->priority;
    }
    
    function getDate(){
        return $this->date;
    }
    
    function getTasks(){
        return $this->tasks;
    }
}
?>
