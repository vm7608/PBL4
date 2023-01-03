<?php
    session_start();
    include("./db.php");
    #Lấy tên file trong đường dẫn~
    $filename = basename($_GET['file']);
    # cấu trúc của filename = "order" + orderid + ".pdf"
    # Select từ DB xem tên file hiện tại chứa order id có nằm trong các order id của người dùng~ 
    $sql = "SELECT order_id  FROM `orders_info` WHERE user_id = '$_SESSION[uid]' and order_id ='".substr($filename, 5, -4)."'";
    if ((mysqli_num_rows(mysqli_query($con,$sql)) <1) or (substr($filename,0,5)!="order") or (substr($filename,-4)!=".pdf"))
    {
        #nếu không thuộc order id của người dùng, in ra lỗi và quay về trang cá nhân của người dùng
        echo "<script>alert('Thao tác không hợp lệ. Bạn không được phép truy cập vào địa chỉ này.')</script>";
        include("profile.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <?php

    echo "<iframe src=\"./bill/$filename\" width=\"100%\" style=\"height:800px\"></iframe>";

    ?>
</body>
</html>