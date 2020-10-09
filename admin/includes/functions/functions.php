<?php

    /*
     * Title function v1.0
     * Title function that echo the page title in case the page
     * Has the variable $pageTitle and echo default title for other pages
     */

    function getTitle()
    {
        global $pageTitle;

        if (isset($pageTitle)){
            echo $pageTitle;
        } else {
            echo 'Default';
        }
    }

    /*
     * Home Redirect Function v1.0
     * This Function Accept Parameters
     * $theMsg = Echo The Error Message [ error | success | warning ]
     * $url = The Link You Want To Redirect To
     * $Seconds = Seconds Before Redirecting
     */

        function redirectHome($theMsg, $url = null, $seconds = 4){

            if ($url == null) {
                $url = 'index.php';
                $link = 'Home Page';
            }else {

                if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
                    $url = $_SERVER['HTTP_REFERER'];
                    $link = 'Previous Page';

                } else {
                    $url = 'index.php';
                    $link = 'Home Page';

                }
            }

           echo $theMsg;

           echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds</div>";

           header("refresh:$seconds;url=$url");

           exit();
        }


        /*
         * Check Items Function v1.0
         * Function To Check Item In Database [ Function Accept Parameters ]
         * $Select = The Item To Select [ Example: User, Item, Category ]
         * $Form = The Table To Select From [ Example: users, items, categories ]
         * $Value = The Value Of Select [ Example: mahmoud, box, electronics ]
         */

            function checkItem($select, $form, $value){

                global $pdo;

                $statement = $pdo->prepare("SELECT $select FROM $form WHERE $select = ? ");

                $statement->execute([$value]);

                $count = $statement->rowCount();

                return $count;

            }


            /*
             * Count number of items function v1.0
             * function to count number of items rows
             * $item = the item to count
             * $table = the table to choose from
             */

             function countItems($item , $table)
             {
                 global $pdo;

                 $stmt2 = $pdo->prepare("SELECT COUNT($item) FROM $table");
                 $stmt2->execute();

                 return $stmt2->fetchColumn();
             }


            /*
             * Get latest Records Function v1.0
             * function to get latest items from database [ users, items , comments ]
             * $select = field to select
             * $table = the table to choose from
             * $order = the desc ordering
             * $limit = number of records to get
             */

            function getLatest($select, $table, $order, $limit = 5)
            {
                global $pdo;

                $getStmt = $pdo->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

                $getStmt->execute();

                $rows = $getStmt->fetchAll();

                return $rows;
            }



















