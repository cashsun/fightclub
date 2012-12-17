<?php

class Task {
    /*
     * PK
     */
    private $tid;
    private $creatorid;
    
    private $creatorName='unknown';
    private $content='';
    private $exp=0;
    private $date='20-12-2012 23:00:00';
    private $isDone = false;

    function __construct($taskInfo) {
        $this->tid = $taskInfo['tid'];
        $this->creatorid = $taskInfo['creatorid'];
        if(isset($taskInfo['content']))
            $this->content = $taskInfo['content'];
        if(isset($taskInfo['exp']))
            $this->exp = $taskInfo['exp'];
        if(isset($taskInfo['date']))
            $this->date = $taskInfo['date'];
        if(isset($taskInfo['isdone']))
            $this->isDone = $taskInfo['isdone'];
        if(isset($taskInfo['creatorname']))
            $this->creatorName = $taskInfo['creatorname'];
    }
    
    
    function getTid(){
        return $this->tid;
    }
    
    function getCreatorName(){
        return $this->creatorName;
    }

    function getCreatorId(){
        return $this->creatorid;
    }
    
    function getContent() {
        return $this->content;
    }
    
    function getExp(){
        return $this->exp;
    }
    
    function getDate(){
        return $this->date;
    }
    
    function isDone(){
        return $this->isDone;
    }

}

?>
