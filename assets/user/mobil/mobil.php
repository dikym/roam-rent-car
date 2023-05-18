<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

if ($_SESSION['level'] == "") {
  header("location: ../../auth/login.php?message=unrecognized");
  exit;
}

require "../../functions/functions.php";

$cars = query("SELECT * FROM mobil");

if (isset($_POST["search"])) {
  $cars = search_car($_POST["keyword"]);
} else if (isset($_POST["submit_filters"])) {
  $cars = car_filters($_POST);
}

if (isset($_POST["submit_order"])) {
  add_lease_data($_POST);
  $user_id = $_POST["user_id"];
  $lease_query = query("SELECT * FROM sewa WHERE id_user = '$user_id' ORDER BY tgl_pemesanan DESC");
  $lease_data = mysqli_fetch_assoc($lease_query);
  $_POST["lease_id"] = $lease_data["id_sewa"];
  $_POST["total"] = $lease_data["total_pembayaran"];
  add_payment($_POST);

  header("location: ?message=order_car_success");
}

$account = get_account();
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Car List - Roam</title>
  <link rel="shortcut icon" href="../../assets/img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

  <style>
    @media only screen and (max-width: 768px) {

      p,
      table,
      .footer,
      .nav-link,
      .dropdown-item,
      .dropdown-toggle,
      .btn,
      .form-label {
        font-size: 0.9rem;
      }

      .search-info {
        font-size: 0.8rem;
      }
    }

    .container {
      padding-right: calc(3rem * .5);
      padding-left: calc(3rem * .5);
    }

    .navbar-toggler:focus {
      box-shadow: none;
      outline: none;
      border-color: transparent;
      padding: 0;
    }
  </style>
</head>

