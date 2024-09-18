<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

// require_once './manager_users/config.php';

try{
    if(class_exists('PDO')){
        $dsn = 'mysql:dbname='._DB.';host='._HOST;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Tao thong bao ra ngoai le khi error
        ];
        $conn = new PDO($dsn,_USER,_PASS, $options);
        // if($conn){
        //     echo 'OK';
        // }
    }
}catch(Exception $exception){
    echo $exception -> getMessage().'<br>';
    die();
}