<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php"><?php echo lang('HOME_ADMIN') ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" > <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES') ?> </a></li>
            <li class="nav-item" > <a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?> </a></li>
            <li class="nav-item" > <a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?> </a></li>
            <li class="nav-item" > <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?> </a></li>
            <li class="nav-item" > <a class="nav-link" href="dashboard.php"><?php echo lang('DASHBOARD') ?> </a></li>


            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo "mahmoud"; ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a>
                    <a class="dropdown-item" href="#">Setting</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>

        </ul>
    </div>
</nav>
