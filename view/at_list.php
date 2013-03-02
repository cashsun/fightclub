<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['key'])){
        $friends = getList($_SESSION['uid'],$_GET['key']);
    }else{
        echo -2;
    }
    if(!isset($friends[0])){
        echo '<br/><br/><div style="font-size:0.75em;color:#8d8f90;margin-left:10px;text-align:center;width:200px">Oops, no result.<br/><br/>T3T</div>';
    }else{
        foreach($friends as $friend){
            echoFriend($friend);
        }
    }
    
function getList($uid,$key){
    $db = new DBadapter();
    $db->connect();
    $result = $db->searchUsers($uid,$key);
    $friends = Array();
    $counter = 0;
    while($row =  mysql_fetch_array($result)){
        $friends[$counter]=new User($row);
        $counter++;
    }
    return $friends;
}
function echoFriend($friend){
    echo '<div class="at_friend_box">';
    echoProfilePic($friend);
    echo '<div class="f_info"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div></div></div>';
}
function echoProfilePic($friend){
    echo '<img uid='.$friend->getUid().' class="friend_image_ss" src="image/'.$friend->getAvatar().'.png" alt=""/>';
}
?>

<script type="text/javascript" src="js/at_list.js"></script>
