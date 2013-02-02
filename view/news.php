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
                  case EventTypes::FIGHTO: break;
                  case EventTypes::PUBLISH:break;
                  case EventTypes::UPDATE_TASK:break;
                  case EventTypes::COMPLETE_TASK:break;
                  case EventTypes::LEVEL_UP:break;
              }
          }else{
              echo -1;
          }
        }
        
    }else{
        echo -1;
    }
    
function echoCommentEvent($row){
    echo '<div class="news_box">';
        echo '<div class="news_title">';
            echo '<img uid="'.$row['uid1'].'" class="friend_image_ss" src="image/'.$row['avatar1'].'.png" alt=""/><div class="f_fullname">'.$row['firstname1'].' '.$row['lastname1'].'</div> commented on '.$row['firstname2'].' '.$row['lastname2'].
                    '\'s task';
        echo '</div> ';
        echo '<div class="news_content">';
            echo '<div class="f_task roundcorner"><div class="f_task_text">'.$row['tcontent'].'</div></div>';
            echoComment($row);
        echo '</div>';
    echo '</div>';
}
function echoAtEvent($row){
    
}
function echoFightoEvent($row){
    
}
function echoPublishEvent($row){
    
}
function echoUpdateEvent($row){
    
}
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
    $isliked = '';
    if($row['isliked']==1){
        $isliked = ' liked';
    }
    $str = htmlspecialchars($row['tcontent']);
    echo '<div class="f_task roundcorner"><div tid="'.$row['tid'].'" class="comment_btn"></div><div class="f_task_text" title="'.$str.'">'.$str.'</div><div tid="'.$row['tid'].'" class="f_task_texp">'.$row['texp'].'</div><div tid="'.$row['tid'].'" class="fighto'.$isliked.'"></div></div>';
}
?>
