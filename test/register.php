<?php
    session_start();
    include_once '../db/DBadapter.php';
    
    
    if(!isset($_POST['username'])||$_POST['username']==''){
        echo 'input username please';
    }else if(!isset($_POST['password'])||$_POST['password']==''){
        echo 'input password';
    }else{
        $con = new DBadapter();
        $uid = $con->register($_POST['username'],$_POST['password'],$_POST['firstname'],
                                        $_POST['lastname'], $_POST['email'], 0);
        if($uid != -1)
        {
          $_SESSION['uid'] = $uid;
          echo 'user created uid = '.$uid.', will login in 3 seconds'.
                  '<meta http-equiv="refresh" content="3; URL=../index.php">';
        }
        else
          echo 'user creation error, will return to register page'.
                  '<meta http-equiv="refresh" content="3; URL=register.php">';
    }
        
?>