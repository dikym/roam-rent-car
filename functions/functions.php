<?php
// current time
$current_time = date("Y-m-d H:i:s");
$current_date = date("Y-m-d");

// connection and query
$connection = mysqli_connect("sql204.epizy.com", "epiz_33712473", "jXeUQSCKZ81L3UC", "epiz_33712473_roam");

function query($query)
{
  global $connection;
  $result = mysqli_query($connection, $query);

  return $result;
}

function filter($input)
{
  return trim(stripslashes(htmlspecialchars($input)));
}

// register and account setting
function register($method)
{
  global $current_time;
  $name = filter($method["name"]);
  $username = filter($method["username"]);
  $password = htmlspecialchars($method["password"]);

  query("INSERT INTO account SET name = '$name', username = '$username', password = '$password', level = 'user', created_date = '$current_time'");
}

function setting_edit_account($method)
{
  $id = $_SESSION["id"];
  $new_name = filter($method["new_name"]);
  $new_username = filter($method["new_username"]);
  $new_password = htmlspecialchars($method["new_password"]);

  query("UPDATE account SET name = '$new_name', username = '$new_username', password = '$new_password' WHERE id = $id");
}

function delete_account($id)
{
  query("DELETE FROM account WHERE id = $id");
}

// account
function get_account()
{
  $id = $_SESSION["id"];
  $account = query("SELECT * FROM account WHERE id = $id");
  return mysqli_fetch_assoc($account);
}

function add_account($method)
{
  global $current_time;
  $name = filter($method["name"]);
  $username = filter($method["username"]);
  $password = htmlspecialchars($method["password"]);
  $level_account = $method["level_account"];

  query("INSERT INTO account SET name = '$name', username = '$username', password = '$password', level = '$level_account', created_date = '$current_time'");
}

function search_account($keyword)
{
  if (trim(strtolower($keyword)) === "all") {
    return query("SELECT * FROM account");
  } else {
    $query = "SELECT * FROM account WHERE id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR username LIKE '%$keyword%' OR level LIKE '%$keyword%'";

    return query($query);
  }
}

function edit_account($method)
{
  $id = $method["id"];
  $new_name = filter($method["new_name"]);
  $new_username = filter($method["new_username"]);
  $new_password = htmlspecialchars($method["new_password"]);
  $new_level_account = $method["new_level_account"];

  query("UPDATE account SET name = '$new_name', username = '$new_username', password = '$new_password', level = '$new_level_account' WHERE id = $id");
}


// lease
function get_lease_data()
{
  $lease_data_query = query("SELECT * FROM sewa");
  return mysqli_fetch_assoc($lease_data_query);
}

function search_lease_data($keyword)
{
  if (trim(strtolower($keyword)) === "all") {
    return query("SELECT * FROM sewa");
  } else {
    $query = "SELECT * FROM sewa JOIN account ON sewa.id_user = account.id JOIN mobil ON sewa.plat_mobil = mobil.plat_mobil WHERE id_sewa LIKE '%$keyword%' OR tgl_pemesanan LIKE '%$keyword%' OR name LIKE '%$keyword%' OR nama_mobil LIKE '%$keyword%' OR mulai LIKE '%$keyword%' OR selesai LIKE '%$keyword%' OR lama_sewa LIKE '%$keyword%' OR total_pembayaran LIKE '%$keyword%'";

    return query($query);
  }
}

function delete_lease_data($lease_id)
{
  $lease_data_query = query("SELECT * FROM sewa WHERE id_sewa = $lease_id");
  $lease_data = mysqli_fetch_assoc($lease_data_query);
  $car_plate = $lease_data["plat_mobil"];

  query("UPDATE mobil SET status = 'available' WHERE plat_mobil = '$car_plate'");

  query("DELETE FROM sewa WHERE id_sewa = $lease_id");
}

function clean_num_input($int)
{
  $arr = str_split((string) $int);
  $result = "";
  foreach ($arr as $str) {
    if ($str === "." || $str === "-") continue;
    $result .= $str;
  }
  return (int) $result;
}

