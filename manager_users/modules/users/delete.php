<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

// Kiem tra id trong database -> ton tai -> tien hanh xoa
// Xoa du lieu tokenlogin -> Xoa du lieu user

$filterAll = filter();
if(!empty($filterAll['id'])){
    $userId = $filterAll['id'];
    $userDetail = getRows("SELECT * FROM users WHERE id =$userId");
    if($userDetail > 0){
        // Thuc hien xoa
        $deleteToken = delete('tokenloging',"user_Id = $userId");
        if($deleteToken){
            // Xoa user
            $deleteUser = delete('users',"id=$userId");
            if($deleteUser){
                setFlashData('smg','Xoa thanh cong.');
                setFlashData('smg_type','success');
            }else{
                setFlashData('smg','Loi he thong.');
                setFlashData('smg_type','danger');
            }
        }

    }else{
        setFlashData('smg','Nguoi dung khong ton tai trong he thong.');
        setFlashData('smg_type','danger');
    }
}
else{
    setFlashData('smg','Lien ket khong ton tai');
    setFlashData('smg_type','danger');
}

redirect('?module=users&action=list');