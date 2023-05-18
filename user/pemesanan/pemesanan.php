<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

if ($_SESSION['level'] == "") {
  header("location: ../../auth/login.php?message=unrecognized");
  exit;
}

require "../../functions/functions.php";

if (isset($_POST["submit_checkout"])) {
  $user_id = $_POST["user_id"];
  add_lease_data($_POST);
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
  <title>Rent a Car - Roam</title>
  <link rel="shortcut icon" href="../../assets/img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

  <style>
    @media only screen and (max-width: 768px) {

      p,
      h6,
      .footer,
      .nav-link,
      .dropdown-item,
      .dropdown-toggle,
      .form-label {
        font-size: 0.9rem;
      }

      small {
        font-size: 0.8rem;
      }

      #submit_checkout {
        font-size: 1.1rem;
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
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='pemesanan.php'\"></button>
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
              <a class="nav-link active opacity-75" href="../mobil/mobil.php">Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active fw-semibold" href="pemesanan.php">Pemesanan</a>
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
    <h1 class="m-5 display-5 text-center">Form Pemesanan</h1>
    <div class="d-flex justify-content-center">
      <div class="p-1">
        <div class="row g-5 mx-0">
          <div class="col-md-5 col-lg-4 order-md-last px-0">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-primary">Detail Pesanan</span>
            </h4>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0">Mobil</h6>
                  <small class="text-muted" id="car_name">No car selected</small>
                </div>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0">Plat Mobil</h6>
                  <small class="text-muted" id="car_plate">No car selected</small>
                </div>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0">Tarif per Hari</h6>
                  <small class="text-muted" id="rate_per_day">No car selected</small>
                </div>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0">Lama Sewa</h6>
                  <small class="text-muted" id="lease_length">Choose date</small>
                </div>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <div>
                  <h6 class="my-0">Diskon</h6>
                  <small class="text-muted" id="discount_name">No discount</small>
                </div>
                <span id="cut_of_totals">−Rp0</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <h6 class="my-0">Total (Rp)</h6>
                <strong id="total">Rp0</strong>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-lg-8 px-0">
            <h4 class="mb-3">Pemesanan</h4>
            <form method="post" class="needs-validation" novalidate>
              <div class="row g-3 mx-0">
                <input type="hidden" name="user_id" value="<?= $account["id"]; ?>">
                <input type="hidden" name="car_plate" id="input_car_plate">
                <div class="col-12">
                  <label for="car" class="form-label">Mobil</label>
                  <select class="form-select" id="car" name="car" required>
                    <?php $cars = query("SELECT * FROM mobil WHERE status = 'available'"); ?>
                    <?php if (mysqli_num_rows($cars) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="">No cars available</option>
                    <?php endif; ?>
                    <?php foreach ($cars as $car) : ?>
                      <option value="<?= $car["plat_mobil"]; ?>"><?= $car["nama_mobil"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid car.
                  </div>
                </div>

                <div class="col-12">
                  <label for="start_date" class="form-label">Tgl Mulai</label>
                  <input type="date" class="form-control" name="start_date" id="start_date" required>
                  <div class="invalid-feedback">
                    Please select a valid start date.
                  </div>
                </div>

                <div class="col-12">
                  <label for="finish_date" class="form-label">Tgl Selesai</label>
                  <input type="date" class="form-control" name="finish_date" id="finish_date" required>
                  <div class="invalid-feedback">
                    Please select a valid finish date.
                  </div>
                </div>

                <div class="col-12">
                  <label for="discount" class="form-label">Diskon</label>
                  <select class="form-select" id="discount" name="discount" required>
                    <?php $discounts = query("SELECT * FROM diskon"); ?>
                    <?php if (mysqli_num_rows($discounts) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="0">No discount</option>
                    <?php endif; ?>
                    <?php foreach ($discounts as $discount) : ?>
                      <option value="<?= $discount["total_diskon"]; ?>"><?= $discount["nama_diskon"]; ?> - <?= $discount["total_diskon"]; ?>%</option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid discount.
                  </div>
                </div>

                <div class="col-12">
                  <label for="user_cash" class="form-label">Uang Anda</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" name="user_cash" id="user_cash" placeholder="Your cash" required>
                    <div class="invalid-feedback">
                      Please input your cash.
                    </div>
                  </div>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit_checkout" id="submit_checkout">Continue to checkout</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer mt-auto pt-4">
    <div class=" text-center py-2 bg-light">
      <span class="mb-0 text-muted">Made with &#x2764;&#xFE0F; by Diky</span>
    </div>
  </footer>

  <script>
    $(document).on("input", "#car", function() {
      const car_plate = $(this).val();
      $.ajax({
        url: "get_car_data.php",
        type: "POST",
        data: {
          car_plate: car_plate,
        },
        dataType: "json",
        success: function(response) {
          const rate_per_day = Number(response.tarif_per_hari).toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          }).replace(/\s/g, '').replace(/,.00$/, '');
          $("#car_name").empty().append(response.nama_mobil);
          $("#car_plate").empty().append(response.plat_mobil);
          $("#input_car_plate").val(response.plat_mobil);
          $("#rate_per_day").empty().append(rate_per_day);
        },
      });
    });


    $(document).ready(function() {
      $('#start_date, #finish_date').on('change', function() {
        let startDate = new Date($('#start_date').val());
        let finishDate = new Date($('#finish_date').val());

        if (finishDate.toString() === 'Invalid Date') {
          finishDate = new Date(startDate.getTime() + 24 * 60 * 60 * 1000);
        }

        let ratePerDay = document.getElementById("rate_per_day").innerHTML;
        let leaseLength = Math.round((finishDate - startDate) / (24 * 60 * 60 * 1000));
        let cleanRatePerDay = Number(ratePerDay.replace(/[^0-9]/g, ''));
        let total = (leaseLength * cleanRatePerDay).toLocaleString('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        }).replace(/\s/g, '').replace(/,.00$/, '');;

        $('#lease_length').empty().append(`${leaseLength} hari`);
        $('#total').empty().append(total)
      });
    });

    $(document).on("input", "#discount", function() {
      let total_discount = $(this).val();
      let ratePerDay = document.getElementById("rate_per_day").innerHTML;
      let leaseLength = document.getElementById("lease_length").innerHTML;
      let total = document.getElementById("total").innerHTML;
      let cleanRatePerDay = Number(ratePerDay.replace(/[^0-9]/g, ''));
      let cleanLeaseLength = Number(leaseLength.replace(/[^\d]/g, ''));
      let cleanTotal = Number(total.replace(/[^\d]/g, ''));

      let findTotalCut = (total, discount) => {
        if (total === 0 || discount === 0) {
          return 0
        }
        return total * discount / 100;
      }

      let totalCut = findTotalCut(cleanTotal, total_discount);

      let cutOfTotals = Number(totalCut).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).replace(/\s/g, '').replace(/,.00$/, '');
      let newTotal = Number(cleanTotal - totalCut).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).replace(/\s/g, '').replace(/,.00$/, '');

      $('#cut_of_totals').empty().append(`-${cutOfTotals}`);
      $('#total').empty().append(newTotal);

      $.ajax({
        url: "get_discount_data.php",
        type: "POST",
        data: {
          total_discount: total_discount,
        },
        dataType: "json",
        success: function(response) {
          $('#discount_name').empty().append(`${response.nama_diskon} - ${response.total_diskon}%`);
        },
      });
    });
  </script>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/validation.js"></script>
</body>

</html>