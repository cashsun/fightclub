<?php
//uid by SESSION
if(isset($_SESSION['uid'])){
    $user = getAllByUid($_SESSION['uid']);
    $groups = $user->getTaskGroups();
}
function echoProfilePic($user){
    echo '<img id="profile_image" src="image/'.$user->getAvatar().'.png" alt=""/>';
}
function echoTask(Task $task){
    $isDone = $task->isDone();
    $option = '';
    if($isDone==1){
        $option = 'checked';
    }
    echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'"class="t_content hoverable roundcorner">
      <div class="isDone"><input class="isdone_checkbox" type="checkbox" '.$option.'/></div><div class="t_content_text">'.$task->getContent().'</div><div class="delete_task"></div></li>';
}
function echoSortedTasks(TaskGroup $group){
    $order = $group->getTaskOrder();
    if($order==''){
        $tasks = $group->getTasks();
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
            <?php echo 'Welcome back, '.$user->getUsername()?>
        </div>
        <button id="logout">Logout</button>
        <button id="friends_button" class="button">Friends</button>
        <div id="group_button"><select data-highlight="true" id="show_group"><option>on</option><option>off</option></select></div>
    </div>
    <div id="panel_main">
        <div id="panel_group">
            <img id="tg_selector" src="image/tg_selector.png"/> 
            <div id="group_wrapper">
            <?php
                $i=-1;
                foreach($groups as $group){
                    if($group->getTgid()!=-1){
                        $i++;
                        $isSelect = "";
                        if($i==0){
                            $isSelect = " selected";
                            echo '<div gtype="'.$group->getGType().'" priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.' g_first"><div class="delete_group"></div><div class="tg_title_text tg_text_selected"><span>'.$group->getTitle().'</span></div></div>';
                        }else{
                            echo '<div gtype="'.$group->getGType().'" priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.'"><div class="delete_group"></div><div class="tg_title_text"><span>'.$group->getTitle().'</span></div></div>';
                        }  
                    }
                }
            ?>
            </div>
            <div id="create_group"></div>
            <div id="panel_control">
                <div id="u_group">EDIT</div>
                <div id="history">LOG</div>
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
                <li><a href="#tabs-1">Friends</a></li>
                <li><a href="#tabs-2">News</a></li>
                <li><a href="#tabs-3">Backyard</a></li>
                </ul>
                <div class="tab" id="tabs-1">
                    <input type="text" class="roundcorner" maxlength="50" id="input_friend"/>
                    <div id="friends_radios">
                        <input type="radio" class="radio" id="radio1" name="radio" checked="checked"/><label for="radio1">Friends</label>
                        <input type="radio" class="radio" id="radio2" name="radio" /><label for="radio2">Follow</label>
                        <input type="radio" class="radio" id="radio3" name="radio" /><label for="radio3">All</label>
                    </div>
                    <div id="friends_wrapper"></div>
                </div>
                <div class="tab" id="tabs-2">
                </div>
                <div class="tab" id="tabs-3">
                </div>
            </div>
        </div>
    </div>
    <div id="cache" class="hidden">
        <?php
            foreach($groups as $group){
                echo '<div gtype="'.$group->getGType().'" priority="'.$group->getPriority().'" id="'.$group->getTgid().'">';
                echoSortedTasks($group);
                echo '</div>';
            }
            echo '<div id="tgid">'.$groups[0]->getTgid().'</div>';
            
        ?>
        <div id="uid"><?php echo $user->getUid() ?></div>
    </div>
    <div id="g_dialog" class="dialog" title="Create New Group">name:<input type="text" class="input" id="input_group" maxlength="40"/><br/><br/>
        priority:
        <select id="g_priority">
            <option value="0">casual</option>
            <option value="1">very low</option>
            <option value="2">low</option>
            <option value="3">minor</option>
            <option value="4">medium</option>
            <option value="5">important</option>
            <option value="6">major</option>
            <option value="7">urgent</option>
            <option value="8">urgent+</option>
            <option value="9">immediate</option>
        </select><br/><br/>
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
        content:<input type="text" class="input" id="update_task" maxlength="140"/><br/><br/>
        privacy:<select id="u_t_privacy">
            <option value="0">Only me</option>
            <option value="1">Friends</option>
            <option value="2">Public</option>
        </select>
    </div>
    <div id="u_g_dialog" class="dialog" title="Update Group">name:<input type="text" class="input" id="update_group" maxlength="40"/><br/><br/>
        priority:
        <select id="u_g_priority">
            <option value="0">casual</option>
            <option value="1">very low</option>
            <option value="2">low</option>
            <option value="3">minor</option>
            <option value="4">medium</option>
            <option value="5">important</option>
            <option value="6">major</option>
            <option value="7">urgent</option>
            <option value="8">urgent+</option>
            <option value="9">immediate</option>
        </select><br/><br/>
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

