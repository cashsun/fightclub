<?php
include_once 'TaskGroup.php';
include_once 'Task.php';

class User{
    private $uid = -1;
    private $firstname = '';
    private $lastname = '';
    private $username = '';
    private $exp = 0;
    private $email = '';
    private $avatar = 0;
    private $isfriend = false;
    /**
     *real time calculated
     */
    private $level=0;
    /**
     * array of Group(s) containing Task(s)
     */
    private $taskgroups = array();
    
    function __construct($userInfo) {	
        if(isset($userInfo['uid']))
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
        if(isset($userInfo['level']))
            $this->level = $userInfo['level'];
        if(isset($userInfo['taskgroups']))
            $this->taskgroups = $userInfo['taskgroups'];
        if(isset($userInfo['avatar']))
            $this->avatar = $userInfo['avatar'];
        if(isset($userInfo['fuid']))
            $this->isfriend = true;
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
    
    function setTaskGroups($groups){
        $this->taskgroups = $groups;
    }
    
    function getAvatar(){
        return $this->avatar;
    }
    
    function isFriend(){
        return $this->isfriend;
    }
}
?>
