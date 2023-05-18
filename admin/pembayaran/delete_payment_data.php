<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require "../../functions/functions.php";

$payment_id = $_GET["payment_id"];

delete_payment_data($payment_id);

header("location: pembayaran.php?message=delete_payment_data_success");
