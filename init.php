<?php

        ini_set('display_errors','on');
        error_reporting(E_ALL);

        include 'admin/connect.php';

        $sessionUser = '';
        if (isset($_SESSION['user'])){
            $sessionUser = $_SESSION['user'];
        }

        $tpl = 'includes/templates/'; //template directory
        $lang = 'includes/languages/'; //Language directory
        $function = 'includes/functions/'; // Functions directory
        $css = 'layout/css'; //CSS directory
        $js = 'layout/js'; //JS directory



        // Include Important Files
        include $lang . 'english.php';
        include $function . 'functions.php';
        include $tpl . 'header.php';
        include $tpl . 'navbar.php';






