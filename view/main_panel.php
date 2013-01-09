<?php 
//uid by SESSION
if(isset($_GET['uid'])){
    $user = getAllByUid($_GET['uid']);
    $groups = $user->getTaskGroups();
}
?>
<div id="master">
    <div id="navibar">
        <img id="profile_image" src="image/profile.png" alt=""/>
        <div id="profile_username" class="username"><?php echo $user->getUsername() ?></div>
    </div>
    <div id="panel_main">
        <div id="panel_group">
            <?php
                
                foreach($groups as $group){
                    echo '<div id="'.$group->getTgid().'" class="tg_title hoverable">'.$group->getTitle().'</div>';
                }
            ?>
            <div class="create_group hoverable">+</div>
        </div>
        <div id="panel_task">
            <input id="input_task" type="text" maxlength="140"/>
            <?php  
                $tasks = $groups[0]->getTasks();
                foreach($tasks as $task){
                    echo '<div tid="'.$task->getTid().'"class="t_content hoverable">'.$task->getContent().'<div class="delete_task">x</div></div>';
                }
            ?>
        </div>
    </div>
        <div id="cache" class="hiddable">
            <?php
                foreach($groups as $group){
                    echo '<div id="'.$group->getTgid().'">';
                   $tasks = $group->getTasks();
                    foreach($tasks as $task){
                        echo '<div tid="'.$task->getTid().'"class="t_content hoverable">'.$task->getContent().'<div class="delete_task">x</div></div>';
                    }
                    echo '</div>';
                }
                echo '<div id="tgid">'.$groups[0]->getTgid().'</div>';
            ?>
            <div id="uid"><?php echo $user->getUid() ?></div>
        </div>
</div>
<script type="text/javascript" src="js/main_panel.js"></script>

