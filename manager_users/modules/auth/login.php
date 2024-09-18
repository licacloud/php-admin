<?php
if(!defined('_CODE')){
    die('Access denied ...');
}


$data = [
    'pageTitle' => 'Dang nhap tai khoan'
];

layouts('header-login', $data);

// if(isLogin()){
//     redirect('?module=home&action=dashboard');
// }

if(isPost()){
    $filterAll = filter();
    if(!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))){
        // kiem tra dang nhap
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // Truy van lay thong tin user theo email
        $userQuery = oneRaw("SELECT password, id FROM users WHERE email = '$email'");

        if(!empty($userQuery)){
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if(password_verify($password, $passwordHash)){

                // Tao token login
                $tokenLogin = sha1(uniqid().time());

                // Insert vao bang tokenlogin
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                $insertStatus = insert('tokenloging',$dataInsert);
                if($insertStatus){
                    // Insert thanh cong
                    // Luu cai tokenlogin vao session
                    setSession('tokenloging',$tokenLogin);
                    redirect('?module=home&action=dashboard');
                }else{
                    setFlashData('msg','khong the dang nhap vui long thu lai sau.');
                    setFlashData('msg_type','danger');
                }
            }else{
                setFlashData('msg','Mat khau khong chinh xac.');
                setFlashData('msg_type','danger');
            }
        }
        else{
            setFlashData('msg','Email khong ton tai.');
            setFlashData('msg_type','danger');
        }

    }else{
        setFlashData('msg','Vui long nhap email va mat khau.');
        setFlashData('msg_type','danger');
        // redirect('?module=auth&action=login');
    }
    redirect('?module=auth&action=login');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Dang nhap quan ly Users</h2>
        <?php
        if(!empty($msg)){
            getSmg($msg, $msg_type);
        }
           
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Dia chi email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mat khau">
            </div>
            <button type="submit" class="btn btn-primary btn-block mg-form mg-btn">Dang nhap</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quen mat khau</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Dang ki tai khoan</a></p>
        </form>
    </div>
</div>


<?php
    layouts('footer-login');
?>