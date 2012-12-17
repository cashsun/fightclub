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
    private $level=0;
    /**
     * array of Group(s) containing Task(s)
     */
    private $taskgroups;
    
    function __construct($userInfo) {	
        $this->uid = $userInfo['uid'];
        if(isset($userInfo['firstname']))
            $this->firstname = $userInfo['firstname'];
        if(isset($userInfo['lastname']))
            $this->lastname = $userInfo['lastname'];
        if(isset($userInfo['username']))
            $this->username = $userInfo['username'];
        if(isset($userInfo['email']))
            $this->email = $userInfo['email'];
        if(isset($userInfo['exp']))
            $this->exp = $userInfo['exp'];
        
        $this->taskgroups = $userInfo['taskgroups'];
        if(isset($userInfo['level']))
            $this->level = $userInfo['level'];
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
        return $this->lastname;
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
        return $this->taskgroups;
    }
}
?>
