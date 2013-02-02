<?php
    session_start();
    include_once '../db/DBadapter.php';
    if(isset($_SESSION['uid'])){
        $db=new DBadapter();
        $result = $db->getNewsByUid($_SESSION['uid']);
        if(isset($result['eventtype'])){
            switch($result['eventtype']){
                case EventTypes::COMMENT:echoCommentEvent($result);break;
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
    }else{
        echo -1;
    }
    
function echoCommentEvent($result){
    
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
