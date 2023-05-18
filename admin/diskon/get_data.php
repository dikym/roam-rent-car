<?php
require "../../functions/functions.php";

$discount_id = $_POST['discount_id'];
$result = query("SELECT * FROM diskon WHERE id_diskon = $discount_id");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
