<?php
    session_start();
    include_once '../service/EventTypes.php';
    include_once '../db/DBadapter.php';
    if(isset($_SESSION['uid'])){
        $db=new DBadapter();
        $result = $db->getNewsByUid($_SESSION['uid']);
        
        while($row=mysql_fetch_array($result))
        {
          if(isset($row['eventtype'])){
              switch($row['eventtype']){
                  case EventTypes::COMMENT:
                      echoCommentEvent($row);
                      break;
                  case EventTypes::AT:break;
                  case EventTypes::FIGHTO: 
                      echoFightoEvent($row);
                      break;
                  case EventTypes::FOLLOW:
                      echoFollowEvent($row);
                      break;
                  case EventTypes::PUBLISH:
                      echoPublishEvent($row);
                      break;
                  case EventTypes::UPDATE_TASK:break;
                  case EventTypes::COMPLETE_TASK:
                      echoCompleteTaskEvent($row);
                      break;
                  case EventTypes::LEVEL_UP:break;
              }
          }else{
              echo -1;
          }
        }
        
    }else{
        echo -2;
    }
    
function echoCommentEvent($row){
    if($row['privacy']!=0){
    echo '<div class="news_box">';
        echo '<div class="news_title">';
            echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div style="color:black" class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> commented on '.$row['firstname2'].' '.$row['lastname2'].
                    '\'s task';
        echo '</div> ';
        echo '<div class="news_content">';
            echoTask($row);
            echoComment($row);
        echo '</div>';
    echo '</div>';
    }
}
function echoAtEvent($row){

}
function echoFightoEvent($row){
    echo '<div class="news_box">';
        echo '<div class="news_title">';
            echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div style="color:black" class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> <div class="news_fighto liked"></div> '.$row['firstname2'].' '.$row['lastname2'].
                    '\'s task';
        echo '</div> ';
        echo '<div class="news_content">';
                echo '<div class="news_comment_wapper">';
                    echo '<div class="comment_box">';
                        echo '<img uid='.$row['uid2'].' class="friend_image_s" src="image/'.$row['avatar2'].'.png" alt=""/>';
                        echo '<div class="f_username">'.$row['username2'].'</div>';
                        echo '<div class="f_fullname">'.$row['firstname2'].' '.$row['lastname2'].'</div>';
                    echo '</div>';
                echo '</div>';
            echoTask($row);
        echo '</div>';
        echo '<div class="news_tstamp">'.$row['tstamp'].'</div>';
    echo '</div>';
}
function echoFollowEvent($row){
    echo '<div class="news_box">';
        echo '<div class="news_title">';
            echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div style="color:black" class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> is now following ';
        echo '</div> ';
        echo '<div class="news_content">';
                echo '<div class="news_comment_wapper">';
                    echo '<div class="comment_box">';
                        echo '<img uid='.$row['uid2'].' class="friend_image_s" src="image/'.$row['avatar2'].'.png" alt=""/>';
                        echo '<div class="f_username">'.$row['username2'].'</div>';
                        echo '<div class="f_fullname">'.$row['firstname2'].' '.$row['lastname2'].'</div>';
                    echo '</div>';
                echo '</div>';
        echo '</div>';
        echo '<div class="news_tstamp">'.$row['tstamp'].'</div>';
    echo '</div>';
}
function echoPublishEvent($row){
    $isShow = true;
    if($row['privacy']==1&&!isset($row['fuid'])){
        if($row['uid1']!=$_SESSION['uid']){
            $isShow = false;
        }
    }
    if($isShow){
        echo '<div class="news_box">';
            echo '<div class="news_title">';
                echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div style="color:black" class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> published a new task!';
            echo '</div> ';
            echo '<div class="news_content">';
                echoTask($row);
            echo '</div>';
            echo '<div class="news_tstamp">'.$row['tstamp'].'</div>';
        echo '</div>';
    }
}
function echoUpdateEvent($row){

}
function echoCompleteTaskEvent($row){
    $isShow = true;
    if($row['privacy']==1){
        if(!isset($row['fuid'])){
            $isShow = false;
        }
    }
    if($isShow){
        echo '<div class="news_box">';
                echo '<div class="news_title">';
                    echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div style="color:black" class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> completed a task and won '.$row['texp'].' exp!';
                echo '</div> ';
                echo '<div class="news_content">';
                    echoTask($row);
                echo '</div>';
                echo '<div class="news_tstamp">'.$row['tstamp'].'</div>';
        echo '</div>';
    }
}
/* utility */
function echoComment($row){
    echo '<div class="news_comment_wapper">';
        echo '<div class="comment_box">';
        echo '<img uid='.$row['uid1'].' class="friend_image_s" src="image/'.$row['avatar1'].'.png" alt=""/>';
        echo '<div class="f_username">'.$row['username1'].'</div>';
        $str = htmlspecialchars(($row['ccontent']));
        echo '<div title="'.$str.'" class="comment_content">'.$str.'</div>';
        echo '<div class="comment_tstamp">'.$row['tstamp'].'</div>';
        if($row['uid1']==$_SESSION['uid'])
          echo '<div class="comment_delete" cid="'.$row['cid'].'"></div>';
        echo '</div>';
    echo '</div>';
}
function echoTask($row){
    $isliked = ' like';
    if($row['isliked']==1){
        $isliked = ' liked';
    }
    $str = htmlspecialchars($row['tcontent']);
    if($str == ''){
        $str = '*DELETED*';
    }
    echo '<div class="f_task roundcorner"><div tid="'.$row['tid'].'" class="comment_btn"></div><div class="f_task_text" title="'.$str.'">'.$str.'</div><div tid="'.$row['tid'].'" class="f_task_texp">'.$row['texp'].'</div><div tid="'.$row['tid'].'" class="fighto'.$isliked.'"></div></div>';
}
?>
<script type="text/javascript" src="js/news.js"></script>
