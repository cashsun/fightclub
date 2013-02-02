<?php
    session_start();
    include_once '../header.php';
    include_once '../db/DBadapter.php';
?>
<body>
  <div class="ErrorMsg">
    <?php
      $success = false;
      if(!isset($_POST['cm-name'])||$_POST['cm-name']==''){
          echo 'Input username please';
      }else if(!isset($_POST['cm-pass'])||$_POST['cm-pass']==''){
          echo 'Input password';
      }else{
          $con = new DBadapter();
          $result = $con->login($_POST['cm-name'],$_POST['cm-pass']);
          if($result['valid'])
          {
            $_SESSION['uid'] = $result['data']['uid'];
            $success = true;
          }
          else
            echo 'Incorrect info';
      }
    ?>
  </div>
  <br /><br />
  <div id="view-content">
  
    <?php
      if($success)
        echo 'Log in Successful, will turn to index page in 3 sec';
      else
        echo 'Password Error, will turn to index page in 3 sec';
    ?>
    <meta http-equiv="refresh" content="0; URL=../index.php">
  </div>
</body>

