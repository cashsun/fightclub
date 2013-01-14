<?php
//uid by SESSION
if(isset($_SESSION['uid'])){
    $user = getAllByUid($_SESSION['uid']);
    $groups = $user->getTaskGroups();
}
?>
<div id="master">
    <div id="navibar">
        <img id="profile_image" src="image/profile.png" alt=""/>
        <div id="profile_username" class="username">
            <?php echo 'Welcome back, '.$user->getUsername();?>
        </div>
        <div id="logout" class="button"><a href="test/logout.php">Logout</a></div>
        <div id="loadingImage"></div>
        <div id="friends_button" class="button">Friends</div>
        <div id="news_button" class="button">News</div>
    </div>
    <div id="panel_main">
        <img id="tg_selector" src="image/tg_selector.png"/>
        <div id="panel_group">
            <?php
                $i=-1;
                foreach($groups as $group){
                    if($group->getTgid()!=-1){
                        $i++;
                        $isSelect = "";
                        if($i==0){
                            $isSelect = " selected";
                            echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.'"><div class="delete_group">x</div><div class="tg_title_text tg_text_selected"><span>'.$group->getTitle().'</span></div></div>';
                        }else{
                            echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'" class="tg_title'.$isSelect.'"><div class="delete_group">x</div><div class="tg_title_text"><span>'.$group->getTitle().'</span></div></div>';
                        }  
                    }
                }
            ?>
            <div id="panel_control">
                <div id="create_group">+</div>
                <div id="u_group">*</div>
                <div id="history">history</div>
            </div>
        </div>
        <div id="panel_task" class="hidden"><div id="task_wrapper" style="margin-left: auto;margin-right: auto">
            <?php
                if($groups[0]->getTgid()!=-1){
                    echo '<input id="input_task" class="input_task roundcorner" type="text" maxlength="140"/><ul id="tasks_sortable">';
                }
                $tasks = $groups[0]->getTasks();
                foreach($tasks as $task){
                    if($task->getContent()!=''){
                        echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'"class="t_content hoverable roundcorner"><div class="t_content_text">'.$task->getContent().'</div><div class="delete_task">x</div></li>';
                    }
                }
                echo '</ul>';
            ?></div>
        </div>
        <div id="panel_social"></div>
    </div>
    <div id="cache" class="hidden">
        <?php
            foreach($groups as $group){
                echo '<div priority="'.$group->getPriority().'" id="'.$group->getTgid().'"><ul id="tasks_sortable">';
                $tasks = $group->getTasks();
                foreach($tasks as $task){
                    if($task->getContent()!=''){
                        echo '<li privacy="'.$task->getPrivacy().'" tid="'.$task->getTid().'"class="t_content hoverable roundcorner"><div class="t_content_text">'.$task->getContent().'</div><div class="delete_task">x</div></li>';
                    }
                }
                echo '</ul></div>';
            }
            if($groups[0]->getTgid()!=-1)
                echo '<div id="tgid">'.$groups[0]->getTgid().'</div>';
        ?>
        <div id="uid"><?php echo $user->getUid() ?></div>
    </div>
    <div id="g_dialog" title="Create New Group">name:<input type="text" class="input" id="input_group" maxlength="40"/><br/><br/>
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
    <div id="t_dialog" title="Update Task">
        <div id="tid" class="hidden"></div>
        content:<input type="text" class="input" id="update_task" maxlength="140"/><br/><br/>
        privacy:<select id="u_t_privacy">
            <option value="0">Only me</option>
            <option value="1">Friends</option>
            <option value="2">Public</option>
        </select>
    </div>
    <div id="u_g_dialog" title="Update Group">name:<input type="text" class="input" id="update_group" maxlength="40"/><br/><br/>
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
<script type="text/javascript" src="js/main_panel.js"></script>
<script type="text/javascript" src="js/social_panel.js"></script>

