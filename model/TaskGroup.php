<?php
include_once 'Task.php';

class TaskGroup{
    private $tgid = -1;
    private $title='';
    /**
     *  array of Task
     * @var Task
     */
    private $tasks = array();
    private $priority=0;
    private $date='20-12-2012 23:00:00';
    private $t_order = '';
    private $type = 0;
    
    function __construct($groupInfo) {
        if(isset($groupInfo['tgid']))
            $this->tgid = $groupInfo['tgid'];
        if(isset($groupInfo['tasks']))
            $this->tasks = $groupInfo['tasks'];
        if(isset($groupInfo['priority']))
            $this->priority = $groupInfo['priority'];
        if(isset($groupInfo['title']))
            $this->title = $groupInfo['title'];
        if(isset($groupInfo['date']))
            $this->date = $groupInfo['date'];
        if(isset($groupInfo['t_order']))
            $this->t_order = $groupInfo['t_order'];
        if(isset($groupInfo['type']))
            $this->type = $groupInfo['type'];
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
    
    function getTaskOrder(){
        return $this->t_order;
    }
    
    function getTaskByTid($tid){
        foreach($this->tasks as $task){
            if($task->getTid()==$tid){
                return $task;
            }
        }
        return null;
    }
    
    function getGType(){
        return $this->type;
    }
}
?>