function date_to_days($date1, $date2)
{
  $timestamp1 = strtotime($date1);
  $timestamp2 = strtotime($date2);

  $diff = abs($timestamp2 - $timestamp1);

  return ceil($diff / 86400);
}

function car_rate_per_day($car_plate)
{
  $car_plate_query = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");
  $car = mysqli_fetch_assoc($car_plate_query);
  return $car["tarif_per_hari"];
}

function add_lease_data($method)
{
  global $current_time;
  $user_id = $method["user_id"];
  $car_plate = $method["car_plate"];
  $start_date = $method["start_date"];
  $finish_date = $method["finish_date"];
  $number_of_days = date_to_days($start_date, $finish_date);
  $lease_length = "$number_of_days hari";
  $car_rate_per_day = car_rate_per_day($car_plate);
  $total_payment = ($number_of_days > 0) ? $number_of_days * $car_rate_per_day : $car_rate_per_day;

  query("UPDATE mobil SET status = 'unavailable' WHERE plat_mobil = '$car_plate'");

  query("INSERT INTO sewa SET id_user = '$user_id', plat_mobil = '$car_plate', mulai = '$start_date', selesai = '$finish_date', lama_sewa = '$lease_length', total_pembayaran = '$total_payment', tgl_pemesanan = '$current_time'");
}

function edit_lease_data($method)
{
  $lease_id = $method["lease_id"];
  $user_id = $method["user_id"];
  $past_car_plate = $method["past_car_plate"];
  $car_plate = $method["car_plate"];
  $start_date = $method["start_date"];
  $finish_date = $method["finish_date"];
  $number_of_days = date_to_days($start_date, $finish_date);
  $lease_length = "$number_of_days hari";
  $car_rate_per_day = car_rate_per_day($car_plate);
  if ($number_of_days > 0) {
    $total_payment = $number_of_days * $car_rate_per_day;
  } else {
    $total_payment = $car_rate_per_day;
  }

  if ($past_car_plate !== $car_plate) {
    query("UPDATE mobil SET status = 'available' WHERE plat_mobil = '$past_car_plate'");
  }

  query("UPDATE mobil SET status = 'unavailable' WHERE plat_mobil = '$car_plate'");

  query("UPDATE sewa SET id_user = '$user_id', plat_mobil = '$car_plate', mulai = '$start_date', selesai = '$finish_date', lama_sewa = '$lease_length', total_pembayaran = '$total_payment' WHERE id_sewa = $lease_id");
}


// car 
function get_car_data()
{
  $cars_query = query("SELECT * FROM mobil");
  return mysqli_fetch_assoc($cars_query);
}

function delete_car($car_id)
{
  query("DELETE FROM mobil WHERE id_mobil = '$car_id'");
}

function add_car($method)
{
  $car_plate = filter($method["car_plate"]);
  $car = filter($method["car"]);
  $rate_per_day = clean_num_input($method["rate_per_day"]);
  $status = $method["status"];

  query("INSERT INTO mobil SET plat_mobil = '$car_plate', nama_mobil = '$car', tarif_per_hari = '$rate_per_day', status = '$status'");
}

function search_car($keyword)
{
  if (trim(strtolower($keyword)) === "all") {
    return query("SELECT * FROM mobil");
  } else {
    $query = "SELECT * FROM mobil WHERE id_mobil LIKE '%$keyword%' OR plat_mobil LIKE '%$keyword%' OR nama_mobil LIKE '%$keyword%' OR tarif_per_hari LIKE '%$keyword%' OR status LIKE '%$keyword%'";

    return query($query);
  }
}

function edit_car($method)
{
  $car_id = $method["car_id"];
  $car_plate = filter($method["car_plate"]);
  $car = filter($method["car"]);
  $rate_per_day = clean_num_input($method["rate_per_day"]);
  $status = $method["status"];

  query("UPDATE mobil SET plat_mobil = '$car_plate', nama_mobil = '$car', tarif_per_hari = '$rate_per_day', status = '$status' WHERE id_mobil = '$car_id'");
}


// payment 
function get_payment_data()
{
  $payments_query = query("SELECT * FROM pembayaran");
  return mysqli_fetch_assoc($payments_query);
}

