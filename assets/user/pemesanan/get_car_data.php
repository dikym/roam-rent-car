<?php
require "../../functions/functions.php";

$car_plate = $_POST['car_plate'];
$result = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
