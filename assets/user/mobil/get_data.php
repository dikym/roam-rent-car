<?php
require "../../functions/functions.php";

$car_id = $_POST['car_id'];
$result = query("SELECT * FROM mobil WHERE id_mobil = '$car_id'");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
