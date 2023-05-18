<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require "../../functions/functions.php";

$discount_id = $_GET["discount_id"];

delete_discount_data($discount_id);

header("location: diskon.php?message=delete_discount_data_success");
