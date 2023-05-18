<?php
require "../../functions/functions.php";

add_car($_POST);

header("location: mobil.php?message=car_data_add_success");
