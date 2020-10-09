<?php


include 'connect.php';

$tpl = 'includes/templates/'; //template directory
$lang = 'includes/languages/'; //Language directory
$function = 'includes/functions/'; // Functions directory
$css = 'layout/css'; //CSS directory
$js = 'layout/js'; //JS directory



// Include Important Files
include $lang . 'english.php';
include $function . 'functions.php';
include $tpl . 'header.inc.php';

// Include navbar on all pages expect the one with $noNavbar Variable

    if (!isset($noNavbar)) { include $tpl . 'navbar.inc.php'; }




