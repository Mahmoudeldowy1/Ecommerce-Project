<?php



$dsn = 'mysql:host=localhost;dbname=shop';
$user='root';
$password = '';
$option =[
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
];

try {
    $pdo = new PDO($dsn, $user, $password, $option);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){

    echo  'Failed to Connect' . $e->getMessage();

}