<?php 
//uid by SESSION
if(isset($_GET['uid'])){
    $user = getAllByUid($_GET['uid']);
}
?>
<div id="master">
    <div id="navibar">
        <img id="profile_image" src="image/profile.png" alt=""/>
        <div id="profile_username" class="username"><?php echo $user->getUsername() ?></div>
    </div>
    <div id="panel_main">
        <div id="panal_group">
            <?php
                $groups = $user->getTaskGroups();
                foreach($groups as $group){
                    echo '<div id="'.$group->getTgid().'" class="tg_title">'.$group->getTitle().'</div>';
                }
            ?>
        </div>
        <div id="panal_task">
            <?php  
                $tasks = $groups[0]->getTasks();
                foreach($tasks as $task){
                    echo '<div tid="'.$task->getTid().'"class="t_content">'.$task->getContent().'</div>';
                }
            ?>
        </div>
        <div id="cache" class="hiddable">
            <?php
                $gcounter = 0;
                while($groups[$gcounter]!=null){
                    echo '<div id="'.$gcounter.'">';
                   $tasks = $groups[$gcounter]->getTasks();
                    foreach($tasks as $task){
                        echo '<div tid="'.$task->getTid().'"class="t_content">'.$task->getContent().'</div>';
                    }
                    echo '</div>';
                    $gcounter++;
                }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/main_panel.js"></script>

