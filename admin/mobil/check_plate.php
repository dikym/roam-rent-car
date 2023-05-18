<?php
require "../../functions/functions.php";
$car_plate = $_POST['car_plate'];

$query = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");

if (mysqli_num_rows($query) > 0) {
  $response['isDuplicate'] = true;
} else {
  $response['isDuplicate'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
