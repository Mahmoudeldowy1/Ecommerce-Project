<?php


    /*
     ** Manage Users Page
     ** You can Add | Edit | Delete Members From Here
    */

        ob_start(); // Output Buffering Start

        session_start();

        $pageTitle = 'Members';

        if (isset($_SESSION['Username'])){

            include "init.php";


            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            //Start manage Page

            if ($do == 'Manage') {// Manage Members Page

                $query = '';

                if (isset($_GET['page']) && $_GET['page'] == 'Pending'){
                    $query = 'AND RegStatus = 0';
                }

                $stmt = $pdo->prepare("SELECT * FROM users WHERE GroupIP != 1 $query ORDER BY UserID DESC");
                $stmt->execute();
                $users = $stmt->fetchAll();

                if (!empty($users)){

                 ?>

                <h1 class="text-center">Manage Members</h1>
                     <div class="container">
                          <div class="table-responsive">

                                <table class="main-table text-center table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Registered Date</th>
                                        <th scope="col">Control</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($users as $user) {

                                            echo '<tr>';
                                            echo '<td>' . $user['UserID']   . '</td>';
                                            echo '<td>' . $user['Username'] . '</td>';
                                            echo '<td>' . $user['Email']    . '</td>';
                                            echo '<td>' . $user['FullName'] . '</td>';
                                            echo '<td>' . $user['Date']     . '</td>';
                                            echo "<td>
                                                      <a href='members.php?do=Edit&userid=". $user['UserID'] ."' class='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                                                      <a href='members.php?do=Delete&userid=". $user['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'> </i>Delete</a>";
                                                        if ($user['RegStatus'] == 0) {

                                                          echo  "<a href='members.php?do=Activate&userid=". $user['UserID'] ."' class='btn btn-info activate'> <i class='fa fa-check'></i>Activate</a>";

                                                        }

                                            echo    "</td>";
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                     <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New User</a>
                          </div>
                     </div>
             <?php
                }else{

                    echo '<div class="container">';
                        echo '<div class="nice-message">There IS No Users To Show</div>';
                        echo '<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New User</a>';
                    echo '</div>';

                }

              }elseif ($do == 'Add'){  //Add Member Page ?>

                <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form action="?do=Insert" method="post">
                        <div class="form-group input-container">
                            <label class="col-sm-2">Username</label>
                            <input  type="text" class="form-control" name="username" required="required" placeholder="Username To Login Into Shop">
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Password</label>
                            <input  type="password" class="password form-control" name="password" autocomplete="new-password" required="required" placeholder="Password Must Be Hard & Complex">
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Email</label>
                            <input type="email" class="form-control" name="email" required="required" placeholder="Email Must Be Valid">
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Full name</label>
                            <input type="text" class="form-control" name="full_name" required="required" placeholder="Full Name Appear In YourProfile Page">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </form>
                </div>

           <?php
            }

            elseif ($do == 'Insert'){

                    //Insert Member Page


                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        echo '<h1 class="text-center">Add Member</h1>';
                        echo "<div class = 'container'>";

                        //Get Variables From the Form
                        $username = $_POST['username'];
                        $pass = $_POST['password'];
                        $email= $_POST['email'];
                        $name = $_POST['full_name'];

                        $hashPass = sha1($_POST['password']);

                        //Validate The Form

                        $formErrors = [];

                        if (strlen($user) < 4){
                            $formErrors[] = 'Username cant be Less Than <strong> 4 characters</strong>';
                        }

                        if (strlen($user) > 20){
                            $formErrors[] = 'Username cant be More Than <strong> 20 characters</strong>';
                        }

                        if (empty($username)){
                            $formErrors[] = 'Username cant be <strong>empty</strong>';
                        }

                        if (empty($pass)){
                            $formErrors[] = 'Password cant be <strong>empty</strong>';
                        }


                        if (empty($name)){
                            $formErrors[] = 'Full Name cant be <strong>empty</strong>';
                        }

                        if (empty($email)){
                            $formErrors[] = 'Email cant be <strong>empty</strong>';
                        }

                        // Loop Into Errors Array And Echo It

                        foreach ($formErrors as $error)
                        {
                            echo '<div class="alert alert-danger">' .  $error . '</div>';
                        }


                        //Check If There`s No Error Proceed The Update Operations

                        if (empty($formErrors)) {

                            // Check If User Exist in Database

                            $check = checkItem("Username" , "users", $username);

                            if ($check == 1){

                                $theMsg =  "<div class='alert alert-danger' >Sorry This User Is Exist </div>";
                                redirectHome($theMsg, 'back');

                            } else {

                                        // Insert User Info Into Database

                                        $stmt = $pdo->prepare('INSERT INTO users(Username , Password , Email , FullName ,RegStatus ,  Date) VALUES (:user , :password , :email , :name, 1, now())');
                                        $stmt->execute([
                                            'user' => $username,
                                            'password' => $hashPass,
                                            'email' => $email,
                                            'name' => $name
                                        ]);
                                        // Echo Success Message
                                          $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted </div>';
                                         redirectHome($theMsg, $url = 'back', $seconds = 4);
                                }
                        }



                    } else {

                        echo "<div class = 'container'>";
                        $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                        redirectHome($theMsg);
                        echo "</div>";
                    }
                    echo "</div>";



            }
            elseif ($do == 'Edit') {// edit Member Page

                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                $stmt = $pdo->prepare("SELECT * FROM users WHERE UserID = ? ");
                $stmt->execute([$userid]);
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) {

                ?>
                        <h1 class="text-center">Edit Member</h1>
                        <div class="container">
                        <form action="?do=Update" method="post">
                            <input type="hidden" name="id" value="<?php echo $userid ?>">
                            <div class="form-group input-container">
                                <label class="col-sm-2">Username</label>
                                <input value="<?php echo $row['Username'] ?>" type="text" class="form-control" name="username" required="required">
                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Password</label>
                                <input value="<?php  $row['Password'] ?>" type="hidden" name="oldpassword" >
                                <input  type="password" class="form-control" name="newpassword" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change">
                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Email</label>
                                <input value="<?php echo $row['Email'] ?>" type="email" class="form-control" name="email" required="required">
                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Full name</label>
                                <input  value="<?php echo $row['FullName'] ?>" type="text" class="form-control" name="full_name" required="required">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                        </div>
        <?php
                } else{
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-danger'>Theres No Such ID </div>";
                    redirectHome($theMsg );
                    echo "</div>";
                }


            } elseif ($do == 'Update'){ // Update Member  Page

                echo '<h1 class="text-center">Update Member</h1>';

                echo "<div class = 'container'>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        //Get Variables From the Form
                    $id = $_POST['id'];
                    $user = $_POST['username'];
                    $email= $_POST['email'];
                    $name = $_POST['full_name'];

                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;
                    //Validate The Form

                    $formErrors = [];

                    if (strlen($user) < 4){
                        $formErrors[] = 'Username cant be Less Than <strong> 4 characters</strong>';
                    }

                    if (strlen($user) > 20){
                        $formErrors[] = 'Username cant be More Than <strong> 20 characters</strong>';
                    }

                    if (empty($user)){
                        $formErrors[] = 'Username cant be <strong>empty</strong>';
                    }

                    if (empty($name)){
                        $formErrors[] = 'Full Name cant be <strong>empty</strong>';
                    }

                    if (empty($email)){
                        $formErrors[] = 'Email cant be <strong>empty</strong>';
                    }

                    // Loop Into Errors Array And Echo It

                    foreach ($formErrors as $error)
                    {
                        echo '<div class="alert alert-danger">' .  $error . '</div>';
                    }

                    //Check If There`s No Error Proceed The Update Operations

                    if (empty($formErrors)) {

                        $stmt2 = $pdo->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? ");
                        $stmt2->execute([$user , $id]);

                        $row = $stmt2->rowCount();

                        if ($row == 1){

                            $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist </div>";
                            redirectHome($theMsg, 'back' );
                        }else {

                            // Update The Datebase With This Info
                            $stmt = $pdo->prepare('UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ? ');
                            $stmt->execute([$user, $email, $name, $pass,  $id]);
                            // Echo Success Message
                        $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';

                        redirectHome($theMsg, 'back', $seconds = 4);
                      }
                    }

                } else {

                    $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                    redirectHome($theMsg );

                        }

                echo "</div>";


            } elseif ($do == 'Delete'){ // Delete Member Page

                echo '<h1 class="text-center">Delete Member</h1>';
                echo "<div class = 'container'>";

                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                $check = checkItem("UserID" , "users", $userid);

                    if ($check > 0) {

                        $stmt = $pdo->prepare('DELETE FROM users WHERE UserID = :userid');
                       $stmt->bindParam(":userid" , $userid);
                        $stmt->execute();

                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted </div>';
                        redirectHome($theMsg , 'back');

                    }else{

                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist </div>";
                        redirectHome($theMsg );
                    }

                echo "</div>";


            } elseif ($do == 'Activate'){ // Activate Member Page

                echo '<h1 class="text-center">Activate Member</h1>';
                echo "<div class = 'container'>";

                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                $check = checkItem("userid" , "users", $userid);

                if ($check > 0) {

                    $stmt = $pdo->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                    $stmt->execute([$userid]);

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Activated </div>';
                    redirectHome($theMsg , 'back');

                }else{

                    $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist </div>";
                    redirectHome($theMsg );
                }

                echo "</div>";

            }

            include $tpl . '/footer.inc.php';

        } else {

            header('Location: index.php');
            exit();
        }

        ob_end_flush(); // Release The Output
?>
