<!-- Dang nhap tai khoan -->
<?php
if(!defined('_CODE')){
    die('Access denied ...');
}



if(isPost()){
    $filterAll = filter();

    $errors = []; // Mang chua cac loi

    // Validate fullname: bat buoc phai nhap, min 5 ky tu
    if(empty($filterAll['fullname'])){
        $errors['fullname']['required'] = 'Ho ten bat buoc phai nhap.';
    }
    else{
        if(strlen($filterAll['fullname']) < 5){
            $errors['fullname']['min'] = 'Ho ten phai co it nhat 5 ki tu.';
        }
    }

    // Email Validate: bat buoc phai nhap, dung dinh dang email, kiem tra email da ton tai hay chua
    if(empty($filterAll['email'])){
        $errors['email']['required'] = 'Email bat buoc phai nhap.';
    }
    else{
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        if(getRows($sql) > 0){
            $errors['email']['unique'] = 'Email da ton tai.';
        }
    }

    // Validate so dien thoai: bat buoc phai nhap, so co dung dinh dang khong
    if(empty($filterAll['phone'])){
        $errors['phone']['required'] = 'So dien thoai bat buoc phai nhap.';
    }
    else{
        if(!isPhone($filterAll['phone'])){
            $errors['phone']['isPhone'] = 'So dien thoai khong hop le.';
        }
    }

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

        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'],PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users',$dataInsert);

        if($insertStatus){
            setFlashData('smg',"Them nguoi dung moi thanh cong!!");
            setFlashData('smg_type',"success");
            redirect('?module=users&action=list');

        }else{
            setFlashData('smg',"He thong dang loi vui long thu lai sau!!");
            setFlashData('smg_type',"danger");
            redirect('?module=users&action=list');
        }

    }
    else{
        // $smg = "Vui long kiem tra lai du lieu!!";
        setFlashData('smg',"Vui long kiem tra lai du lieu!!");
        setFlashData('smg_type',"danger");
        setFlashData('errors',$errors);
        setFlashData('old',$filterAll);
        redirect('?module=users&action=add');
    }
}
layouts('header-login');

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');


?>

<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Them nguoi dung</h2>
        <?php
        if(!empty($smg)){
            getSmg($smg, $smg_type);
        }
           
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Ho ten</label>
                        <input name="fullname" type="fullname" class="form-control" placeholder="Ho ten" value="<?php 
                        echo old('fullname', $old);
                        ?>">
                        <?php
                            echo form_error('fullname','<span class="error">','</span>',$errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Dia chi email" value="<?php 
                        echo old('email', $old);
                        ?>">
                        <?php
                            echo form_error('email','<span class="error">','</span>',$errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">So dien thoai</label>
                        <input name="phone" type="number" class="form-control" placeholder="So dien thoai" value="<?php 
                        echo old('phone', $old);
                        ?>">
                        <?php
                            echo form_error('phone','<span class="error">','</span>',$errors);
                        ?>
                    </div>
                </div>
                <div class="col">
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
                    <div class="form-group">
                        <label for="">Trang thai</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (old('status',$old) == 0) ? 'selected' : false; ?> >Chua kich hoat</option>
                            <option value="1" <?php echo (old('status',$old) == 1) ? 'selected' : false; ?>>Da kich hoat</option>
                        </select>
                    </div>
                </div>
            </div>
             
            
            <button type="submit" class="btn-user btn btn-primary btn-block mg-form mg-btn">Them nguoi dung</button>
            <a href="?module=users&action=list" class="btn-user btn btn-success btn-block mg-form mg-btn">Quay lai</a>
            <hr>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>