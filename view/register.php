<?php
    session_start();
    ob_start();
    include_once '../db/DBadapter.php';
    
    /* define variables for fields */
    class REGISTER_STATUS{
      const INITIAL = 0;
      const EMPTY_FIELD = 1;
      const ERROR_FORMAT = 2;
      const RECORD_EXISTS = 3;
      const PASS_NOT_MATCH = 4;
      const SUCCESS = 5;
    }
    
    function REGISTER_ConstructErrorStr($status, $field, $value){
      $str = "";
      switch($status){
        case REGISTER_STATUS::INITIAL:
          $str = sprintf("");
          break;
        case REGISTER_STATUS::EMPTY_FIELD:
          $str = sprintf("%s is Mandatory", $field);
          break;
        case REGISTER_STATUS::ERROR_FORMAT:
          $str = sprintf("%s Invalid", $field);
          break;
        case REGISTER_STATUS::RECORD_EXISTS:
          $str = sprintf("%s already Exists, choose another one:)", $value);
          break;
        case REGISTER_STATUS::PASS_NOT_MATCH:
          $str = sprintf("Re-enter Password Not Match");
        default:
          break;
      }
      return $str;
    }
    
    function REGISTER_IsError($status){
      if($status>0 && $status<5)
        return TRUE;
      else
        return FALSE;
    }
    
    $first = true;
    if(isset($_POST['first']))
      $first = false;
    $submit = false;
    $username = REGISTER_STATUS::INITIAL;
    $passwd = REGISTER_STATUS::INITIAL;
    $repasswd = REGISTER_STATUS::INITIAL;
    $firstname = REGISTER_STATUS::INITIAL;
    $lastname = REGISTER_STATUS::INITIAL;
    $email = REGISTER_STATUS::INITIAL;
    
    if(!$first)
    {
      if(isset($_POST['username'])&&$_POST['username']!='')
      {
        $pattern = "/\b^[a-zA-Z0-9]{1,20}$\b/";
        if(!preg_match($pattern, $_POST['username']))
          $username = REGISTER_STATUS::ERROR_FORMAT;
        else
          $username = REGISTER_STATUS::SUCCESS;
      }
      else
        $username = REGISTER_STATUS::EMPTY_FIELD;
      if(isset($_POST['password'])&&$_POST['password']!='')
      {
        $pattern = "/^[a-zA-Z0-9!.,#~$\��*+-_]{4,20}$/";
        if(!preg_match($pattern, $_POST['password']))
          $passwd = REGISTER_STATUS::ERROR_FORMAT;
        else
          $passwd = REGISTER_STATUS::SUCCESS;
      }
      else
        $passwd = REGISTER_STATUS::EMPTY_FIELD;
      if(isset($_POST['password2'])&&$_POST['password2']!='')
      {
        if(!REGISTER_IsError($passwd) && $_POST['password2']!=$_POST['password'])
          $repasswd = REGISTER_STATUS::PASS_NOT_MATCH;
        else
          $repasswd = REGISTER_STATUS::SUCCESS;
      }
      else
        $repasswd = REGISTER_STATUS::EMPTY_FIELD;
      if(isset($_POST['firstname'])&&$_POST['firstname']!='')
        $firstname = REGISTER_STATUS::SUCCESS;
      else
        $firstname = REGISTER_STATUS::EMPTY_FIELD;
      if(isset($_POST['lastname'])&&$_POST['lastname']!='')
        $lastname = REGISTER_STATUS::SUCCESS;
      else
        $lastname = REGISTER_STATUS::EMPTY_FIELD;
      if(isset($_POST['email'])&&$_POST['email']!='')
        $email = REGISTER_STATUS::SUCCESS;
      else
        $email = REGISTER_STATUS::EMPTY_FIELD;
    }
    if($username==REGISTER_STATUS::SUCCESS && 
        $passwd==REGISTER_STATUS::SUCCESS &&
        $repasswd==REGISTER_STATUS::SUCCESS &&
        $firstname==REGISTER_STATUS::SUCCESS &&
        $lastname==REGISTER_STATUS::SUCCESS &&
        $email==REGISTER_STATUS::SUCCESS)
    {
        $con = new DBadapter();
        $result = $con->register($_POST['username'],$_POST['password'],$_POST['firstname'],
                                        $_POST['lastname'], $_POST['email'], 0);
        $uid = $result['uid'];
        if($uid > 0)
        {
          /* SUCCESSFUL */
          $_SESSION['uid'] = $uid;
          $submit = true;
        }
        else if($uid == -1)
        {
          /* DB ERROR */
          header("location:/fightclub/test/register.php");
        }
        else if($uid == 0)
        {
          /* RECORD EXISTS */
          if(isset($result['existM'])){
            if($result['existM'])
              $email = REGISTER_STATUS::RECORD_EXISTS;
            if($result['existU']){
              $username = REGISTER_STATUS::RECORD_EXISTS;
            }
          }
            
        }
          
    }
       
if($submit)
{
  header("location:/fightclub/index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Fighto! Club</title>
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
        <input name="username" value="<?php if($username == REGISTER_STATUS::SUCCESS) echo $_POST['username'];?>" type="text"/>
      </div>
      <div class="error-msg">
      <?php
      if(REGISTER_IsError($username)&&!$first)
        echo REGISTER_ConstructErrorStr($username, 'username', $_POST['username']);
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
      if(REGISTER_IsError($passwd)&&!$first)
        echo REGISTER_ConstructErrorStr($passwd, 'password', '');
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
      if(REGISTER_IsError($repasswd)&&!$first)
        echo REGISTER_ConstructErrorStr($repasswd, 're-enter password', '');
      ?>
    </div>
    <div class="prompt-firstname">
      <div class="field-name">
        First Name
      </div>
      <div class="holding">
        <input name="firstname" value="<?php if($firstname == REGISTER_STATUS::SUCCESS) echo $_POST['firstname'];?>" type="text"/>
      </div>
      <?php
      if(REGISTER_IsError($firstname)&&!$first)
        echo REGISTER_ConstructErrorStr($firstname, 'firstname', '');
      ?>
    </div>
    <div class="prompt-lastname">
      <div class="field-name">
        Last Name
      </div>
      <div class="holding">
        <input name="lastname" value="<?php if($lastname == REGISTER_STATUS::SUCCESS) echo $_POST['lastname'];?>" type="text"/>
      </div>
      <?php
      if(REGISTER_IsError($lastname)&&!$first)
        echo REGISTER_ConstructErrorStr($lastname, 'lastname', '');
      ?>
    </div>
    <div class="prompt-email">
      <div class="field-name">
        E-mail
      </div>
      <div class="holding">
        <input name="email" value="<?php if($email == REGISTER_STATUS::SUCCESS) echo $_POST['email'];?>" type="email"/>
      </div>
      <?php
      if(REGISTER_IsError($email)&&!$first)
        echo REGISTER_ConstructErrorStr($email, 'email', $_POST['email']);
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