<?php

class Task {
    /*
     * PK
     */
    private $tid;
    private $uid;
    private $otid;
    private $creatorname='unknown';
    private $content='';
    private $texp=0;
    private $date='20-12-2012 23:00:00';
    private $isdone = 0;
    private $privacy = 0;

    function __construct($taskInfo) {
        $this->tid = $taskInfo['tid'];
        $this->uid = $taskInfo['uid'];
        if(isset($taskInfo['content']))
            $this->content = $taskInfo['content'];
        if(isset($taskInfo['texp']))
            $this->texp = $taskInfo['texp'];
        if(isset($taskInfo['date']))
            $this->date = $taskInfo['date'];
        if(isset($taskInfo['isdone']))
            $this->isdone = $taskInfo['isdone'];
        if(isset($taskInfo['creatorname']))
            $this->creatorname = $taskInfo['creatorname'];
        if(isset($taskInfo['otid']))
            $this->creatorname = $taskInfo['otid'];
        if(isset($taskInfo['privacy']))
            $this->privacy = $taskInfo['privacy'];
    }
    
    
    function getTid(){
        return $this->tid;
    }
    
    function getCreatorName(){
        return $this->creatorname;
    }

    function getCreatorId(){
        return $this->uid;
    }
    
    function getContent() {
        return $this->content;
    }
    
    function getTExp(){
        return $this->texp;
    }
    
    function getDate(){
        return $this->date;
    }
    
    function isDone(){
        return $this->isdone;
    }
    
    function getOtid(){
        return $this->otid;
    }
    
    function getPrivacy(){
        return $this->privacy;
    }
}
?>
