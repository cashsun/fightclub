<form action="#" method="post"> 
    Username: <input name="username" type="text"/>
    Password: <input name="password" type="password"/>
    <input type="submit" value="submit"/>
</form>
<?php
    include_once '../db/DBadapter.php';
    if(!isset($_POST['username'])||$_POST['username']==''){
        echo 'input username please';
    }else if(!isset($_POST['password'])||$_POST['password']==''){
        echo 'input password';
    }else{
        $con = new DBadapter();
        $result = $con->getUser($_POST['username'],$_POST['password']);
        if($result['valid'])
          echo $result['data']['email'];
        else
          echo 'incorrect info';
    }
        
?>
