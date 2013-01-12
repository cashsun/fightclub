<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
    session_start();
    include_once 'service/web.php';
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