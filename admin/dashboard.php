     <?php

     ob_start(); // Output buffering start
     session_start();


    if (isset($_SESSION['Username'])){

        $pageTitle = 'Dashboard';

        include "init.php";

        $numUsers = 4; // number of latest users

        $latestUsers = getLatest("*", "users", "UserID", $numUsers); //latest users array

        $numItems = 4; // number of latest Items

        $latestItems = getLatest("*", "items", "Item_ID", $numItems); //latest Items array

        $numComments = 4; // number of latest Comments


        // Start Dashboard Page
     ?>
            <div class="container home-stats text-center">
                <h1>Dashboard</h1>
                <div class="row">

                    <div class="col-md-3">
                            <div class="stat st-members">
                              <i class="fa fa-users"></i>
                              <div class="info">
                                Total Members
                                <span><a href="members.php"><?php echo countItems('UserID' , 'users') ?></a></span>
                              </div>
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class="fa fa-users"></i>
                            <div class="info">
                              Pending Members
                              <span><a href="members.php?do=Manage&page=Pending">
                                      <?php echo checkItem("RegStatus", "users", 0) ?>
                                  </a></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                          Total Items
                          <span><a href="items.php"><?php echo countItems('Item_ID' , 'items') ?></a></span>

                        </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-comments">
                          <i class="fa fa-comments"></i>
                          <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems('c_id' , 'comments') ?></a></span>

                          </div>
                        </div>
                    </div>

               </div>
            </div>

            <div class="container latest">
                <div class="row">

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fa fa-users"></i> Latest <?= $numUsers ?> Registered Users
                                            <span class="toggle-info pull-right">
                                              <i class="fa fa-plus fa-lg"></i>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled latest-users">
                                            <?php
                                            if(!empty($latestUsers)) {

                                                foreach ($latestUsers as $user) {
                                                    echo '<li>' . $user['Username'];
                                                    echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                                    echo '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i>Edit';

                                                    if ($user['RegStatus'] == 0) {

                                                        echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' class='btn btn-info pull-right activate'> <i class='fa fa-check'></i>Activate</a>";

                                                    }

                                                    echo '</span>';
                                                    echo '</a>';
                                                    echo '</li>';
                                                }
                                            } else {

                                                echo "There \`S No Users To Show";
                                            }

                                            ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fa fa-tag"></i> Latest <?= $numItems ?>  Items
                                            <span class="toggle-info pull-right">
                                              <i class="fa fa-plus fa-lg"></i>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                          <ul class="list-unstyled latest-users">
                                          <?php
                                         if(!empty($latestItems)) {
                                             foreach ($latestItems as $item) {
                                                 echo '<li>' . $item['Name'];
                                                 echo '<a href="members.php?do=Edit&userid=' . $item['Item_ID'] . '">';
                                                 echo '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i>Edit';

                                                 if ($item['Approve'] == 0) {

                                                     echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info pull-right activate'> <i class='fa fa-check'></i>Activate</a>";

                                                 }

                                                 echo '</span>';
                                                 echo '</a>';
                                                 echo '</li>';
                                             }
                                         }else {

                                             echo "There \`S No Items To Show";
                                         }
                                          ?>
                                          </ul>
                                        </div>
                                    </div>
                                </div>
                </div>
                    <!-- Start Latest Comments -->
                    <br>
                    <div class="row">
                         <div class="col-sm-6">
                             <div class="card">
                                <div class="card-header">
                                <i class="fa fa-comments"></i> Latest <?= $numComments ?>  Items Comments
                                <span class="toggle-info pull-right">
                                  <i class="fa fa-plus fa-lg"></i>
                                </span>
                                 </div>
                                 <div class="card-body">

                                            <?php
                                            $stmt = $pdo->prepare("SELECT
                                                            comments.* ,
                                                            users.Username
                                                            FROM
                                                            comments
                                                            INNER JOIN
                                                            users
                                                            ON      users.UserID  = comments.user_id
                                                            ORDER BY c_id DESC
                                                            LIMIT $numComments");

                                            $stmt->execute();
                                            $comments = $stmt->fetchAll();

                                         if (!empty($comments)){

                                            foreach ($comments as $comment){

                                                echo '<div class="comment-box">';
                                                echo '<span class="member-n" >
                                                      <a href="members.php?do=Edit&userid= ' .$comment['user_id']  . '">
                                                    ' . $comment['Username'] . '</a></span>';

                                                echo '<p class="member-c" >' . $comment['comment'] . '</p>';
                                                echo '</div>';
                                            }
                                         }else {

                                             echo "There \`S No Comments To Show";
                                         }

                                             ?>

                                 </div>
                              </div>
                         </div>
                    </div>
                    <!-- End Latest Comments -->

        </div>

        <?php
        // End Dashboard Page

        include $tpl . 'footer.inc.php';

    } else {

        header('Location: index.php');
        exit();
    }

    ob_end_flush();
