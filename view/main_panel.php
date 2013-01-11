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
                $i=-1;
                foreach($groups as $group){
                    if($group->getTgid()!=-1){
                        $i++;
                        $isSelect = "";
                        if($i==0){
                            $isSelect = " selected";
                        }
                        echo '<div id="'.$group->getTgid().'" class="tg_title hoverable'.$isSelect.'">'.$group->getTitle().'<div class="delete_group">x</div></div>';
                    }
                }
            ?>
            <div id="create_group" class="hoverable">+</div>
        </div>
        <div id="panel_task">
            <?php
                if($groups[0]->getTgid()!=-1){
                    echo '<input id="input_task" class="input" type="text" maxlength="140"/>';
                }
                $tasks = $groups[0]->getTasks();
                foreach($tasks as $task){
                    if($task->getContent()!=''){
                        echo '<div tid="'.$task->getTid().'"class="t_content hoverable"><div class="tt_content">'.$task->getContent().'</div><div class="delete_task">x</div></div>';
                    }
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
                        if($task->getContent()!=''){
                            echo '<div tid="'.$task->getTid().'"class="t_content hoverable"><div class="tt_content">'.$task->getContent().'</div><div class="delete_task">x</div></div>';
                        }
                    }
                    echo '</div>';
                }
                if($groups[0]->getTgid()!=-1)
                    echo '<div id="tgid">'.$groups[0]->getTgid().'</div>';
            ?>
            <div id="uid"><?php echo $user->getUid() ?></div>
        </div>
    <div id="g_dialog" title="Create New Group">name:<input type="text" class="input" id="input_group" maxlength="40"/><br/><br/>
        priority (Biggest number first):
        <select id="g_priority">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
        </select>
    </div>
    <div id="t_dialog" title="Update Task">
        <div id="tid" class="hiddable"></div>
        content:<input type="text" class="input" id="update_task" maxlength="140"/><br/>
    </div>
</div>
<script type="text/javascript" src="js/main_panel.js"></script>

