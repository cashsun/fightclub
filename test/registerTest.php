<form action="#" method="post"> 
    Username: <input name="username" type="text"/>
    Password: <input name="password" type="password"/>
    first name: <input name="firstname" type="text"/>
    last name: <input name="lastname" type="text"/>
    email: <input name="email" type="email"/>
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
        $uid = $con->register($_POST['username'],$_POST['password'],$_POST['firstname'],
                                        $_POST['lastname'], $_POST['email']);
        echo 'user created uid = '.$uid;
    }
        
?>
