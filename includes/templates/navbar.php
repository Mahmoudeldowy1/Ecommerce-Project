
    <!-- Upper Nav Bar -->
    <div class="upper-bar">
       <div class="container text-right">
           <?php
           if (isset($_SESSION['user'])){ ?>

               <img class="img-thumbnail rounded-circle" src="profile.jpeg" alt="" width="35px" height="35px"/>
               <div class="btn-group ">

                   <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <?php echo $sessionUser; ?>
                   </button>
                   <div class="dropdown-menu">
                       <a class="dropdown-item" href="profile.php">My Profile</a>
                       <a class="dropdown-item" href="newad.php">New Item</a>
                       <a class="dropdown-item" href="profile.php#my-ads">My Items</a>
                       <a class="dropdown-item" href="logout.php">Logout</a>
                   </div>
               </div>

        <?php

           } else {

           ?>
           <a href="login.php">
               <span >Login/Signup</span>
           </a>
           <?php } ?>
       </div>
    </div>


    <!-- Down Nav Bar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto ">
            <?php
                foreach (getCat() as $cat){

                   echo '<li class="nav-item" > <a class="nav-link" href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . ' </a></li>';
                }


            ?>

        </ul>
    </div>
</nav>
