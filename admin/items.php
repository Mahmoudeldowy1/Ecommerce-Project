<?php

        /*
         ** Manage Items Page
         ** You can Add | Edit | Delete Items From Here
        */

        ob_start(); // Output Buffering Start

        session_start();

        $pageTitle = 'Items';


        if (isset($_SESSION['Username'])) {

            include "init.php";


            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


                if ($do == 'Manage') { // Manage Items Page


                    $stmt = $pdo->prepare("SELECT
                                                            items.* ,
                                                            users.Username ,
                                                            categories.Name AS  Category_name
                                                    FROM
                                                            items
                                                    INNER JOIN
                                                            categories
                                                    ON      categories.ID = items.Cat_ID

                                                    INNER JOIN
                                                            users
                                                    ON      
                                                            users.UserID  = items.Member_ID
                                                    ORDER BY 
                                                            Item_ID DESC");

                    $stmt->execute();
                    $items = $stmt->fetchAll();

                    if (!empty($items)){

                    ?>

                    <h1 class="text-center">Manage Items</h1>
                    <div class="container">
                        <div class="table-responsive">

                            <table class="main-table text-center table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Country made</th>
                                    <th scope="col">Adding Date</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Control</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($items as $item) {

                                    echo '<tr>';
                                    echo '<td>' . $item['Item_ID']          . '</td>';
                                    echo '<td>' . $item['Name']             . '</td>';
                                    echo '<td>' . $item['Description']      . '</td>';
                                    echo '<td>' . $item['Price']            . '</td>';
                                    echo '<td>' . $item['Country_Made']     . '</td>';
                                    echo '<td>' . $item['Add_date']         . '</td>';
                                    echo '<td>' . $item['Username']         . '</td>';
                                    echo '<td>' . $item['Category_name']    . '</td>';
                                    echo "<td>
                                                      <a href='items.php?do=Edit&itemid=". $item['Item_ID'] ."' class='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                                                      <a href='items.php?do=Delete&itemid=". $item['Item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'> </i>Delete</a>";
                                    if ($item['Approve'] == 0) {

                                        echo  "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] ."' class='btn btn-info activate'> <i class='fa fa-check'></i>Approve</a>";

                                    }

                                    echo    "</td>";
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>

                            <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Item</a>
                        </div>
                    </div>

                    <?php  }else{

                        echo '<div class="container">';
                        echo '<div class="nice-message">There IS No Items To Show</div>';
                        echo '<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Item</a>';
                        echo '</div>';

                    } ?>

                    <?php

                    }elseif ($do == 'Add') {  // Add Item page ?>

                <h1 class="text-center">Add New Item</h1>
                <div class="container">
                    <form action="?do=Insert" method="post">
                        <div class="form-group input-container">
                            <label class="col-sm-2">Name</label>
                            <input  type="text" class="form-control" name="name" required="required" placeholder="Name Of Item">
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Description</label>
                            <input  type="text" class="form-control"  name="description" required="required" placeholder="Description Of Item">
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Price</label>
                            <input type="text" class="form-control" name="price" required="required" placeholder="Price Of Item">
                        </div>
                        <div class="form-group input-container">
                            <label for="exampleInputPassword1">Country</label>
                            <input type="text" class="form-control" name="country" required="required" placeholder="Country Of Made">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Status</label>
                            <select class="form-control" name="status" >
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">LikeNew</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Member</label>
                            <select class="form-control" name="member" >
                                <option value="0">...</option>
                               <?php
                                    $stmt = $pdo->prepare('SELECT * FROM users');
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user){
                                        echo "<option value='" . $user['UserID'] . "'>" . $user['Username']. "</option>";
                                    }
                               ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Category</label>
                            <select class="form-control" name="category" >
                                <option value="0">...</option>
                                <?php
                                $stmt2 = $pdo->prepare('SELECT * FROM categories');
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat){
                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']. "</option>";
                                }
                                ?>
                            </select>
                        </div>


                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </form>
                </div>

                <?php
            }elseif ($do == 'Insert') {

                //Insert Member Page


                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo '<h1 class="text-center">Add Item</h1>';
                    echo "<div class = 'container'>";

                    //Get Variables From the Form
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price= $_POST['price'];
                    $country = $_POST['country'];
                    $status = $_POST['status'];
                    $member = $_POST['member'];
                    $category = $_POST['category'];



                    //Validate The Form

                    $formErrors = [];


                    if (empty($name)){
                        $formErrors[] = 'Name cant be <strong>empty</strong>';
                    }

                    if (empty($description)){
                        $formErrors[] = 'Description cant be <strong>empty</strong>';
                    }

                    if (empty($price)){
                        $formErrors[] = 'Price cant be <strong>empty</strong>';
                    }

                    if (empty($country)){
                        $formErrors[] = 'Country of Made cant be <strong>empty</strong>';
                    }

                    if ($status == 0){
                        $formErrors[] = 'You Must Choose The <strong>Status</strong>';
                    }

                    if ($member == 0){
                        $formErrors[] = 'You Must Choose The <strong>Member</strong>';
                    }
                    if ($category == 0){
                        $formErrors[] = 'You Must Choose The <strong>Category</strong>';
                    }


                    // Loop Into Errors Array And Echo It

                    foreach ($formErrors as $error)
                    {
                        echo '<div class="alert alert-danger">' .  $error . '</div>';
                    }


                    //Check If There`s No Error Proceed The Update Operations

                    if (empty($formErrors)) {

                            // Insert Item Info Into Database

                            $stmt = $pdo->prepare('INSERT INTO items(Name , Description , Price , Country_Made ,Status ,  Add_date , Cat_ID , Member_ID)
                                                            VALUES (:name , :description , :price , :country, :status, now() , :cat , :member)');

                            $stmt->execute([
                                'name' => $name,
                                'description' => $description,
                                'price' => $price,
                                'country' => $country,
                                'status' => $status,
                                'cat' => $category,
                                'member' => $member

                            ]);
                            // Echo Success Message
                            $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted </div>';
                            redirectHome($theMsg, $url = 'back', $seconds = 4);

                    }

                } else {

                    echo "<div class = 'container'>";
                    $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                    redirectHome($theMsg);
                    echo "</div>";
                }
                echo "</div>";



            }elseif ($do == 'Edit') { // edit Item Page


                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                    $stmt = $pdo->prepare("SELECT * FROM items WHERE Item_ID = ? ");
                    $stmt->execute([$itemid]);
                    $item = $stmt->fetch();
                    $count = $stmt->rowCount();

                    if ($count > 0) {

                        ?>

                    <h1 class="text-center">Edit Item</h1>
                    <div class="container">
                        <form action="?do=Update" method="post">
                            <input type="hidden" name="id" value="<?php echo $itemid ?>">
                            <div class="form-group input-container">
                                <label class="col-sm-2">Name</label>
                                <input  value="<?php echo $item['Name'] ?>"  type="text" class="form-control" name="name" required="required" placeholder="Name Of Item">
                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Description</label>
                                <input  type="text" value="<?php echo $item['Description'] ?>" class="form-control"  name="description" required="required" placeholder="Description Of Item">

                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Price</label>
                                <input value="<?php echo $item['Price'] ?>"  type="text" class="form-control" name="price" required="required" placeholder="Price Of Item">
                            </div>
                            <div class="form-group input-container">
                                <label for="exampleInputPassword1">Country</label>
                                <input value="<?php echo $item['Country_Made'] ?>"  type="text" class="form-control" name="country" required="required" placeholder="Country Of Made">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Status</label>
                                <select class="form-control" name="status" >
                                    <option value="1" <?php if ($item['Status'] == 1){echo 'selected';} ?> >New</option>
                                    <option value="2" <?php if ($item['Status'] == 2){echo 'selected';} ?> >LikeNew</option>
                                    <option value="3" <?php if ($item['Status'] == 3){echo 'selected';} ?> >Used</option>
                                    <option value="4" <?php if ($item['Status'] == 4){echo 'selected';} ?> >Old</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Member</label>
                                <select class="form-control" name="member" >
                                    <option value="0">...</option>
                                    <?php
                                    $stmt = $pdo->prepare('SELECT * FROM users');
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user){
                                        echo "<option value='" . $user['UserID'] . "'";   if ($item['Member_ID'] == $user['UserID']){echo 'selected';}  echo ">" . $user['Username']. "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Category</label>
                                <select class="form-control" name="category" >
                                    <option value="0">...</option>
                                    <?php
                                    $stmt2 = $pdo->prepare('SELECT * FROM categories');
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'";   if ($item['Cat_ID'] == $cat['ID']){echo 'selected';}  echo ">" . $cat['Name']. "</option>";

                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Item</button>
                        </form>


                    <?php
                        $stmt = $pdo->prepare("SELECT
                        comments.* ,
                        users.Username
                        FROM
                        comments
                        INNER JOIN
                        users
                        ON      users.UserID  = comments.user_id
                         WHERE item_id = ?");

                        $stmt->execute([$itemid]);
                        $rows = $stmt->fetchAll();

                        if (!empty($rows)) {

                        ?>

                        <h1 class="text-center">Manage [<?php echo $item['Name'] ?>] Comments</h1>

                            <div class="table-responsive">

                                <table class="main-table text-center table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">Comment</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Added Date</th>
                                        <th scope="col">Control</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($rows as $row) {

                                        echo '<tr>';
                                        echo '<td>' . $row['comment'] . '</td>';
                                        echo '<td>' . $row['Username']     . '</td>';
                                        echo '<td>' . $row['added_date']     . '</td>';

                                        echo "<td>
                                                      <a href='comments.php?do=Edit&comid=". $row['c_id'] ."' class='btn btn-success'> <i class='fa fa-edit'></i>Edit</a>
                                                      <a href='comments.php?do=Delete&comid=". $row['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'> </i>Delete</a>";
                                        if ($row['status'] == 0) {

                                            echo  "<a href='comments.php?do=Approve&comid=". $row['c_id'] ."' class='btn btn-info activate'> <i class='fa fa-check'></i>Approve</a>";

                                        }

                                        echo    "</td>";
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php }else{

                        echo '<div class="container">';
                        echo '<div class="nice-message">There IS No Comments To Show</div>';
                        echo '</div>';

                    } ?>

                    </div>

                <?php

                    }  }elseif ($do == 'Update') { // Update Item  Page

                    echo '<h1 class="text-center">Update Item</h1>';

                    echo "<div class = 'container'>";

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        //Get Variables From the Form
                        $item_Id =       $_POST['id'];
                        $name =          $_POST['name'];
                        $description =   $_POST['description'];
                        $price=          $_POST['price'];
                        $country =       $_POST['country'];
                        $status =        $_POST['status'];
                        $member =        $_POST['member'];
                        $category =      $_POST['category'];

                        //Validate The Form

                        $formErrors = [];


                        if (empty($name)){
                            $formErrors[] = 'Name cant be <strong>empty</strong>';
                        }

                        if (empty($description)){
                            $formErrors[] = 'Description cant be <strong>empty</strong>';
                        }

                        if (empty($price)){
                            $formErrors[] = 'Price cant be <strong>empty</strong>';
                        }

                        if (empty($country)){
                            $formErrors[] = 'Country of Made cant be <strong>empty</strong>';
                        }

                        if ($status == 0){
                            $formErrors[] = 'You Must Choose The <strong>Status</strong>';
                        }

                        if ($member == 0){
                            $formErrors[] = 'You Must Choose The <strong>Member</strong>';
                        }
                        if ($category == 0){
                            $formErrors[] = 'You Must Choose The <strong>Category</strong>';
                        }


                        // Loop Into Errors Array And Echo It

                        foreach ($formErrors as $error)
                        {
                            echo '<div class="alert alert-danger">' .  $error . '</div>';
                        }


                        //Check If There`s No Error Proceed The Update Operations

                        if (empty($formErrors)) {

                            // Update The Datebase With This Info
                            $stmt = $pdo->prepare('UPDATE items SET
                                                                            Name = ? ,
                                                                            Description = ? ,
                                                                            Price = ? ,
                                                                            Country_Made = ?,
                                                                            Status = ?,
                                                                            Cat_ID = ? ,
                                                                            Member_ID = ?
                                                                        WHERE
                                                                            Item_ID = ? ');

                            $stmt->execute([$name, $description, $price,  $country , $status , $category , $member, $item_Id]);
                            // Echo Success Message
                            $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';

                            redirectHome($theMsg, 'back', $seconds = 4);
                        }


                    } else {

                        $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                        redirectHome($theMsg );

                    }

                    echo "</div>";


                } elseif ($do == 'Delete'){ // Delete Item Page

                    echo '<h1 class="text-center">Delete Item</h1>';
                    echo "<div class = 'container'>";

                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                    $check = checkItem("Item_ID" , "items", $itemid);

                    if ($check > 0) {

                        $stmt = $pdo->prepare('DELETE FROM items WHERE Item_ID = :itemid');
                        $stmt->bindParam(":itemid" , $itemid);
                        $stmt->execute();

                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted </div>';
                        redirectHome($theMsg, 'back');

                    }else{

                        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist </div>";
                        redirectHome($theMsg );
                    }

                    echo "</div>";


            }elseif ($do == 'Approve') {

                  echo '<h1 class="text-center">Approve Item</h1>';
                  echo "<div class = 'container'>";

                  $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                  $check = checkItem("Item_ID" , "items", $itemid);

                  if ($check > 0) {

                      $stmt = $pdo->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
                      $stmt->execute([$itemid]);

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
