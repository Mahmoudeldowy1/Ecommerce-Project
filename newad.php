<?php
        ob_start();
        session_start();

        $pageTitle = 'Create New Item';

       include "init.php";

       if (isset($_SESSION['user'])){


           if ($_SERVER['REQUEST_METHOD'] == 'POST'){

               $formError = [];

               //Get Variables From the Form
               $name     = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
               $desc     = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
               $price    = filter_var($_POST['price'] , FILTER_SANITIZE_NUMBER_INT);
               $country  = filter_var($_POST['country'] , FILTER_SANITIZE_STRING);
               $status   = filter_var($_POST['status'] , FILTER_SANITIZE_STRING);
               $category = filter_var($_POST['category'] , FILTER_SANITIZE_STRING);

               if (empty($name)){
                   $formErrors[] = 'Name cant be <strong>empty</strong>';
               }

               if (empty($desc)){
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

               if ($category == 0){
                   $formErrors[] = 'You Must Choose The <strong>Category</strong>';
               }

               if (empty($formErrors)) {

                   // Insert Item Info Into Database

                   $stmt = $pdo->prepare('INSERT INTO items(Name , Description , Price , Country_Made ,Status ,  Add_date , Cat_ID , Member_ID)
                                                            VALUES (:name , :description , :price , :country, :status, now() , :cat , :member)');

                   $stmt->execute([
                       'name' => $name,
                       'description' => $desc,
                       'price' => $price,
                       'country' => $country,
                       'status' => $status,
                       'cat' => $category,
                       'member' => $_SESSION['uid']

                   ]);

                   if ($stmt){
                       $succesMes = 'Item Has Been Added';
                   }

               }

           }

    ?>

    <h1 class="text-center"><?= $pageTitle ?></h1>

    <div class="create-ad block">
        <div class="container">
            <div class="card">
                <div class="card-header"><?= $pageTitle ?></div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8">
                            <form class="main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                <div class="form-group input-container">
                                    <label class="col-sm-2">Name</label>
                                    <input  type="text" class="form-control live-name" name="name" required="required" placeholder="Name Of Item">
                                </div>
                                <div class="form-group input-container">
                                    <label for="exampleInputPassword1">Description</label>
                                    <input  type="text" class="form-control live-desc"  name="description" required="required" placeholder="Description Of Item">
                                </div>
                                <div class="form-group input-container">
                                    <label for="exampleInputPassword1">Price</label>
                                    <input type="text" class="form-control live-price" name="price" required="required" placeholder="Price Of Item">
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
                                    <label for="exampleInputPassword1">Category</label>
                                    <select class="form-control" name="category" >
                                        <option value="0">...</option>
                                        <?php

                                        $cats =  getAllForm('*','categories', '' , 'ID');
                                        foreach ($cats as $cat){
                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']. "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </form>

                        </div>
                        <div class="col-md-4">
                            <div class="img-thumbnail item-box live-preview" >
                                <span class="price-tag">$
                                    <span>0</span>
                                </span>
                                <img class="card-img" src="profile.jpeg" alt="" width="300px" height="300px"/>
                                <div class="caption">
                                    <h3>title</h3>
                                    <p>description</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Looping Through Errors -->
                    <?php
                        if (!empty($formError)){
                            foreach ($formError as $error){
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }

                    if (isset($succesMes)){

                        echo '<div class="alert alert-success">' . $succesMes . '</div>';

                    }
                    ?>
                    <!-- End Looping Through Errors -->
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
