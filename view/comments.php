<?php
    session_start();
    include_once('../db/DBadapter.php');
    include_once('../model/User.php');
    if(isset($_SESSION['uid'])&&isset($_GET['tid'])&&isset($_GET['lastcid'])){
        $db = new DBadapter();
        $db->connect();
        $result = $db->GetComments($_GET['tid'], $_GET['lastcid'], $_SESSION['uid']);
        $counter = 0;
        while($row =  mysql_fetch_array($result)){
            $counter++;
            echoComment($row);
        }
        echo '<div class="ccount hidden">'.$counter.'</div>';
    }else{
        echo -2;
    }
    function echoComment($row){
        echo '<div class="comment_box">';
        echo '<img uid='.$row['uid'].' class="friend_image_s" src="image/'.$row['avatar'].'.png" alt=""/>';
        echo '<div class="f_username">'.$row['username'].'</div>';
        $str = htmlspecialchars(($row['content']));
        echo '<div title="'.$str.'" class="comment_content">'.$str.'</div>';
        echo '<div class="comment_tstamp">'.$row['tstamp'].'</div>';
        if($row['uid']==$_SESSION['uid'])
          echo '<div class="comment_delete" cid="'.$row['commentid'].'"></div>';
        echo '</div>';
    }
?>
