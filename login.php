    <?php

        ob_start();
        session_start();
        $pageTitle = 'Login';

        if (isset($_SESSION['user'])){

            header('Location: index.php'); // Redirect To Index page
        }

        include "init.php";

        //check if  user coming from HTTP post request

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if (isset($_POST['login'])){

            $user = $_POST['username'];
            $pass = $_POST['password'];

            $hashedPass = sha1($pass);


            //Check If The User Exist In Database
            $stmt = $pdo->prepare("SELECT UserID , Username , Password FROM users WHERE Username = ? AND Password = ? ");
            $stmt->execute([$user , $hashedPass]);
            $get = $stmt->fetch();
            $count = $stmt->rowCount();


            if ($count>0){

                $_SESSION['user'] = $user;    //Register session name

                $_SESSION['uid'] = $get['UserID']; // Register User ID

                header('Location: index.php'); // Redirect To Index page
                exit();
              }
            } else {

                $formErrors =[];

                $userName = $_POST['username'];
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $email = $_POST['email'];

                if (isset($userName)){
                      $filteredUser = filter_var($userName , FILTER_SANITIZE_STRING);

                      if (strlen($filteredUser) < 4){
                          $formErrors[] = 'User Name Must Be More Than 4 Characters';
                      }

                }

                if (isset($password) && isset($password2)){

                    if (empty($password)){
                        $formErrors[] = "Sorry Password can'\t Be Empty";
                    }

                    if (sha1($password) !== sha1($password2)){
                        $formErrors[] = 'Sorry Password Is Not Match';
                    }
                }

                if (isset($email)){
                    $filteredEmail = filter_var($email , FILTER_SANITIZE_EMAIL);
                    if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true){
                        $formErrors[] = 'This Email Is Not Valid';
                    }
                }

                //Check If There`s No Error Proceed The User Add

                if (empty($formErrors)) {

                    // Check If User Exist in Database

                    $check = checkItem("Username" , "users", $userName);

                    if ($check == 1){

                        $formErrors[] = 'Sorry This User Is Exist';

                    } else {

                        // Insert User Info Into Database

                        $stmt = $pdo->prepare('INSERT INTO users(Username , Password , Email , Date) VALUES (:zuser , :zpassword , :zemail , now())');
                        $stmt->execute([
                            'zuser' => $userName,
                            'zpassword' => sha1($password),
                            'zemail' => $email
                        ]);
                        // Echo Success Message
                        $succesMes = 'Congrats You Are Now Registered User ';
                    }
                }




            }
        }

        ?>


        <div class="container login-page">

            <h1 class="text-center">
                <span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
            </h1>

            <!-- Start Login Form -->

            <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
               <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter User Name" >
                <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Password">
                <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
            </form>

            <!-- End Login Form -->

            <!-- Start Signup Form -->

            <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <div class="input-container"><input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required ></div>
                <div class="input-container"><input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex Password" required ></div>
                <div class="input-container"> <input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a Password again" required ></div>
                <div class="input-container">  <input class="form-control" type="email" name="email" autocomplete="new-password" placeholder="Type a Valid Email" required ></div>
                <input class="btn btn-success btn-block" type="submit" value="Signup">
            </form>

            <!-- End Signup Form -->

            <div class="the-errors text-center">
                <?php
                    if (!empty($formErrors)){
                        foreach ($formErrors as $error){
                            echo '<div class="msg error">' .$error . '</div>';
                        }
                    }

                    if (isset($succesMes)){

                        echo '<div class="msg success">' . $succesMes . '</div>';

                    }
                ?>

            </div>


        </div>



    <?php

    include $tpl . 'footer.php';

    ob_end_flush()

    ;?>
