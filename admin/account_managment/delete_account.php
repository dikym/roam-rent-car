<?php
session_start();

require "../../functions/functions.php";

$id = $_GET["id"];

$query = query("SELECT id_user FROM sewa WHERE id_user = $id");

if (mysqli_num_rows($query) > 0) {
  header("location: account_managment.php?message=error_delete_account");
  exit;
}

delete_account($id);

if ($id === $_SESSION["id"]) {
  header("location: ../../auth/login.php?message=delete_account_success");
} else {
  header("location: account_managment.php?message=delete_account_success");
}
