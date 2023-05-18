<?php
require "../../functions/functions.php";

$lease_id = $_POST["lease_id"];
$payment_id = $_POST['payment_id'];
$result = query("SELECT * FROM pembayaran JOIN sewa ON sewa.id_sewa = '$lease_id' WHERE id_pembayaran = $payment_id");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
