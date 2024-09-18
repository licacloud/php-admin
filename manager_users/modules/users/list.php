<?php
if(!defined('_CODE')){
    die('Access denied ...');
}

$data = [
    'pageTitle' => 'Danh sach nguoi dung'
];

layouts('header-login');

// if(!isLogin()){
//     redirect('?module=auth&action=login');
// }

// Truy van vao bang user
$listUsers = getRaw("SELECT * FROM users ORDER BY update_at");

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');


?>

<div class="container">
    <hr>
    <h2>Quan ly nguoi dung</h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm">Them nguoi dung<i class="fa-solid fa-plus"></i></a>
    </p>
    <?php
        if(!empty($smg)){
            getSmg($smg, $smg_type);
        }
           
        ?>
    <table class="table table-bordered">
        <thead>
            <th>STT</th>
            <th>Ho ten</th>
            <th>Email</th>
            <th>So dien thoai</th>
            <th>Trang thai</th>
            <th width = "5%">Sua</th>
            <th width = "5%">Xoa</th>
        </thead>
        <tbody>
        <?php
            if(!empty($listUsers)):
                $count = 0; // So thu tu
                foreach($listUsers as $item):
                    $count++;
        ?>
        <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $item['fullname']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php echo $item['phone']; ?></td>
            <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Da kich hoat</button>' : '<button class="btn btn-danger btn-sm">Chua kich hoat</button>' ; ?></td>
            <td><a href="<?php echo _WEB_HOST; ?> ?module=users&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="<?php echo _WEB_HOST; ?> ?module=users&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Ban co chac chan muon xoa?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php
                endforeach;
            else:
        ?>
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger text-center">Khong co nguoi dung nao</div>
                </td>
            </tr>
        
        <?php
            endif;
        ?>
        </tbody>
    </table>
</div>


<?php
layouts('footer-login');

?>