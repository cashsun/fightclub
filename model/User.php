<?php
include_once '../db/DBadapter.php';
include_once 'TaskGroup.php';

class User{
    public $uid;
    private $firstname;
    private $lastname;
    private $username;
    private $exp;
    private $email;
    /**
     *real time calculated
     */
    private $level;
    /**
     * array of Group(s) containing Task(s)
     */
    private $task_groups;
    
    function __construct($uid,$firstname,$lastname,$username,$exp,$email,$task_groups) {	
        $this->uid = $uid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $username;
        $this->exp = $exp;
        $this->task_groups = $task_groups;
    }
    
    function setLevel($level){
        $this->level=$level;
    }
    
    function setExp($exp){
        $this->exp = $exp;
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
