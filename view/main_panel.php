<?php
//uid by SESSION
if(isset($_SESSION['uid'])){
    $user = getAllByUid($_SESSION['uid']);
    $groups = $user->getTaskGroups();
}
function echoSortedTasks(TaskGroup $group){
    $order = $group->getTaskOrder();
    if($order==''){
        $tasks = $group->getTasks();
        foreach($tasks as $task){
            if($task->getContent()!=''){
                echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'"class="t_content hoverable roundcorner"><div class="t_content_text">'.$task->getContent().'</div><div class="delete_task"></div></li>';
            }
        }
    }else{
        $tidlist=  explode(',', $order);
        foreach($tidlist as $tid){
            $task = $group->getTaskByTid($tid);
            if($task!=null && $task->getContent()!=''){
                echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'"class="t_content hoverable roundcorner"><div class="t_content_text">'.$task->getContent().'</div><div class="delete_task"></div></li>';
            }
        }
    }
}
?>
<body onload="showPanel()">
<div id="loadingImage"></div>
<div id="master">
    <div id="navibar">
        <img id="profile_image" src="image/profile.png" alt=""/>
        <div id="profile_username" class="username">
            <?php echo 'Welcome back, '.$user->getUsername()?>
        </div>
        <button id="logout">Logout</button>
        <button id="friends_button" class="button">Friends</button>
        <button id="news_button" class="button">News</button>
    </div>
    <div id="panel_main" class="hidden">
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
                            echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.' g_first"><div class="delete_group"></div><div class="tg_title_text tg_text_selected"><span>'.$group->getTitle().'</span></div></div>';
                        }else{
                            echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.'"><div class="delete_group"></div><div class="tg_title_text"><span>'.$group->getTitle().'</span></div></div>';
                        }  
                    }
                }
            ?>
            </div>
            <div id="panel_control">
                <div id="create_group">CREATE</div>
                <div id="u_group">EDIT</div>
                <div id="history">LOG</div>
            </div>
        </div>
        <div id="panel_task" class="hidden"><div id="task_wrapper" style="margin-left: auto;margin-right: auto">
        <input id="input_task" class="input_task roundcorner" type="text" maxlength="140"/>    
            <?php
                echo '<ul id="tasks_sortable">';
                echoSortedTasks($groups[0]);
                echo '</ul>';
            ?></div>
        </div>
        <div id="panel_social"></div>
    </div>
    <div id="cache" class="hidden">
        <?php
            foreach($groups as $group){
                echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'">';
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
        </select>
    </div>
</div>
</body>
<script type="text/javascript">
    function showPanel(){
            setTimeout(function(){
                $('#panel_main').fadeIn(300,function(){
                loading_image.hide();
            });
            },200);
    }
</script>

