<?php
include_once 'Task.php';

class TaskGroup{
    private $tgid;
    private $title='unknown';
    /**
     *  array of Task
     * @var Task
     */
    private $tasks;
    private $priority=0;
    private $date='20-12-2012 23:00:00';
    
    function __construct($groupInfo) {	
        $this->tgid = $groupInfo['tgid'];
        $this->tasks = $groupInfo['tasks'];
        
        if(isset($groupInfo['priority']))
            $this->priority = $groupInfo['priority'];
        if(isset($groupInfo['title']))
            $this->title = $groupInfo['title'];
        if(isset($groupInfo['date']))
            $this->date = $groupInfo['date'];
    }
    
    function getTgid(){
        return $this->tgid;
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
