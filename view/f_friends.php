<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['ftype'])&&isset($_GET['fuid'])){
        $friends = getList($_GET['fuid'], $_GET['ftype']);
    }
    if(!isset($friends[0])){
        echo '<br/><br/><div style="text-align:center;width:200px">Oops, no result.<br/><br/>T3T</div>';
    }else{
        foreach($friends as $friend){
            echoFriend($friend);
        }
    }
    
function getList($fuid,$ftype){
    $db = new DBadapter();
    $db->connect();
    switch($ftype){
    case 0:
        $result = $db->getUserFollows($fuid, $_SESSION['uid']);
        break;
    case 1:
        
        break;
    case 2:
        
        break;
    default:
        break;
    }
    $friends = Array();
    $counter = 0;
    while($row =  mysql_fetch_array($result)){
        $friends[$counter]=new User($row);
        $counter++;
    }
    return $friends;
}  
function echoFriend($friend){
    echo '<div class="friend_box">';
    echoProfilePic($friend);
    $isfriend='';
    if($friend->getUid()!=$_SESSION['uid']){
        if(!$friend->isFriend()){
            $isfriend='<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="add_friend"><img src="image/addfriend.png" alt=""/></button></span></div>';
        }else{
            $isfriend = '<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="unfollow_friend"><img src="image/delfriend.png" alt=""/></button></span></div>';
        }
    }
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div><div class="f_exp">Exp '.$friend->getExp().'</div>'.$isfriend.'</div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
?>
<script type="text/javascript" src="js/f_friends.js"></script>

