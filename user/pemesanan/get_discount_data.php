<?php
require "../../functions/functions.php";

$total_discount = $_POST['total_discount'];
$result = query("SELECT * FROM diskon WHERE total_diskon = '$total_discount'");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
