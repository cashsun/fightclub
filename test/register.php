<?php
    session_start();
    include_once '../db/DBadapter.php';
    
    /* define variables for fields */
    
    $first = true;
    if(isset($_POST['first']))
      $first = false;
    $submit = false;
    $username = false;
    $passwd = false;
    $repasswd = false;
    $firstname = false;
    $lastname = false;
    $email = false;
    
    if(!$first)
    {
      if(isset($_POST['username'])&&$_POST['username']!='')
        $username = true;
      if(isset($_POST['password'])&&$_POST['password']!='')
        $passwd = true;
      if(isset($_POST['password2'])&&$_POST['password2']!=''&&$_POST['password2']==$_POST['password'])
        $repasswd = true;
      if(isset($_POST['firstname'])&&$_POST['firstname']!='')
        $firstname = true;
      if(isset($_POST['lastname'])&&$_POST['lastname']!='')
        $lastname = true;
      if(isset($_POST['email'])&&$_POST['email']!='')
        $email = true;
    }
    if($username&&$passwd&&$repasswd&&$firstname&&$lastname&&$email){
        $con = new DBadapter();
        $uid = $con->register($_POST['username'],$_POST['password'],$_POST['firstname'],
                                        $_POST['lastname'], $_POST['email'], 0);
        if($uid != -1)
        {
          $_SESSION['uid'] = $uid;
          echo 'user created uid = '.$uid.', will login in 3 seconds'.
                  '<meta http-equiv="refresh" content="0; URL=../index.php">';
          $submit = true;
        }
        else
          echo 'user creation error, will return to register page'.
                  '<meta http-equiv="refresh" content="0; URL=register.php">';
    }
       
if($submit)
  header("location:/fightclub/index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Fightclub</title>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" href="../css/pepper-grinder/jquery-ui-1.8.23.custom.css" type="text/css"/>
        <link rel="stylesheet" href="../css/signup.css"/>
</head>
<body>
<div class="signup-wrapper">
  <form action="register.php" method="post">
    <div class="prompt-username">
      <div class="field-name">
        Username
      </div>
      <div class="holding">
        <input name="username" value="<?php if($username) echo $_POST['username'];?>" type="text"/>
      </div>
      <div class="error-msg">
      <?php
      if(!$username&&!$first)
        echo 'Username Emptry or Error';
      ?>
      </div>
    </div>
    <div class="prompt-passwd">
      <div class="field-name">
        Password
      </div>
      <div class="holding">
        <input name="password" type="password"/>
      </div>
      <?php
      if(!$passwd&&!$first)
        echo 'Password Empty or Error';
      ?>
    </div>
    <div class="prompt-passwd2">
      <div class="field-name">
        Re-enter Password
      </div>
      <div class="holding">
        <input name="password2" type="password"/>
      </div>
      <?php
      if(!$repasswd&&!$first)
        echo 'Re-enter Password Not Match';
      ?>
    </div>
    <div class="prompt-firstname">
      <div class="field-name">
        First Name
      </div>
      <?php
      if(!$firstname&&!$first)
        echo 'First Name Emptry or Error';
      ?>
      <div class="holding">
        <input name="firstname" value="<?php if($firstname) echo $_POST['firstname'];?>" type="text"/>
      </div>
    </div>
    <div class="prompt-lastname">
      <div class="field-name">
        Last Name
      </div>
      <div class="holding">
        <input name="lastname" value="<?php if($lastname) echo $_POST['lastname'];?>" type="text"/>
      </div>
      <?php
      if(!$lastname&&!$first)
        echo 'Last Name Emptry or Error';
      ?>
    </div>
    <div class="prompt-email">
      <div class="field-name">
        E-mail
      </div>
      <div class="holding">
        <input name="email" value="<?php if($email) echo $_POST['email'];?>" type="email"/>
      </div>
      <?php
      if(!$email&&!$first)
        echo 'Email Empty or Error';
      ?>
    </div>
    <div class="prompt-email">
      <div class="holding">
        <input type="submit" value="submit"/>
      </div>
    </div>
    <input type="hidden" name="first" value=""/>
  </form>
</div>
<div class="tutorial-wrapper">
</div>
</body>
</html>