<?php


    session_start(); // Start the session

    session_unset();  // Unset the data

    session_destroy(); // Destroy the session

    header('location: index.php');


    exit();
