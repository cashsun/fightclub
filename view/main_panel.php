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
                foreach($user->getTaskGroups() as $group){
                    echo '<div class="tg_title">'.$group->getTitle().'</div>';
                }
            ?>
        </div>
        <div id="panal_task">
            <?php 
                $groups = $user->getTaskGroups();
                $tasks = $groups[0]->getTasks();
                foreach($tasks as $task){
                    echo '<div tid="'.$task->getTid().'"class="t_content">'.$task->getContent().'</div>';
                }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/main_panel.js"></script>

