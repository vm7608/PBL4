<?php
session_start();
?>

<?php

// initializing variables
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'onlineshop', 3306);
// Login user
if (isset($_POST['login_admin'])) {
  $admin_username = mysqli_real_escape_string($db, $_POST['admin_username']);

  $admin_username = str_replace("or", htmlentities("or"), $admin_username);
  $admin_username = str_replace("union", htmlentities("union"), $admin_username);
  $admin_username = str_replace("OR", htmlentities("or"), $admin_username);
  $admin_username = str_replace("UNION", htmlentities("union"), $admin_username);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($admin_username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  // Code cũ, truyền thẳng vào query
  // if (count($errors) == 0) {
  //   $password = md5($password);
  //   $query = "SELECT * FROM admin_info WHERE admin_email='$admin_username' AND admin_password='$password'";
  //   $results = mysqli_query($db, $query);
  //   if (mysqli_num_rows($results) == 1) {
  //     $_SESSION['admin_email'] = $email;
  //     $_SESSION['admin_name'] = $admin_username;
  //     $_SESSION['success'] = "You are now logged in";
  //     header('location: ./admin/');
  //   } else {
  //     array_push($errors, "Wrong username/password combination");
  //   }
  // }
  // Code mới, dùng PDO, chống được sqli
  if (count($errors) == 0) {
    $password = md5($password);
    $db = new PDO('mysql:host=localhost;port=3306;dbname=onlineshop', 'root', '');
    // Set up the prepared statement
    $stmt = $db->prepare('SELECT * FROM admin_info WHERE admin_email = :username AND admin_password = :password');
    // Bind the parameters to the prepared statement
    $stmt->bindParam(':username', $admin_username);
    $stmt->bindParam(':password', $password);
    // Execute the prepared statement
    $stmt->execute();
    // Fetch the results
    $results = $stmt->fetchAll();
    $numRows = $stmt->rowCount();
    if ($numRows > 0) {
      $row = $stmt->fetch();
      $_SESSION['admin_email'] = $email;
      $_SESSION['admin_name'] = $admin_username;
      $_SESSION['success'] = "You are now logged in";
      header('location: ./admin/');
    } else {
      array_push($errors, "Wrong username or password!");
    }
  }
}


?>

