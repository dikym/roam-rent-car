<?php
require "../../functions/functions.php";

$lease_id = $_POST['lease_id'];
$result = query("SELECT * FROM sewa JOIN mobil ON sewa.plat_mobil = mobil.plat_mobil WHERE id_sewa = $lease_id");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
