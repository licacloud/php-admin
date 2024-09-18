<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

// Ham gan session
function setSession($key, $value){
    return $_SESSION[$key] = $value;
}

// Ham doc session
function getSession($key=''){
    if(empty($key)){
        return $_SESSION;
    }
    else{
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
}

// Ham xoa session
function removeSession($key=''){
    if(empty($key)){
        session_destroy();
        return true;
    }
    else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
}

// Ham gan flash data
function setFlashData($key, $value){
    $key = 'flash_' .$key;
    return setSession($key,$value);
}

// Ham doc flash data
function getFlashData($key){
    $key = 'flash_' .$key;
    $data = getSession($key);
    removeSession($key);
    return $data;
}