<body class="d-flex flex-column h-100">
  <header>
    <?php
    if (isset($_GET["message"])) {
      if ($_GET['message'] == "order_car_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
              Congratulations, your car order was successful!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
            </div>";
      }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color: #645CBB">
      <div class="container">
        <a href="../user_home.php"><img src="../../assets/img/white_logo.png" alt="Roam Logo" width="70px" class="navbar-brand"></a>
        <a href="#navbarToggler" id="navbar-toggler" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler text-white p-0 border-0">
          <i class="bi bi-list display-6" id="navbar-toggler-icon"></i>
        </a>
        <div class="collapse navbar-collapse" id="navbarToggler">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active opacity-75" aria-current="page" href="../user_home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active fw-semibold" href="mobil.php">Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../pemesanan/pemesanan.php">Pemesanan</a>
            </li>
          </ul>
          <div class="dropdown">
            <a class="dropdown-toggle link-light text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person"></i> <?= $account["name"]; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg-end shadow">
              <li><a class="dropdown-item opacity-75" href="../user_account/user_account_profile.php">Profiles</a></li>
              <li><a class="dropdown-item opacity-75" href="../user_account/user_account_setting.php">Settings</a></li>
              <li><a class="dropdown-item opacity-75" href="../../auth/logout.php">Logout</a></li>
              <?php
              if ($_SESSION['level'] == "admin") {
                echo '<li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item opacity-75" href="../../admin/admin_home.php">Switch to Admin</a></li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="container">
    <h1 class="m-5 display-5 text-center">Daftar Mobil Kami</h1>

    <div class="d-flex justify-content-center">
      <div class="col-xxl-12 col-md-12 table-responsive p-1">
        <div class="row mb-4 m-0">
          <div class="col-sm-8 p-0">
            <button class="btn btn-dark mb-2" data-bs-target="#filtersModal" data-bs-toggle="modal"><i class="bi bi-filter"></i> Filters</button>
          </div>
          <div class=" col-sm-4 p-0">
            <form class="input-group" method="post">
              <input class="form-control border-dark-subtle" type="search" placeholder="Search" name="keyword">
              <button class="btn btn-dark" type="submit" name="search"><i class=" bi bi-search"></i></button>
            </form>
          </div>
        </div>
        <?php if (isset($_POST["search"]) || isset($_POST["submit_filters"])) : ?>
          <span class="search-info"><?= mysqli_num_rows($cars); ?> data found</span>
        <?php endif; ?>
        <div class="card table-responsive">
          <table class="table table-hover align-middle m-0">
            <thead class="table-light text-nowrap">
              <tr align="center">
                <th>id</th>
                <th>Plat Mobil</th>
                <th>Mobil</th>
                <th>Harga per Hari</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              <?php if (mysqli_num_rows($cars) > 0) : ?>
                <?php foreach ($cars as $row) : ?>
                  <tr align="center">
                    <td><?= $row["id_mobil"]; ?></td>
                    <td><?= $row["plat_mobil"]; ?></td>
                    <td><?= $row["nama_mobil"]; ?></td>
                    <td>Rp.<?= number_format($row["tarif_per_hari"], 0, ",", "."); ?></td>
                    <td>
                      <?php if ($row["status"] == "available") : ?>
                        <span class="badge text-bg-primary"><?= $row["status"]; ?></span>
                      <?php else : ?>
                        <span class="badge text-bg-danger"><?= $row["status"]; ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($row["status"] == "available") : ?>
                        <button type="button" class="btn btn-success btn-sm btn-order" data-bs-target="#orderModal" data-bs-toggle="modal" data-id="<?= $row["id_mobil"]; ?>">Pesan sekarang</button>
                      <?php else : ?>
                        <button type="button" class="btn btn-success btn-sm" disabled>Pesan sekarang</button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="6" align="center"><em>Data not found</em></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <section>
      <div id="filtersModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form method="post" autocomplete="off" id="formAdd" novalidate>
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Filters</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="car_plate" class="form-label">Merk Mobil</label>
                  <?php
                  $cars_query = query("SELECT nama_mobil FROM mobil");
                  $car_names = [];
                  foreach ($cars_query as $cars_query_result) {
                    $car_names[] = $cars_query_result["nama_mobil"];
                  }
                  $filter_brands = filter_car_brands($car_names);
                  ?>
                  <select class="form-select" id="car_brand" name="car_brand">
                    <option selected value="all_cars">All cars</option>
                    <?php foreach ($filter_brands as $filter_brand) : ?>
                      <option value="<?= $filter_brand; ?>"><?= $filter_brand; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label for="min" class="form-label">Range Tarif</label>
                  <div class="row">
                    <div class="col-sm-5">
                      <input type="number" class="form-control" id="min" name="min_range" placeholder="Min">
                    </div>
                    <span class="col-sm-2 text-center my-2">to</span>
                    <div class="col-sm-5">
                      <input type="number" class="form-control" id="max" name="max_range" placeholder="Max">
                    </div>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select class="form-select" id="filter_status" name="filter_status">
                    <option selected value="all_status">All status</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white" style="background-color: #645CBB;" name="submit_filters">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div id="orderModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form method="post" class="needs-validation" autocomplete="off" novalidate>
              <div class=" modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Pemesanan Mobil</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="user_id" value="<?= $account["id"]; ?>">
                <input type="hidden" name="car_plate" id="car_plate">
                <input type="hidden" name="rate_per_day" id="rate_per_day">
                <div class="form-group mb-3">
                  <label for="start_date" class="form-label">Tgl Mulai</label>
                  <input type="date" class="form-control" name="start_date" id="start_date" required>
                  <div class="invalid-feedback">
                    Please select a valid start date.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="finish_date" class="form-label">Tgl Selesai</label>
                  <input type="date" class="form-control" name="finish_date" id="finish_date" required>
                  <div class="invalid-feedback">
                    Please select a valid finish date.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="user_cash" class="form-label">Uang Anda</label>
                  <input type="number" class="form-control" name="user_cash" id="user_cash" required>
                  <div class="invalid-feedback">
                    Please input your cash.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="discount" class="form-label">Diskon</label>
                  <select class="form-select" id="discount" name="discount" required>
                    <?php $discounts = query("SELECT * FROM diskon"); ?>
                    <?php if (mysqli_num_rows($discounts) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="0">No discount</option>
                    <?php endif; ?>
                    <?php foreach ($discounts as $discount) : ?>
                      <option value="<?= $discount["total_diskon"]; ?>"><?= $discount["total_diskon"]; ?>% - <?= $discount["nama_diskon"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid discount.
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white" style="background-color: #645CBB;" name="submit_order">Order</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer mt-auto pt-4">
    <div class="text-center py-2 bg-light">
      <span class="mb-0 text-muted">Made with &#x2764;&#xFE0F; by Diky</span>
    </div>
  </footer>

  <script>
    $(document).on("click", ".btn-order", function() {
      const car_id = $(this).data("id");
      $.ajax({
        url: "get_data.php",
        type: "POST",
        data: {
          car_id: car_id,
        },
        dataType: "json",
        success: function(response) {
          $("#car_plate").val(response.plat_mobil);
          $("#rate_per_day").val(response.tarif_per_hari);
        },
      });
    });
  </script>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/validation.js"></script>
</body>

</html>