<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['ftype'])){
        $friends = getFriends($_SESSION['uid'], $_GET['ftype']);
    }
    if(!isset($friends[0])){
        echo '<br/><br/>Oops, no result.<br/><br/>T3T';
    }else{
        foreach($friends as $friend){
            echoFriend($friend);
        }
    }
    
function getFriends($uid,$ftype){
    $db = new DBadapter();
    $db->connect();
    switch($ftype){
    case 0:
        $result = $db->getFriends($uid);
        break;
    case 1:
        $result = $db->searchFriends($uid,$_GET['key']);
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
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div></div></div>';
}
function echoProfilePic($friend){
    echo '<img class="friend_image" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
?>
<script type="text/javascript" src="js/friends.js"></script>