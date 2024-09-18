<!-- Dang xuat -->
<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

if(isLogin()){
    $token = getSession('tokenloging');
    delete('tokenloging', "token='$token'");
    removeSession('tokenloging');
    redirect('?module=auth&action=login');
}