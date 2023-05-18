<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require "../functions/functions.php";

$username = $_POST['username'];
$password = $_POST['password'];


$login = query("SELECT * FROM account WHERE username = '$username' AND password = '$password'");
$total_user = mysqli_num_rows($login);

if ($total_user > 0) {
  $data = mysqli_fetch_assoc($login);

  if ($data['level'] == "admin") {
    $_SESSION['id'] = $data['id'];
    $_SESSION['level'] = "admin";

    header("location: ../admin/admin_home.php");
  } else if ($data['level'] == "user") {
    $_SESSION['id'] = $data['id'];
    $_SESSION['level'] = "user";

    header("location: ../user/user_home.php");
  } else {

    header("location: login.php?message=unvalid");
  }
} else {
  header("location: login.php?message=unvalid");
}
