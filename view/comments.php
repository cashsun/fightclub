<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['tid'])&&isset($_GET['lastcid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->GetComments($_GET['tid'], $_GET['lastcid'], $_SESSION['uid']);
        while($row =  mysql_fetch_array($result)){
            echoComment($row);
        }
    }
    function echoComment($row){
        echo '<div class="comment_box">';
        
        echo '<img uid='.$row['uid'].' class="friend_image_s" src="image/'.$row['avatar'].'.png" alt=""/>';
        echo '<div class="f_username">'.$row['username'].'</div>';
        echo '<div title="'.$row['content'].'" class="comment_content">'.$row['content'].'</div>';
        echo '<div class="comment_tstamp">'.$row['tstamp'].'</div>';
        if($row['uid']==$_SESSION['uid'])
          echo '<div class="comment_delete" cid="'.$row['commentid'].'"></div>';
        echo '</div>';
    }
?>
