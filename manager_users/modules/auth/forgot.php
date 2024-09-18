<!-- Dang nhap tai khoan -->
<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

$data = [
    'pageTitle' => 'Quen mat khau'
];

layouts('header-login', $data);


if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email'])){
        $email = $filterAll['email'];

        $queryUser = oneRaw("SELECT id FROM users WHERE email = '$email'");
        if(!empty($queryUser)){
            $userId = $queryUser['id'];

            // Tao forgot token
            $forgotToken = sha1(uniqid().time());

            $dataUpdate = [
                'forgotToken' => $forgotToken 
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if($updateStatus){
                //  Tao cai link reset, khoi phuc mat khau
                $linkReset = _WEB_HOST.'?module=auth&action=reset&token='.$forgotToken;

                // Gui mail cho nguoi dung
                $subject = 'Yeu cau khoi phuc mat khau.';
                $content = 'Chao ban.</br>';
                $content .= 'Chung toi nhan duoc yeu cau khoi phuc mat khau tu ban.
                Vui long click vao link sau de doi lai mat khau: </br>';
                $content .= $linkReset.'</br>';
                $content .= 'Tran trong cam on!';

                $sendEmail = sendMail($email, $subject, $content);

                if($sendEmail){
                    setFlashData('msg','Vui long kiem tra email de xem huong dan dat lai mat khau!');
                    setFlashData('msg_type','success');
                }else{
                    setFlashData('msg','Loi he thong vui long thu lai sau!(email)');
                    setFlashData('msg_type','danger');
                }

            }else{
                setFlashData('msg','Loi he thong vui long thu lai sau!');
                setFlashData('msg_type','danger');
            }

        }else{
            setFlashData('msg','Dia chi email khong ton tai trong he thong!');
            setFlashData('msg_type','danger');
        }

    }else{
        setFlashData('msg','Vui long nhap dia chi email');
        setFlashData('msg_type','danger');
    }
    // redirect('?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Quen mat khau</h2>
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
            <button type="submit" class="btn btn-primary btn-block mg-form mg-btn">Gui</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Dang nhap</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Dang ki tai khoan</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer-login');
?>