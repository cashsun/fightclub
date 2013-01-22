<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['ftype'])){
        $friends = getFriends($_SESSION['uid'], $_GET['ftype']);
    }
    function getFriends($uid,$ftype){
        $db = new DBadapter();
        $db->connect();
        if($ftype==0){
            $result = $db->getFriends($uid);
        }else{
            
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
        echo '<div class="friend_box"><div class="f_username">'.$friend->getUsername().'</div><div class="f_fullname">'.$friend->getFirstname().' '.$friend->getLastname().'</div></div>';
    }
?>
<input type="text" class="roundcorner" maxlength="50" id="input_friend"/>
<div id="friends_radios">
    <input type="radio" class="radio" id="radio1" name="radio" checked="checked"/><label for="radio1">Friends</label>
    <input type="radio" class="radio" id="radio2" name="radio" /><label for="radio2">Follow</label>
    <input type="radio" class="radio" id="radio3" name="radio" /><label for="radio3">All</label>
</div>
<div id="friends_wrapper">
    <?php
        if($friends[0]->getUid()==-1){
            echo 'Oops,you don\'t have any friend<br/>T3T';
        }else{
            foreach($friends as $friend){
                echoFriend($friend);
            }
        }
    ?>
</div>
<script type="text/javascript" src="js/friends.js"></script>