function delete_payment_data($payment_id)
{
  query("DELETE FROM pembayaran WHERE id_pembayaran = '$payment_id'");
}

function search_payment($keyword)
{
  if (trim(strtolower($keyword)) === "all") {
    return query("SELECT * FROM pembayaran JOIN sewa ON pembayaran.id_sewa = sewa.id_sewa");
  } else {
    $query = "SELECT pembayaran.*, sewa.total_pembayaran 
    FROM pembayaran 
    JOIN sewa ON pembayaran.id_sewa = sewa.id_sewa 
    WHERE pembayaran.id_pembayaran LIKE '%$keyword%' 
    OR pembayaran.id_sewa LIKE '%$keyword%' 
    OR pembayaran.tgl_pembayaran LIKE '%$keyword%' 
    OR pembayaran.total LIKE '%$keyword%' 
    OR sewa.total_pembayaran LIKE '%$keyword%' 
    OR pembayaran.diskon LIKE '%$keyword%' 
    OR pembayaran.uang_user LIKE '%$keyword%' 
    OR pembayaran.status LIKE '%$keyword%'
    ";

    return query($query);
  }
}

function add_payment($method)
{
  global $current_time;
  $lease_id = $method["lease_id"];
  $total = $method["total"];
  $user_cash = $method["user_cash"];
  $discount = $method["discount"];
  $total -= $discount / 100 * $total;
  $status = ($user_cash < $total) ? "belum lunas" : "lunas";

  query("INSERT INTO pembayaran SET id_sewa = '$lease_id', total = '$total', diskon = '$discount', uang_user = '$user_cash', status = '$status', tgl_pembayaran = '$current_time'");
}

function edit_payment($method)
{
  $payment_id = $method["payment_id"];
  $lease_id = $method["lease_id"];
  $past_total = $method["past_total"];
  $total = $method["total"];
  $user_cash = $method["user_cash"];
  $discount = $method["discount"];
  $total = $past_total -= $discount / 100 * $past_total;
  $status = ($user_cash < $total) ? "belum lunas" : "lunas";

  query("UPDATE pembayaran SET id_sewa = '$lease_id', total = '$total', uang_user = '$user_cash', diskon = '$discount', status = '$status' WHERE id_pembayaran = '$payment_id'");
}


// discount
function get_discount_data()
{
  $discount_query = query("SELECT * FROM diskon");
  return mysqli_fetch_assoc($discount_query);
}

function delete_discount_data($discount_id)
{
  query("DELETE FROM diskon WHERE id_diskon = '$discount_id'");
}

function search_discount($keyword)
{
  if (trim(strtolower($keyword)) === "all") {
    return query("SELECT * FROM diskon");
  } else {
    $query = "SELECT * FROM diskon WHERE id_diskon LIKE '%$keyword%' OR nama_diskon LIKE '%$keyword%' OR total_diskon LIKE '%$keyword%' OR mulai LIKE '%$keyword%' OR selesai LIKE '%$keyword%' OR lama_diskon LIKE '%$keyword%' OR created_date LIKE '%$keyword%'";

    return query($query);
  }
}

function add_discount($method)
{
  global $current_time;
  $discount_name = $method["discount_name"];
  $total_discount = clean_num_input($method["total_discount"]);
  $start_date = $method["start_date"];
  $finish_date = $method["finish_date"];
  $number_of_days = date_to_days($start_date, $finish_date);
  $discount_length = "$number_of_days hari";

  query("INSERT INTO diskon SET nama_diskon = '$discount_name', total_diskon = '$total_discount', mulai = '$start_date', selesai = '$finish_date', lama_diskon = '$discount_length', created_date = '$current_time'");
}

function edit_discount($method)
{
  $discount_id = $method["discount_id"];
  $discount_name = $method["discount_name"];
  $total_discount = clean_num_input($method["total_discount"]);
  $start_date = $method["start_date"];
  $finish_date = $method["finish_date"];
  $number_of_days = date_to_days($start_date, $finish_date);
  $discount_length = "$number_of_days hari";

  query("UPDATE diskon SET nama_diskon = '$discount_name', total_diskon = '$total_discount', mulai = '$start_date', selesai = '$finish_date', lama_diskon = '$discount_length' WHERE id_diskon = '$discount_id'");
}

