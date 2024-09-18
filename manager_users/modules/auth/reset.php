<!-- reset tai khoan -->
<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

layouts('header-login');



$token = filter()['token'];
if(!empty($token)){
    // Truy van database kiem tra token
    $tokenQuery = oneRaw("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
    if(!empty($tokenQuery)){
        $userId = $tokenQuery['id'];
        if(isPost()){
            $filterAll = filter();
            $errors = []; // Mang chua cac loi

            // Validate password: bat buoc phai nhap, >= 8 ky tu
            if(empty($filterAll['password'])){
                $errors['password']['required'] = 'Mat khau bat buoc phai nhap.';
            }
            else{
                if(strlen($filterAll['password']) < 8){
                    $errors['password']['min'] = 'Mat khau phai lon hon hoac bang 8 ky tu.';
                }
            }

            // Validate password_confirm: bat buoc phai nhap, giong password
            if(empty($filterAll['password_confirm'])){
                $errors['password_confirm']['required'] = 'Ban phai nhap lai mat khau.';
            }
            else{
                if(($filterAll['password']) != $filterAll['password_confirm']){
                    $errors['password_confirm']['match'] = 'Mat khau nhap lai khong dung.';
                }
            }

            if(empty($errors)){
                // Xu lu viec update mat khau
                $passwordHash = password_hash($filterAll['password'],PASSWORD_DEFAULT);
                $dataUpdate =[
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];

                $updateStatus = update('users', $dataUpdate, "id = '$userId'");
                if($updateStatus){
                    setFlashData('msg',"Thay doi mat khau thanh cong!!");
                    setFlashData('msg_type',"success");
                    redirect('?module=auth&action=login');
                }else{
                    setFlashData('msg',"Loi he thong vui long thu lai sau!!");
                    setFlashData('msg_type',"danger");
                }
            }
            else{
                setFlashData('msg',"Vui long kiem tra lai du lieu!!");
                setFlashData('msg_type',"danger");
                setFlashData('errors',$errors);
                redirect('?module=auth&action=reset&token='.$token);
            }
        }


$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');

        
?>
        <!-- Form dat lai mat khau -->
        <div class="row">
        <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">DAT LAI MAT KHAU</h2>
        <?php
        if(!empty($msg)){

            getSmg($msg, $msg_type);
        }
           
        ?>
        <form action="" method="post">
            
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mat khau">
                <?php
                    echo form_error('password','<span class="error">','</span>',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhap lai Password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Nhap lai Mat khau">
                <?php
                    echo form_error('password_confirm','<span class="error">','</span>',$errors);
                ?>
            </div>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <button type="submit" class="btn btn-primary btn-block mg-form mg-btn">GUI</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Dang nhap tai khoan</a></p>
        </form>
    </div>
</div>

        <?php

    }else{
        getSmg('Lien ket khong ton tai hoac da het han.','danger');
    }
}
else{
    getSmg('Lien ket khong ton tai hoac da het han.','danger');
}

?>

<?php
    layouts('footer-login');

?>