<?php
    session_start();
    include_once 'header.php';
    include_once 'model/User.php';
    include_once 'model/Task.php';
    include_once 'model/TaskGroup.php';
    include_once 'db/DBadapter.php';
    if(!isset($_SESSION['uid']))
      include_once 'test/loginTest.php';
    else
      include_once 'view/main_panel.php';
?>
</html>