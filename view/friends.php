<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['ftype'])){
        $friends = getList($_SESSION['uid'], $_GET['ftype']);
    }
    if(!isset($friends[0])){
        echo '<br/><br/><div style="font-size:0.75em;color:#8d8f90;margin-left:10px;text-align:center;width:200px">Oops, no result.<br/><br/>T3T</div>';
    }else{
        foreach($friends as $friend){
            echoFriend($friend);
        }
    }
    
function getList($uid,$ftype){
    $db = new DBadapter();
    $db->connect();
    switch($ftype){
    case 0:
        $result = $db->getMyFollows($uid);
        break;
    case 1:
        $result = $db->getMyFriends($uid);
        break;
    case 2:
        $result = $db->getMyFans($uid);
        break;
    case 3:
        $result = $db->searchUsers($uid,$_GET['key']);
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
    $isfriend = '<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="unfollow_friend">DEL</button></span></div>';
    if(!$friend->isFriend()){
        $isfriend='<div class="isfriend"><span><button uid="'.$friend->getUid().'" class="add_friend">ADD</button></span></div>';
    }
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div><div class="f_exp">Exp '.$friend->getExp().'</div>'.$isfriend.'</div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
?>
<script type="text/javascript" src="js/friends.js"></script>