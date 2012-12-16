<?php

class Task {

    private $tid;
    private $creatorId;
    private $creatorName;
    private $content;
    private $exp;
    private $date;
    private $isDone = false;

    function __construct($taskInfo) {
        $this->tid = $taskInfo['tid'];
        $this->creatorId = $taskInfo['createrid'];
        $this->content = $taskInfo['content'];
        $this->exp = $taskInfo['exp'];
        $this->date = $taskInfo['date'];
        $this->isDone = $taskInfo['isdone'];
        $this->creatorName = $taskInfo['creatorname'];
    }
    
    
    function getTid(){
        return $this->tid;
    }
    
    function getCreatorName(){
        return $this->creatorName;
    }

    function getCreaterId(){
        return $this->creatorId;
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
