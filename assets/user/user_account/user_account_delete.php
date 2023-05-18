<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require "../../functions/functions.php";

$id = $_GET["id"];

$query = query("SELECT id_user FROM sewa WHERE id_user = $id");

if (mysqli_num_rows($query) > 0) {
  header("location: user_account_setting.php?message=error_delete_account");
  exit;
}

delete_account($id);

header("location: ../../auth/login.php?message=delete_account_success");
