<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require "../../functions/functions.php";

$car_id = $_GET["car_id"];
$car_plate = $_GET["car_plate"];
$car_data_query = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");
$car = mysqli_fetch_assoc($car_data_query);

if ($car["status"] == "unavailable") {
  header("location: mobil.php?message=error_delete_car_data");
} else {
  delete_car($car_id);

  header("location: mobil.php?message=delete_car_data_success");
}
