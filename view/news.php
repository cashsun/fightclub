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
              switch(EventTypes::COMMENT){
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
    
function echoCommentEvent($result){
    echo $result['firstname1'].' '.$result['lastname1'].' '.$result['avatar1'].' '.$result['firstname2'].' '.$result['lastname2'].
            ' '.$result['tcontent'].' '.$result['ccontent'].' '.$result['tid'].' '.$result['tstamp'];
}
function echoAtEvent($result){
    
}
function echoFightoEvent($result){
    
}
function echoPublishEvent($result){
    
}
function echoUpdateEvent($result){
    
}

?>
