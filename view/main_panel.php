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
            <ul>
                
            </ul>
        </div>
        <div id="panal_task">
            <center><h1>tasks</h1></center>
        </div>
    </div>
</div>
