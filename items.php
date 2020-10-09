<?php
        ob_start();
        session_start();

        $pageTitle = 'Show Item';

       include "init.php";

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $pdo->prepare("SELECT
                                                  items.* ,
                                                  users.Username ,
                                                  categories.Name AS  Category_name
                                         FROM
                                                  items
                                         INNER JOIN
                                                  categories
                                         ON      
                                                  categories.ID = items.Cat_ID

                                         INNER JOIN
                                                  users
                                         ON      
                                                  users.UserID  = items.Member_ID      
                                         WHERE 
                                                  Item_ID = ?
                                         AND 
                                                  Approve = 1");
        $stmt->execute([$itemid]);
        $count = $stmt->rowCount();

        if ($count > 0) {

            $item = $stmt->fetch();
    ?>



    <h1 class="text-center"> <?php echo $item['Name']; ?></h1>

            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <img class="card-img img-thumbnail" src="profile.jpeg" alt="" width="300px" height="300px"/>
                    </div>
                    <div class="col-md-9 item-info">
                        <h2><?php echo $item['Name']; ?></h2>
                        <p><?php echo $item['Description']; ?></p>
                        <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Added Date</span> : <?php echo $item['Add_date']; ?>
                        </li>
                        <li>
                            <i class="fa fa-money fa-fw"></i>
                            <span>Price</span> : $<?php echo $item['Price']; ?>
                        </li>
                        <li>
                            <i class="fa fa-flag fa-fw"></i>
                            <span>Made In</span> : <?php echo $item['Country_Made']; ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category</span> : <a href="categories.php?pageid='. <?php echo $item['Cat_ID']; ?> .'"><?php echo $item['Category_name']; ?></a>
                        </li>
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Added By</span> : <a href="#"><?php echo $item['Username']; ?></a>
                        </li>
                        </ul>
                    </div>
                </div>

                <hr class="custom-hr">

            <!-- Start Section Comment -->
                <?php

                if (isset($_SESSION['user'])){




                ?>


                    <div class="row">
                        <div class="col-md-3 offset-md-3">
                            <div class="add-comment">
                                <h4>Add Your Comment</h4>
                                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="post">
                                    <textarea name="comment" required></textarea>
                                    <input class="btn btn-primary" type="submit" value="Add Comment">
                                </form>
                                <?php

                                if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                                    $comm  = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                    $itemid     = $item['Item_ID'];
                                    $userid     = $_SESSION['uid'];

                                    if (!empty($comm)){

                                        $stmt = $pdo->prepare('INSERT INTO comments(comment, status, added_date, item_id, user_id) 
                                                                                    VALUES(:zcomment, 0 , now(), :zitemid, :zmember_id )');
                                        $stmt->execute([
                                            ':zcomment'  => $comm,
                                            ':zitemid'   => $itemid,
                                            ':zmember_id'=> $userid

                                        ]);

                                        if ($stmt){
                                            echo '<div class="alert alert-success " style=" margin-top: 10px; width: 500px">Comment Added</div>';
                                        }
                                    } else {

                                        echo '<div class="alert alert-danger " style=" margin-top: 10px; width: 500px">Comment Is Empty Please, Write Some Thing</div>';

                                    }

                                }

                                ?>

                             </div>
                        </div>
                    </div>

            <!-- End Section Comment -->

                <?php }else{
                    echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment';
                }
                ?>

            <hr class="custom-hr">

                <?php
                $stmt = $pdo->prepare("SELECT
                                                                        comments.* ,
                                                                        users.Username AS member
                                                                FROM
                                                                        comments
                                                                INNER JOIN
                                                                        users        
                                                                ON      
                                                                        users.UserID  = comments.user_id
                                                                WHERE 
                                                                        item_id = ?
                                                                AND 
                                                                        status = 1
                                                                ORDER BY 
                                                                        c_id DESC
                                                                ");

                $stmt->execute([$item['Item_ID']]);
                $comments = $stmt->fetchAll();

                ?>



                  <?php
                      foreach ($comments as $comment){ ?>
                        <div class="comment-box">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <img class="card-img img-thumbnail rounded-circle d-block mx-auto" src="profile.jpeg" alt="" />
                                    <?php echo $comment['member']; ?>
                                </div>
                                <div class="col-sm-10">
                                    <p class="lead"><?php echo $comment['comment'];  ?></p>
                                </div>
                            </div>
                        </div>

                          <hr class="custom-hr">

                     <?php }?>

            </div>








    <?php

             }else{

                echo '<div class="container">';
                echo '<div class="alert alert-danger">There IS No Such ID Or This Item IS Waiting Approval</div>';
                echo '</div>';
            }

            
    include $tpl . 'footer.php';

       ob_end_flush();
       ?>
