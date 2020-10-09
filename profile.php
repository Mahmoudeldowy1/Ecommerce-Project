<?php
        ob_start();
        session_start();

        $pageTitle = 'Profile';

       include "init.php";

       if (isset($_SESSION['user'])){

           $getUser = $pdo->prepare('SELECT * FROM users WHERE Username = ?');

           $getUser->execute([$sessionUser]);

           $info = $getUser->fetch();
    ?>

    <h1 class="text-center"> My Profile</h1>

    <div class="information block">
        <div class="container">
            <div class="card">
                <div class="card-header">My Information </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login Name</span> : <?= $info['Username']; ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <span>Email</span> : <?= $info['Email']; ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Full Name</span> : <?= $info['FullName']; ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Register Date</span> : <?= $info['Date']; ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Fav Category</span> :
                        </li>

                    </ul>
                    <a href="#" class="btn btn-dark">Edit Information</a>
                </div>
            </div>
        </div>
    </div>

    <div id="my-ads" class="my-adv block">
        <div class="container">
            <div class="card">
                <div class="card-header">My Items </div>
                <div class="card-body">

                    <?php
                    $items = getItems('Member_ID' , $info['UserID'] , 1);

                    if (!empty($items)) {
                        echo'<div class="row">';
                        foreach ($items as $item) {
                            echo '<div class="col-sm-6 col-md-3" >';
                            echo '<div class="img-thumbnail item-box" >';
                            if ($item['Approve'] == 0){
                                 echo '<span class="approve-status">Waiting Approve</span>';
                            }
                            echo '<span class="price-tag">$' . $item['Price'] . ' </span>';
                            echo '<img class="card-img" src="profile.jpeg" alt="" width="300px" height="300px"/>';
                            echo '<div>';
                            echo '<h3><a href="items.php?itemid=' .  $item['Item_ID'].'">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_date'] . '</div>';
                            echo '</div>';

                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {

                        echo 'There\'s No Items To Show, Create <a href="newad.php">New Ads</a>';

                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <div class="my-comments block">
        <div class="container">
            <div class="card">
                <div class="card-header">Latest Comments </div>
                <div class="card-body">
                    <?php
                        $stmt = $pdo->prepare("SELECT comment FROM comments WHERE user_id = ?");

                        $stmt->execute([$info['UserID']]);
                        $comments = $stmt->fetchAll();

                        if (!empty($comments)){
                            foreach ($comments as $comment){
                                echo '<p>' . $comment['comment'] . '</p>';
                            }

                        } else {
                            echo 'There\'s No Comments To Show';
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>


    <?php
       }else{

           header('Location: login.php');
           exit();
       }


    include $tpl . 'footer.php';

       ob_end_flush();
       ?>
