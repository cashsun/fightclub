<?php
  include_once './db/DBadapter.php';
  
  $first = true;
  $success = false;
  
  if(isset($_POST['first']))
    $first = false;
    
  if(!$first)
  {
    if(!isset($_POST['cm-name'])||$_POST['cm-name']==''){
        $errMsg = 'Input username please';
    }else if(!isset($_POST['cm-pass'])||$_POST['cm-pass']==''){
        $errMsg = 'Input password';
    }else{
        $con = new DBadapter();
        $result = $con->login($_POST['cm-name'],$_POST['cm-pass']);
        if($result['valid'])
        {
          $_SESSION['uid'] = $result['data']['uid'];
          $success = true;
        }
        else
        {
          $errMsg = 'Unmatch Credential';
        }
    }
  }
if($success)
{
  header("location: /fightclub/index.php");
  exit();
}
  
?>



<link rel="stylesheet" href="css/login.css"/>
<script type="text/javascript" src="js/login.js"></script>
<div id="login-wrapper">
    <img id="fighto_logo" src="image/fighto_logo.png" alt=""/>
    <div class="front-login">
        <form action="" class="signin" method="post">
        <div>
            <input type="text" name="cm-name" class="input-box" value="username or email">
            <input type="password" name="cm-pass" class="input-box" value="password">
            <input type="hidden" name="first">
            <div class="signup">Login</div>
            <div class ="register">Register</div>
            <input id="submit" type="submit" style="display: none"/>
        </div>
        </form>
        <div><?php if(isset($errMsg))echo $errMsg;?></div>
    </div>
</div>