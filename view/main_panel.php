<?php
//uid by SESSION
if(isset($_SESSION['uid'])){
    $user = getAllByUid($_SESSION['uid']);
    $groups = $user->getTaskGroups();
}
function echoProfilePic($user){
    echo '<img id="profile_image" src="image/'.$user->getAvatar().'.png" alt=""/>';
}
function echoTaskGroup($group,$i){
   if($group->getTgid()!=-1){
        $isSelect = "";
        $tgTextSel = "";
        if($i==0){
            $isSelect = " selected";
            $tgTextSel = " tg_text_selected";
        }
        echo '<div gtype="'.$group->getGType().'" priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.' g_first"><div class="delete_group"></div><div class="tg_title_text"'.$tgTextSel.'><span>'.$group->getTitle().'</span></div><div class="roundcorner tg_alarm"></div></div>';
    }
} 
function echoTask(Task $task){
    $isDone = $task->isDone();
    $option = '';
    $ribbon = '';
    if($isDone==1){
        $option = 'checked';
    }
    switch($task->getPrivacy()){
        case 1:$ribbon=' shared_f';break;
        case 2:$ribbon=' shared_g';break;
    }
    echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'" class="t_content hoverable roundcorner"><div class="handle"></div><div mytid="'.$task->getTid().'" title="❤" class="texp">'.$task->getTExp().'</div><div title="comment" class="comment">'.$task->getCcount().'</div>
      <div class="isDone"><input class="isdone_checkbox" title="complete" type="checkbox" '.$option.'/></div><div dead_date="'.$task->getDate().'" dead_time="'.$task->getTime().'" class="t_content_text">'.$task->getContent().'</div><div class="delete_task"></div><div class="tshare'.$ribbon.'"><div></li>';

}
function echoSortedTasks(TaskGroup $group){
    $order = $group->getTaskOrder();
    $tasks = $group->getTasks();
    if($order==''){
        foreach($tasks as $task){
            if($task->getContent()!=''){
                echoTask($task);
            }
        }
    }else{
        $tidlist=  explode(',', $order);
        foreach($tidlist as $tid){
            $task = $group->getTaskByTid($tid);
            if($task!=null && $task->getContent()!=''){
                echoTask($task);
            }
        }
    }
}

