<?php

class User{
    private $uid;
    private $firstname = '';
    private $lastname = '';
    private $username = '';
    private $exp = 0;
    private $email = '';
    /**
     *real time calculated
     */
    private $level;
    /**
     * array of Group(s) containing Task(s)
     */
    private $task_groups;
    
    function __construct($userInfo) {	
        $this->uid = $userInfo['uid'];
        $this->firstname = $userInfo['firstname'];
        $this->lastname = $userInfo['lastname'];
        $this->username = $userInfo['username'];
        $this->email = $userInfo['email'];
        $this->exp = $userInfo['exp'];
        $this->task_groups = $userInfo['task_groups'];
    }
    
    
    function setLevel($level){
        $this->level=$level;
    }
    
    function setExp($exp){
        $this->exp = $exp;
    }
    
    function getUid(){
        return $this->uid;
    }
    
    function getFirstname(){
        return $this->firstname;
    }

    function getLastname(){
        return $this->Lastname;
    }
    function getEmail(){
        return $this->email;
    }
    
    function getLevel(){
        return $this->level;
    }
    
    function getExp(){
        return $this->exp;
    }
    
    function getUsername(){
        return $this->username;
    }
    
    function getTaskGroups(){
        return $this->task_groups;
    }
}
?>