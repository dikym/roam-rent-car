<?php
require "../../functions/functions.php";

$lease_id = $_POST['lease_id'];
$result = query("SELECT total_pembayaran, lama_sewa FROM sewa WHERE id_sewa = $lease_id");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