function housekeeping($tasks, $tidlist)
{
  if(sizeof($tidlist)!=sizeof($tasks)){
    $db = new DBadapter();
    $db->connect();
    foreach($tasks as $task){
        $tid = $task->getTid();
        if(!in_array($tid, $tidlist)){
          $result=$db->deleteTask($tid);
          echo $result;
        }
    }
  }
}
function getAllByUid($uid){
    $db = new DBadapter();
    $db->connect();
    $result = $db->getAllByUid($uid);
    $tasks = array();
    $groups = array();
    $user=null;
    $last_row=array();
    $t_counter=0;
    $g_counter=0;
    $current_tgid=-1;
    while($row =  mysql_fetch_array($result)){
        if($row['tgid']!= $current_tgid){
            $current_tgid = $row['tgid'];
            if($t_counter!=0){
                $last_row['tasks']=$tasks;
                $groups[$g_counter]=new TaskGroup($last_row);
                $last_row = $row;
                $g_counter++;
                $t_counter = 0;
                unset($tasks);
            }
        }
        $row['creatorname'] = $row['firstname'].' '.$row['lastname'];
        $tasks[$t_counter]=new Task($row);
        $last_row = $row;
        $t_counter++;
    }
    $last_row['tasks']=$tasks;
    $groups[$g_counter]=new TaskGroup($last_row);
    $last_row['taskgroups'] = $groups;
    $user=new User($last_row);
    return $user;
}
?>
<body onload="showPanel()">
<div id="loadingImage"></div>
<div id="master" class="hidden">
    <div id="navibar">
        <?php echoProfilePic($user);?>
        <div id="profile_username" class="username">
            <?php
            echo '<div class="my_fullname">'.$user->getFirstname().' '.$user->getLastname().'</div>';
            echo '<div class="my_exp">Exp '.$user->getExp().'</div>';
            ?>
        </div>
        <button id="logout">Logout</button>
        <button id="club_button" class="button">Club<div class="roundcorner club_alarm"></div></button>
        <div id="group_button"><select data-highlight="true" id="show_group"><option>on</option><option>off</option></select></div>
    </div>
    <div id="panel_main">
        <div id="panel_group">
            <img id="tg_selector" src="image/tg_selector.png"/> 
            <div id="group_wrapper">
            <?php
                $i=-1;
                foreach($groups as $group){
                    $i++;
                    echoTaskGroup($group,$i);
                }
            ?>
            </div>
            <div id="create_group"></div>
            <div id="panel_control">
                <div id="u_group">EDIT</div>
                <div id="option">OPTION</div>
            </div>
        </div>
        <div id="panel_task"><div id="task_wrapper" style="margin-left: auto;margin-right: auto">
        <input id="input_task" class="input_task roundcorner" type="text" maxlength="140"/>    
            <?php
                echo '<ul id="tasks_sortable">';
                echoSortedTasks($groups[0]);
                echo '</ul>';
            ?></div>
        </div>
        <div id="panel_social">
            <div id="social_loading" class="hidden"></div>
            <div id="social_tabs">
                <ul>
                <li><a href="#tabs-1">Friends<div class="roundcorner club_alarm">6</div></a></li>
                <li><a href="#tabs-2">News</a></li>
                <li><a href="#tabs-3">Backyard</a></li>
                </ul>
                <div class="tab" id="tabs-1">
                    <input type="text" class="roundcorner" maxlength="50" id="input_friend"/>
                    <div id="friends_radios">
                        <input type="radio" class="radio" id="radio0" name="radio" /><label for="radio0">Follows</label>
                        <input type="radio" class="radio" id="radio1" name="radio" /><label for="radio1">Friends</label>
                        <input type="radio" class="radio" id="radio2" name="radio" /><label for="radio2">Fans</label>
                    </div>
                    <div id="friends_wrapper"></div>
                </div>
                <div class="tab" id="tabs-2">
                    <div id="news_wrapper"></div>
                </div>
                <div class="tab" id="tabs-3">
                </div>
            </div>
        </div>
    </div>
    <div id="cache" class="hidden">
        <div id="comment_main" tid="-1" class="hidden">
            <div id="comment_dialog"></div>
            <textarea rows="3" id="comment_input" maxlength="140"></textarea><br/>
            <button id="comment_submit">comment</button>
        </div>
        <div id="fightolist" class="hidden"></div>
        <?php
            foreach($groups as $group){
                echo '<div gtype="'.$group->getGType().'" priority="'.$group->getPriority().'" id="'.$group->getTgid().'">';
                echoSortedTasks($group);
                echo '</div>';
            }
            echo '<div id="tgid">'.$groups[0]->getTgid().'</div>';
            
        ?>
        <div id="uid"><?php echo $_SESSION['uid']; ?></div>
    </div>
    <div id="g_dialog" class="dialog" title="Create New Group">name:<input type="text" class="input" id="input_group" maxlength="40"/><br/><br/>
        priority:
        <div id="g_priority">
            <span></span>
        </div><br/><div style="font-style: italic;font-size: 0.8em" id="g_phint">casual</div><br/><br/>
        type:
        <select id="g_type">
            <option value="0">Work</option>
            <option value="1">Education</option>
            <option value="2">Business</option>
            <option value="3">Finance</option>
            <option value="4">Travel</option>
            <option value="5">Sports</option>
            <option value="6">Entertainment</option>
            <option value="7">Lifestyle</option>
        </select>
    </div>
    <div id="t_dialog" class="dialog" title="Update Task">
        <div id="tid" class="hidden"></div>
        content:<br/><textarea rows="5" cols="60" id="update_task" maxlength="140"></textarea><br/><br/>
        privacy:<br/><div id="u_t_privacy">
                        <input type="radio" class="radio" id="privacy0" name="radio" value="0"/><label for="privacy0">Private</label>
                        <input type="radio" class="radio" id="privacy1" name="radio" value="1"/><label for="privacy1">Friends</label>
                        <input type="radio" class="radio" id="privacy2" name="radio" value="2"/><label for="privacy2">Public</label>
                </div>
        <br/><br/>
        deadline:<br/>
        <div id="u_t_deadline"><input type="text" id="deadline_date"/><input type="text" id="deadline_time"/></div>
    </div>
    <div id="u_g_dialog" class="dialog" title="Update Group">name:<input type="text" class="input" id="update_group" maxlength="40"/><br/><br/>
        priority:
        <div id="u_g_priority">
            <span></span>
        </div><br/><div style="font-style: italic;font-size: 0.8em" id="u_g_phint"></div><br/><br/>
        type:
        <select id="u_g_type">
            <option value="0">Work</option>
            <option value="1">Education</option>
            <option value="2">Business</option>
            <option value="3">Finance</option>
            <option value="4">Travel</option>
            <option value="5">Sports</option>
            <option value="6">Entertainment</option>
            <option value="7">Lifestyle</option>
        </select>
        <br/><br/><br/>
        <div style="font-size: 0.7em">You can open this dialog by double clicking the group.</div>
    </div>
    <div id="avatar_dialog" class="dialog" title="Avatar">
        <?php 
            for($i=0;$i>-51;$i--){
                echo '<div pid="'.$i.'" class="avatar"><img src="image/'.$i.'.png" alt=""/></div>';
            }
        ?>
    </div>
    <div id="user_dialog" class="dialog" title="User">
        <div id="user_tabs">
                <ul>
                <li><a href="#utabs-1">Info</a></li>
                <li><a href="#utabs-2">Stats</a></li>
                <li><a href="#utabs-3">History</a></li>
                </ul>
                <div class="tab" id="utabs-1">
                    coming soon.
                </div>
                <div class="tab" id="utabs-2">
                    coming soon.
                </div>
                <div class="tab" id="utabs-3">
                    coming soon.
                </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
        function showPanel(){
            setTimeout(function(){
                $('#master').fadeIn(300,function(){
                loading_image.fadeOut(100,function(){
                    $('#input_task').focus();
                });
            });
            },200);
        }
</script>
<script type="text/javascript" src="js/loading.js"></script>
<script type="text/javascript" src="js/main_panel.js"></script>
<script type="text/javascript" src="js/social_panel.js"></script>


