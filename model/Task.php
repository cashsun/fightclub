<?php

class Task {

    public $tid;
    private $creatorid;
    private $creatorName;
    private $content;
    private $exp;
    private $date;
    private $isDone = false;

    function __construct($tid, $createrid, $content, $exp, $date,$isDone) {
        $this->tid = $tid;
        $this->creatorid = $createrid;
        $this->content = $content;
        $this->exp = $exp;
        $this->date = $date;
        $this->isDone = $isDone;
    }
    
    /**
     * execute after each initialization. i.e. DB connection for task
     * @param type $creatorName 
     */
    function setCreatorName($creatorName){
        $this->creatorName = $creatorName;
    }
    
    function getCreatorName(){
        return $this->creatorName;
    }

    function getCreaterId(){
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