function filter_car_brands(array $car_names): array
{
  $car_brands = array(
    "Acura",
    "Alfa Romeo",
    "Aston Martin",
    "Audi",
    "Bentley",
    "BMW",
    "Bugatti",
    "Buick",
    "Cadillac",
    "Chevrolet",
    "Chrysler",
    "Citroen",
    "Dodge",
    "Ferrari",
    "Fiat",
    "Ford",
    "GMC",
    "Honda",
    "Hyundai",
    "Infiniti",
    "Jaguar",
    "Jeep",
    "Kia",
    "Lamborghini",
    "Land Rover",
    "Lexus",
    "Lincoln",
    "Lotus",
    "Maserati",
    "Mazda",
    "McLaren",
    "Mercedes-Benz",
    "Mini",
    "Mitsubishi",
    "Nissan",
    "Pagani",
    "Peugeot",
    "Porsche",
    "Ram",
    "Renault",
    "Rolls-Royce",
    "Saab",
    "Subaru",
    "Suzuki",
    "Tesla",
    "Toyota",
    "Volkswagen",
    "Volvo"
  );
  $hasil = array();
  foreach ($car_names as $car) {
    foreach ($car_brands as $brand) {
      if (stripos(strtolower($car), strtolower($brand)) !== false) {
        $hasil[$brand] = true;
        break;
      }
    }
  }
  return array_keys($hasil);
}

function car_filters($method)
{
  $car_brand = $method["car_brand"];
  $min_range = $method["min_range"];
  $max_range = $method["max_range"];
  $status = $method["filter_status"];

  if ($car_brand == "all_cars" && $min_range == "" && $max_range == "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE status = '$status'");
  }

  if ($car_brand == "all_cars" && $min_range !== "" && $max_range == "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari >= '$min_range' AND status = '$status'");
  }

  if ($car_brand == "all_cars" && $min_range == "" && $max_range !== "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari <= '$max_range' AND status = '$status'");
  }

  if ($car_brand == "all_cars" && $min_range !== "" && $max_range !== "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari BETWEEN '$min_range' AND '$max_range' AND status = '$status'");
  }

  if ($car_brand !== "all_cars" && $min_range == "" && $max_range == "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND status = '$status'");
  }

  if ($car_brand !== "all_cars" && $min_range !== "" && $max_range == "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari >= '$min_range' AND status = '$status'");
  }

  if ($car_brand !== "all_cars" && $min_range == "" && $max_range !== "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari <= '$max_range' AND status = '$status'");
  }

  if ($car_brand !== "all_cars" && $min_range !== "" && $max_range !== "" && $status !== "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari BETWEEN '$min_range' AND '$max_range' AND status = '$status'");
  }

  if ($car_brand == "all_cars" && $min_range == "" && $max_range == "" && $status == "all_status") {
    return query("SELECT * FROM mobil");
  }

  if ($car_brand == "all_cars" && $min_range !== "" && $max_range == "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari >= '$min_range'");
  }

  if ($car_brand == "all_cars" && $min_range == "" && $max_range !== "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari <= '$max_range'");
  }

  if ($car_brand == "all_cars" && $min_range !== "" && $max_range !== "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE tarif_per_hari BETWEEN '$min_range' AND '$max_range'");
  }

  if ($car_brand !== "all_cars" && $min_range == "" && $max_range == "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%'");
  }

  if ($car_brand !== "all_cars" && $min_range !== "" && $max_range == "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari >= '$min_range'");
  }

  if ($car_brand !== "all_cars" && $min_range == "" && $max_range !== "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari <= '$max_range'");
  }

  if ($car_brand !== "all_cars" && $min_range !== "" && $max_range !== "" && $status == "all_status") {
    return query("SELECT * FROM mobil WHERE nama_mobil LIKE '%$car_brand%' AND tarif_per_hari BETWEEN '$min_range' AND '$max_range'");
  }
}
