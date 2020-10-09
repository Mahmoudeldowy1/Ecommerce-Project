<?php

    session_start();
    $pageTitle = 'Login';


    $noNavbar = '';

    if (isset($_SESSION['Username'])){

        header('Location: dashboard.php'); // Redirect To Dashboard page
    }

     include "init.php";

?>

<?php
        //check if  user coming from HTTP post request

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $username = $_POST['user'];
            $password = $_POST['password'];

            $hashedPass = sha1($password);


            //Check If The User Exist In Database
            $stmt = $pdo->prepare("SELECT UserID , Username , Password FROM users WHERE Username = ? AND Password = ? AND GroupIP = 1 ");
            $stmt->execute([$username , $hashedPass]);
            $row = $stmt->fetch();
            $count = $stmt->rowCount();


            if ($count>0){

                $_SESSION['Username'] = $username;    //Register session name

                $_SESSION['ID'] = $row['UserID'];    //Register session Id

                header('Location: dashboard.php'); // Redirect To Dashboard page
                exit();
            }



        }

?>


<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <h4 class="text-center">Admin login</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control"  type="password" name="password" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block"  type="submit" value="Login">
</form>




<?php include $tpl . 'footer.inc.php'; ?>
