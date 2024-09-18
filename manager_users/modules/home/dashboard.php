<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

$data = [
    'pageTitle' => 'Trang Dashboard'
];

layouts('header', $data);

if(!isLogin()){
    redirect('?module=auth&action=login');
}

?>
<?php

layouts('footer');

?>