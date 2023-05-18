<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require "../../functions/functions.php";

$lease_id = $_GET["lease_id"];

delete_lease_data($lease_id);

header("location: sewa.php?message=delete_lease_data_success");
