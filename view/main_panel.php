<?php 
//uid by SESSION
if(isset($_GET['uid']))
    $user = getAllByUid($_GET['uid']);
?>
<div id="master">
    <div id="navibar">
        
    </div>
    <div id="panel_main">
        <div id="panal_group">
            <center><h1>Groups</h1></center>
        </div>
        <div id="panal_task">
            <center><h1>tasks</h1></center>
        </div>
    </div>
</div>
