<?php
require "../../functions/functions.php";

$id = $_POST['id'];
$result = query("SELECT * FROM account WHERE id = $id");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
