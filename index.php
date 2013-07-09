<?php
    session_start();
    ob_start();
    include_once 'header.php';
    include_once 'model/User.php';
    include_once 'model/Task.php';
    include_once 'model/TaskGroup.php';
    include_once 'db/DBadapter.php';
    if(!isset($_SESSION['uid']))
      include_once 'view/login.php';
    else
      include_once 'view/main_panel.php';
?>
</html>