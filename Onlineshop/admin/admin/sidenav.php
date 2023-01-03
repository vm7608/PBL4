<?php

if (!isset($_SESSION['admin_name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: .././login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['admin_name']);
    header("location: .././login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/admin-panel.png">
    <link rel="icon" type="image/png" href="./assets/img/admin-panel.png">
    <!-- <link rel="icon" href="https://media.geeksforgeeks.org/wp-content/cdn-uploads/gfg_200X200.png" type="image/x-icon"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Admin
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/demo/demo.css" rel="stylesheet" />
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
</head>



<body class="dark-edition">
    <div class="wrapper ">
        <div class="sidebar" data-color="green" data-background-color="black" data-image=" ../assets/img/sidebar-3.jpg">
            <!--
        Tip 1: Change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
        Tip 2: Add an image using data-image tag
    -->
            <div class="logo"><a href="index.php" class="simple-text logo-normal">
                    <img src="./assets/img/ITF.jpg" style="width: 150px;">
                </a></div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="add_products.php">
                            <i class="material-icons">add</i>
                            <p>Add Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products_list.php?page=1">
                            <i class="material-icons">list</i>
                            <p>Product List</p>
                        </a>

                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="manageuser.php?page=1">
                            <i class="material-icons">person</i>
                            <p>Manage users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageCate.php?page=1">
                            <i class="material-icons">bookmark_border</i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageBrand.php?page=1">
                            <i class="material-icons">branding_watermark</i>
                            <p>Brands</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salesofday.php?page=1">
                            <i class="material-icons">shopping_cart</i>
                            <p>Order</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>