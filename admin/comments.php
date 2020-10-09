<?php


    /*
     ** Manage Comments Page
     ** You can Edit | Delete | Approve Comments From Here
    */

        ob_start(); // Output Buffering Start

        session_start();

        $pageTitle = 'Comments';

        if (isset($_SESSION['Username'])){

            include "init.php";


            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            //Start manage Page

            if ($do == 'Manage') {// Manage Members Page

              $stmt = $pdo->prepare("SELECT
                                                comments.* ,
                                                users.Username ,
                                                items.Name AS  item_name
                                        FROM
                                                comments
                                        INNER JOIN
                                                items
                                        ON      items.Item_ID = comments.item_id

                                        INNER JOIN
                                                users        
                                        ON      users.UserID  = comments.user_id
                                        ORDER BY c_id DESC");

                $stmt->execute();
                $comments = $stmt->fetchAll();

                if (!empty($comments)){

                 ?>

                <h1 class="text-center">Manage Comments</h1>
                     <div class="container">
                          <div class="table-responsive">

                                <table class="main-table text-center table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Added Date</th>
                                        <th scope="col">Control</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($comments as $comment) {

                                            echo '<tr>';
                                            echo '<td>' . $comment['c_id']   . '</td>';
                                            echo '<td>' . $comment['comment'] . '</td>';
                                            echo '<td>' . $comment['item_name'] . '</td>';
                                            echo '<td>' . $comment['Username']     . '</td>';
                                            echo '<td>' . $comment['added_date']     . '</td>';

                                            echo "<td>
                                                      <a href='comments.php?do=Edit&comid=". $comment['c_id'] ."' class='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                                                      <a href='comments.php?do=Delete&comid=". $comment['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'> </i>Delete</a>";
                                                        if ($comment['status'] == 0) {

                                                          echo  "<a href='comments.php?do=Approve&comid=". $comment['c_id'] ."' class='btn btn-info activate'> <i class='fa fa-check'></i>Approve</a>";

                                                        }

                                            echo    "</td>";
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                          </div>
                     </div>

                <?php  }else{

                    echo '<div class="container">';
                        echo '<div class="nice-message">There IS No Comments To Show</div>';
                    echo '</div>';

                } ?>


         <?php   }  elseif ($do == 'Edit') {// edit Comment Page

                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                $stmt = $pdo->prepare("SELECT * FROM comments WHERE c_id = ? ");
                $stmt->execute([$comid]);
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) {

                ?>
                        <h1 class="text-center">Edit Comment</h1>
                        <div class="container">
                        <form action="?do=Update" method="post">
                            <input type="hidden" name="comid" value="<?php echo $comid ?>">
                            <div class="form-group">
                                <label class="col-sm-2">Comment</label>
                                <textarea class="form-control" name='comment'><?php echo $row['comment'] ?></textarea>
                                  <br>
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


            } elseif ($do == 'Update'){ // Update Comment  Page

                echo '<h1 class="text-center">Update Comment</h1>';

                echo "<div class = 'container'>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    //Get Variables From the Form

                    $comid = $_POST['comid'];
                    $com = $_POST['comment'];

                            // Update The Datebase With This Info
                            $stmt = $pdo->prepare('UPDATE comments SET comment = ? WHERE c_id = ? ');
                            $stmt->execute([$com, $comid]);

                            // Echo Success Message
                            $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';

                            redirectHome($theMsg, 'back', $seconds = 4);

                } else {

                    $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                    redirectHome($theMsg );

                        }

                echo "</div>";


            } elseif ($do == 'Delete'){ // Delete Comment Page

                echo '<h1 class="text-center">Delete Comment</h1>';
                echo "<div class = 'container'>";

                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                $check = checkItem("c_id" , "comments", $comid);

                    if ($check > 0) {

                        $stmt = $pdo->prepare('DELETE FROM comments WHERE c_id = :comid');
                       $stmt->bindParam(":comid" , $comid);
                        $stmt->execute();

                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted </div>';
                        redirectHome($theMsg , 'back');

                    }else{

                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist </div>";
                        redirectHome($theMsg  );
                    }

                echo "</div>";


            } elseif ($do == 'Approve'){ // Activate Comment Page

                echo '<h1 class="text-center">Approve Comment</h1>';
                echo "<div class = 'container'>";

                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                $check = checkItem("c_id" , "comments", $comid);

                if ($check > 0) {

                    $stmt = $pdo->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                    $stmt->execute([$comid]);

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Approved </div>';
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
