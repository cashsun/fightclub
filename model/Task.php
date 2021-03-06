<?php

class Task {
    private $tid = -1;
    private $uid = -1;
    private $otid;
    private $creatorname='unknown';
    private $content='';
    private $texp=0;
    private $tstamp='20-12-2012 23:00:00';
    private $isdone = 0;
    private $privacy = 0;
    private $deadline = '0000-00-00 00:00:00';
    private $isliked = 0;
    private $ccount = 0;
    
    function __construct($taskInfo) {
        if(isset($taskInfo['uid']))
            $this->uid = $taskInfo['uid'];
        if(isset($taskInfo['tid']))
            $this->tid = $taskInfo['tid'];
        if(isset($taskInfo['content']))
            $this->content = $taskInfo['content'];
        if(isset($taskInfo['texp']))
            $this->texp = $taskInfo['texp'];
        if(isset($taskInfo['tstamp']))
            $this->tstamp = $taskInfo['tstamp'];
        if(isset($taskInfo['isdone']))
            $this->isdone = $taskInfo['isdone'];
        if(isset($taskInfo['creatorname']))
            $this->creatorname = $taskInfo['creatorname'];
        if(isset($taskInfo['otid']))
            $this->otid = $taskInfo['otid'];
        if(isset($taskInfo['privacy']))
            $this->privacy = $taskInfo['privacy'];
        if(isset($taskInfo['deadline']))
            $this->deadline = $taskInfo['deadline'];
        if(isset($taskInfo['isliked']))
            $this->isliked = $taskInfo['isliked'];
        if(isset($taskInfo['ccount']))
            $this->ccount = $taskInfo['ccount'];
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
    
    function getTstamp(){
        return $this->tstamp;
    }
    
    function getDate(){
        $strArray = explode(' ', $this->deadline);
        return $strArray[0];
    }
    function getTime(){
        $strArray = explode(' ', $this->deadline);
        return $strArray[1];
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
    
    function isLiked(){
        return ($this->isliked==0)?false:true;
    }
    function getCcount() {
        return $this->ccount;
    }
}
?>
