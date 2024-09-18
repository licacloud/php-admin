
<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layouts($layoutName='header', $data = []){
    if(file_exists(_WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php')){
        require_once _WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php';
    };
}

// Ham gui mail
function sendMail($to, $subject, $content){
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'vanquybk1992@gmail.com';                     //SMTP username
        $mail->Password   = 'ynym zjmc nhro tcwp';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('thanh@gmail.com', 'Nguyen Lica');
        $mail->addAddress($to);     //Add a recipient
    
        //Content
        $mail-> CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
    
        // PHPMailer SSL certificate vertify failed
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
       
        $sendMail = $mail->send();
        if($sendMail){
            return $sendMail;
        }
    } catch (Exception $e) {
        echo "Gui mail that bai. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiem tra phuong thuc GET
function isGet(){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        return true;
    }
    return false;
}    

// Kiem tra phuong thuc POST
function isPost(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return true;
    }
    return false;
}

// Ham Filter loc du lieu
function filter(){
    $filterArr = [];
    if(isGet()){
        // Xu ly data truoc khi hien thi ra
        // return $_GET;
        if(!empty($_GET)){
            foreach($_GET as $key => $value)
            {
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                }
                else{
                    $filterArr[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if(isPost()){
        // Xu ly data truoc khi hien thi ra
        // return $_GET;
        if(!empty($_POST)){
            foreach($_POST as $key => $value)
            {
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                }
                else{
                    $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                
            }
        }
    }
    return $filterArr;
}

// kiem tra email
function isEmail($email){
    $checkEmail = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// Ham kiem tra so nguyen INT
function isNumberInt($number){
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}

// Ham kiem tra so thuc FLOAT
function isNumberFloat($number){
    $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

// Ham kiem tra so dien thoai
function isPhone($phone){
    // 0954562158
    $checkZero = false;

    // Dieu kien 1: ky tu dau tien la so 0
    if($phone[0] == '0'){
        $checkZero = true;
        $phone = substr($phone,1);
    }

    // Dieu kien 2: Dang sau co 9 so
    $checkNumber = false;
    if(isNumberInt($phone) && (strlen($phone) == 9)){
        $checkNumber = true;
    }

    if($checkZero && $checkNumber){
        return true;
    }

    return false;
}

// Thong bao loi
function getSmg($smg, $type = 'success'){
    echo '<div class= "alert alert-'.$type.'">';
    echo $smg;
    echo '</div>';
}

// Ham chuyen huong 
function redirect($path='index.php'){
    header("Location: $path");
    exit;
}

// Ham thong bao loi
function form_error($fileName, $beforeHtml='',$afterHtml='',$errors){
    return (!empty($errors[$fileName])) ? '<span class="error">'.reset($errors[$fileName]).'</span>' : null ;
}

// Ham hien thi du lieu cu
function old($fileName, $oldData, $default = null){
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default ;
}

// Ham kiem tra trang thai dang nhap
function isLogin(){
    $checkLogin = false;
    if(getSession('tokenloging')){
        $tokenLogin = getSession('tokenloging');
        // echo $tokenLogin;

        // Kiem tra tokengiong trong database
        $queryToken = oneRaw("SELECT user_Id FROM tokenloging WHERE token = '$tokenLogin'");

        if(!empty($queryToken)){
            $checkLogin = true;
        }else{
            removeSession('tokenloging');
        }
    }

    return $checkLogin;
}