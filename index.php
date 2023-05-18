<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['level'])) {
  if ($_SESSION['level'] == 'user') {
    header("location: /user/user_home.php");
    exit;
  } else if ($_SESSION['level'] == 'admin') {
    header("location: /admin/admin_home.php");
    exit;
  }
} else {
  header("location: /auth/login.php");
  exit;
}
?>
