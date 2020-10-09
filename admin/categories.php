<?php

        /*
         ** Manage Category Page
         ** You can Add | Edit | Delete Category From Here
        */

        ob_start(); // Output Buffering Start

        session_start();

        $pageTitle = 'Categories';


        if (isset($_SESSION['Username'])) {

            include "init.php";


            $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

            if ($do == 'Manage'){

                $sort ='ASC';

                $sort_array = ['DESC' , 'ASC'];
                if (isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
                    $sort = $_GET['sort'];
                }

                $stmt2 = $pdo->prepare("SELECT *  FROM categories ORDER BY Ordering $sort ");
                $stmt2->execute();
                $cats = $stmt2->fetchAll();

                if (!empty($cats)){

                ?>

                <h1 class="text-center">Manage Categories</h1>
                <div class="container categories">
                    <div class="card ">
                        <div class="card-header">
                            <i class="fa fa-edit"></i>Manage Categories
                            <div class="option pull-right">
                                <i class="fa fa-sort"></i> Ordering: [
                                <a class="<?php if ($sort=='ASC'){echo 'active';} ?>" href="?sort=ASC">Asc</a> |
                                <a class="<?php if ($sort=='DESC'){echo 'active';} ?>" href="?sort=DESC">Desc</a> ]
                                <i class="fa fa-eye"></i> View: [
                                <span class="active" data-view = "full" >Full</span> |
                                <span data-view = "classic" >Classic</span> ]
                            </div>
                        </div>
                            <div class="card-body">
                            <?php

                            foreach ($cats as $cat) {
                                echo '<div class="cat">';
                                        echo '<div class="hidden-buttons">';
                                               echo '<a href="categories.php?do=Edit&catid='. $cat['ID'] .'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit</a>';
                                               echo '<a href="categories.php?do=Delete&catid='. $cat['ID'] .'" class="confirm btn btn-sm btn-danger"><i class="fa fa-close"></i>Delete</a>';
                                        echo '</div>';
                                        echo  '<h3>'. $cat['Name'] . '</h3>';
                                        echo '<div class="full-view">';
                                            echo  '<p> ';if ($cat['Description']==''){echo'this category has no description';}else{echo $cat['Description'];} echo '</p>';
                                            if ($cat['Visibility']==1){echo '<span class="visibility"><i class="fa fa-eye"></i>Hidden</span>'; }
                                            if ($cat['Allow_Comment']==1){echo '<span class="commenting"><i class="fa fa-close"></i>Comment display</span>'; }
                                            if ($cat['Allow_Ads']==1){echo '<span class="advertises"><i class="fa fa-close"></i>Advertises display</span>'; }
                                        echo '</div>';
                                echo '</div>';
                                echo '<hr>';


                            }

                            ?>

                         </div>
                     </div>
                    <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
                </div>

                <?php  }else{

                    echo '<div class="container">';
                    echo '<div class="nice-message">There IS No Categories To Show</div>';
                    echo '<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>';
                    echo '</div>';

                } ?>

            <?php

            }elseif ($do == 'Add') { //Add Category Page ?>

                <h1 class="text-center">Add New Category</h1>
                <div class="container pb-5">
                    <form action="?do=Insert" method="post">

                        <div class="form-group">
                            <label class="col-sm-2 ">Name</label>
                            <input  type="text" class="form-control" name="name"  placeholder="Name Of The Category">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <input  type="text" class="form-control" name="description" placeholder="Describe the Category">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Ordering</label>
                            <input type="text" class="form-control" name="ordering"  placeholder="Number To Arrange The Categories">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Visibility</label>
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" />
                                <label for="vis-no">No</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Allow Commenting</label>
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" />
                                <label for="com-no">No</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Allow Ads</label>
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" />
                                <label for="ads-no">No</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Category</button>

                    </form>
                </div>



            <?php }elseif ($do == 'Insert') {

                //Insert Category Page


                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo '<h1 class="text-center">Add Category</h1>';
                    echo "<div class = 'container'>";

                    //Get Variables From the Form
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $ordering = $_POST['ordering'];
                    $visibility = $_POST['visibility'];
                    $allowComment = $_POST['commenting'];
                    $allowAds = $_POST['ads'];

                    //Check if name is Not Empty

                    if (!empty($name)) {

                    // Check If Category Exist in Database

                    $check = checkItem("name", "categories", $name);

                    if ($check == 1) {

                        $theMsg = "<div class='alert alert-danger' >Sorry This Category Is Exist </div>";
                        redirectHome($theMsg, 'back');

                    } else {

                        // Insert Category Info Into Database

                        $stmt = $pdo->prepare('INSERT INTO categories (Name , Description, Ordering, Visibility, Allow_Comment,  Allow_Ads ) VALUES (:name , :description , :order , :visible, :comment, :ads)');
                        $stmt->execute([
                            'name' => $name,
                            'description' => $description,
                            'order' => $ordering,
                            'visible' => $visibility,
                            'comment' => $allowComment,
                            'ads' => $allowAds
                        ]);                        // Echo Success Message
                        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted </div>';
                        redirectHome($theMsg, $url = 'back', $seconds = 4);
                    }

                    } else {

                        $theMsg = '<div class="alert alert-danger"> Name Cant Be Empty  </div>';
                        redirectHome($theMsg, $url = 'back', $seconds = 4);
                    }


                }


                } elseif ($do == 'Edit') {

                // edit Category Page

                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

                $stmt = $pdo->prepare("SELECT * FROM categories WHERE ID = ? ");
                $stmt->execute([$catid]);
                $cat = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) {

                    ?>
                        <h1 class="text-center">Edit Category</h1>
                        <div class="container pb-5">
                            <form action="?do=Update" method="post">

                                <input type="hidden" name="catid" value="<?php echo $catid; ?>">

                                <div class="form-group">
                                    <label class="col-sm-2 ">Name</label>
                                    <input value="<?php echo $cat['Name']; ?>" type="text" class="form-control" name="name"  placeholder="Name Of The Category">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Description</label>
                                    <input value="<?php echo $cat['Description']; ?>"  type="text" class="form-control" name="description" placeholder="Describe the Category">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Ordering</label>
                                    <input value="<?php echo $cat['Ordering']; ?>"   type="text" class="form-control" name="ordering"  placeholder="Number To Arrange The Categories">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Visibility</label>
                                    <div>
                                        <input id="vis-yes" type="radio" name="visibility" value="0" checked <?php if ($cat['Visibility'] == 0) {echo 'checked';} ?>/>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {echo 'checked';} ?>/>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Allow Commenting</label>
                                    <div>
                                        <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {echo 'checked';} ?> />
                                        <label for="com-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {echo 'checked';} ?>/>
                                        <label for="com-no">No</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Allow Ads</label>
                                    <div>
                                        <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {echo 'checked';} ?> />
                                        <label for="ads-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {echo 'checked';} ?>/>
                                        <label for="ads-no">No</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>

                            </form>
                        </div>

                    <?php
                } else{
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-danger'>Theres No Such ID </div>";
                    redirectHome($theMsg );
                    echo "</div>";
                }



            }elseif ($do == 'Update') { // Update Category  Page

                echo '<h1 class="text-center">Category Member</h1>';

                echo "<div class = 'container'>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    //Get Variables From the Form
                    $categoryid  = $_POST['catid'];
                    $cat_name    = $_POST['name'];
                    $description = $_POST['description'];
                    $order       = $_POST['ordering'];
                    $visibility  = $_POST['visibility'];
                    $allow_comm  = $_POST['commenting'];
                    $allow_ads   = $_POST['ads'];



                    //Check If There`s No Error Proceed The Update Operations

                    if (!empty($cat_name)) {

                        // Update The Datebase With This Info
                        $stmt = $pdo->prepare('UPDATE categories SET Name = ? , Description = ? , Ordering = ? , Visibility  = ? , Allow_Comment = ?, Allow_Ads = ? WHERE ID = ? ');
                        $stmt->execute([$cat_name, $description, $order, $visibility, $allow_comm, $allow_ads,  $categoryid]);

                        // Echo Success Message
                        $theMsg =  '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>';

                        redirectHome($theMsg, 'back', $seconds = 4);
                    }


                } else {

                    $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                    redirectHome($theMsg );

                }

                echo "</div>";


            } elseif ($do == 'Delete'){ // Delete Member Page

                echo '<h1 class="text-center">Delete Member</h1>';
                echo "<div class = 'container'>";

                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

                $check = checkItem("ID" , "categories", $catid);

                if ($check > 0) {

                    $stmt = $pdo->prepare('DELETE FROM categories WHERE ID = :id');
                    $stmt->bindParam(":id" , $catid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted </div>';
                    redirectHome($theMsg , 'back' , 2);

                }else{

                    $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist </div>";
                    redirectHome($theMsg, 'back' , 3);
                }

                echo "</div>";



            }elseif ($do == 'Delete') {


            }


            include $tpl . 'footer.inc.php';

        } else {


            header('Location: index.php');
            exit();
        }

        ob_end_flush(); // Release The Output


?>
