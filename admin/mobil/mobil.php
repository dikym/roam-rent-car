<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

if ($_SESSION['level'] == "") {
  header("location: ../../auth/login.php?message=unrecognized");
  exit;
} else if ($_SESSION['level'] == "user") {
  header("location: ../../user/user_home.php?message=invalid_access");
  exit;
}

require "../../functions/functions.php";

$cars = query("SELECT * FROM mobil");

if (isset($_POST["search"])) {
  $cars = search_car($_POST["keyword"]);
}

$account = get_account();

if (isset($_POST["submit_edit"])) {
  $past_car_plate = $_POST["past_car_plate"];
  $car_plate = $_POST["car_plate"];

  if ($past_car_plate === $car_plate) {
    $car_id = $_POST["car_id"];
    $edit_car_query = query("SELECT * FROM mobil WHERE id_mobil = $car_id");
    $edit_car = mysqli_fetch_assoc($edit_car_query);

    if (empty($_POST["car_plate"])) {
      $_POST["car_plate"] = $edit_car["plat_mobil"];
    }

    if (empty($_POST["car"])) {
      $_POST["car"] = $edit_car["nama_mobil"];
    }

    if (empty($_POST["rate_per_day"])) {
      $_POST["rate_per_day"] = $edit_car["tarif_per_hari"];
    }

    edit_car($_POST);

    header("location: ?message=car_data_edit_success");
  } else {
    $query = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");
    $result = mysqli_fetch_assoc($query);

    if ($result) {
      header("location: ?message=error_edit_car_data");
      exit;
    } else {
      $car_id = $_POST["car_id"];
      $edit_car_query = query("SELECT * FROM mobil WHERE id_mobil = $car_id");
      $edit_car = mysqli_fetch_assoc($edit_car_query);

      if (empty($_POST["car_plate"])) {
        $_POST["car_plate"] = $edit_car["plat_mobil"];
      }

      if (empty($_POST["car"])) {
        $_POST["car"] = $edit_car["nama_mobil"];
      }

      if (empty($_POST["rate_per_day"])) {
        $_POST["rate_per_day"] = $edit_car["tarif_per_hari"];
      }

      edit_car($_POST);

      header("location: ?message=car_data_edit_success");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Data - Roam</title>
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
      if ($_GET['message'] == "delete_car_data_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Car data deleted successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "car_data_add_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Car data added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "car_data_edit_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Car data edited successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "error_delete_car_data") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to delete selected car, car is unavailable!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "error_edit_car_data") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to edit car data, car license plate already exists!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='mobil.php'\"></button>
              </div>";
      }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color: #645CBB">
      <div class="container">
        <a href="../admin_home.php"><img src="../../assets/img/white_logo.png" alt="Roam Logo" width="70px" class="navbar-brand"></a>
        <a href="#navbarToggler" id="navbar-toggler" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler text-white p-0 border-0">
          <i class="bi bi-list display-6" id="navbar-toggler-icon"></i>
        </a>
        <div class="collapse navbar-collapse" id="navbarToggler">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active opacity-75" aria-current="page" href="../admin_home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active fw-semibold" href="../mobil/mobil.php">Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../sewa/sewa.php">Sewa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../pembayaran/pembayaran.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../account_managment/account_managment.php">Accounts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../diskon/diskon.php">Diskon</a>
            </li>
          </ul>
          <div class="nav-item dropdown">
            <a class="dropdown-toggle link-light text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person"></i> <?= $account["name"]; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg-end shadow">
              <li><a class="dropdown-item opacity-75" href="../admin_account/admin_account_profile.php">Profiles</a></li>
              <li><a class="dropdown-item opacity-75" href="../admin_account/admin_account_setting.php">Settings</a></li>
              <li><a class="dropdown-item opacity-75" href="../../auth/logout.php">Logout</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item opacity-75" href="../../user/user_home.php">Switch to User</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="container">
    <h1 class="m-5 display-5 text-center">Data Mobil</h1>

    <div class="d-flex justify-content-center">
      <div class="col-xxl-12 col-md-12 table-responsive p-1">
        <div class="row mb-4 m-0">
          <div class="col-sm-8 p-0">
            <button class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add New Data</button>
          </div>
          <div class=" col-sm-4 p-0">
            <form class="input-group" method="post">
              <input class="form-control border-dark-subtle" type="search" placeholder="Search" name="keyword">
              <button class="btn btn-dark" type="submit" name="search"><i class=" bi bi-search"></i></button>
            </form>
          </div>
        </div>
        <?php if (isset($_POST["search"])) : ?>
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
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill"><?= $row["status"]; ?></span>
                      <?php else : ?>
                        <span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill"><?= $row["status"]; ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="#editModal" class="link-dark text-decoration-none me-3 btn-edit" data-bs-toggle="modal" data-id="<?= $row["id_mobil"]; ?>"><i class="bi bi-pencil-fill"></i></a><a href="delete_car_data.php?car_plate=<?= $row["plat_mobil"]; ?>&car_id=<?= $row["id_mobil"]; ?>" class="link-dark text-decoration-none" onclick="return confirm('Selected car data will delete permanently, are you sure?')"><i class="bi bi-trash3-fill"></i></a>
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
      <div id="editModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form method="post" autocomplete="off">
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Edit Data Mobil</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="car_id" id="car_id">
                <input type="hidden" name="past_car_plate" id="past_car_plate">
                <div class="form-group mb-3">
                  <label for="car_plate" class="form-label">Plat Mobil</label>
                  <input type="text" class="form-control" id="car_plate" name="car_plate">
                </div>
                <div class="form-group mb-3">
                  <label for="car" class="form-label">Mobil</label>
                  <input type="text" class="form-control" id="car" name="car">
                </div>
                <div class="form-group mb-3">
                  <label for="rate_per_day" class="form-label">Tarif per Hari</label>
                  <input type="number" class="form-control" id="rate_per_day" name="rate_per_day">
                </div>
                <div class="form-group mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select class="form-select" id="status" name="status">
                    <option selected disabled value="">Choose...</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white" style="background-color: #645CBB;" name="submit_edit">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div id="addModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form method="post" action="insert_mobil.php" autocomplete="off" id="formAdd" novalidate>
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Add New Data</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="car_plate" class="form-label">Plat Mobil</label>
                  <input type="text" class="form-control" id="addCarPlate" name="car_plate" required>
                  <div class="invalid-feedback" id="carPlateFeedback"></div>
                </div>
                <div class="form-group mb-3">
                  <label for="car" class="form-label">Mobil</label>
                  <input type="text" class="form-control" id="addCar" name="car" required>
                  <div class="invalid-feedback" id="carFeedback"></div>
                </div>
                <div class="form-group mb-3">
                  <label for="rate_per_day" class="form-label">Tarif per Hari</label>
                  <input type="number" class="form-control" id="addRatePerDay" name="rate_per_day" required>
                  <div class="invalid-feedback" id="ratePerDayFeedback"></div>
                </div>
                <div class="form-group mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select class="form-select" id="addStatus" name="status" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                  </select>
                  <div class="invalid-feedback" id="statusFeedback"></div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white" style="background-color: #645CBB;">Add</button>
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
    $(document).on("click", ".btn-edit", function() {
      const car_id = $(this).data("id");
      $.ajax({
        url: "get_data.php",
        type: "POST",
        data: {
          car_id: car_id,
        },
        dataType: "json",
        success: function(response) {
          $("#car_id").val(response.id_mobil);
          $("#past_car_plate").val(response.plat_mobil);
          $("#car_plate").val(response.plat_mobil);
          $("#car").val(response.nama_mobil);
          $("#rate_per_day").val(response.tarif_per_hari);
          $("#status").val(response.status);
        },
      });
    });
  </script>

  <script>
    const form = document.getElementById("formAdd");
    form.addEventListener("submit", function(event) {
      event.preventDefault();
      const carPlate = document.getElementById("addCarPlate");
      const carPlateValue = carPlate.value.trim();
      const carPlateFeedback = document.getElementById("carPlateFeedback");
      const car = document.getElementById("addCar");
      const carValue = car.value.trim();
      const carFeedback = document.getElementById("carFeedback");
      const ratePerDay = document.getElementById("addRatePerDay");
      const ratePerDayValue = ratePerDay.value.trim();
      const ratePerDayFeedback = document.getElementById("ratePerDayFeedback");
      const status = document.getElementById("addStatus");
      const statusValue = status.value.trim();
      const statusFeedback = document.getElementById("statusFeedback");

      if (carPlateValue === "") {
        carPlate.classList.add("is-invalid");
        carPlateFeedback.innerHTML = "Please input valid car license plate.";
        carPlate.focus();
      } else if (isDuplicate(carPlateValue)) {
        carPlate.classList.add("is-invalid");
        carPlateFeedback.innerHTML = "Car license plate already exists.";
        carPlate.focus();
      } else {
        carPlate.classList.remove("is-invalid");
        carPlateFeedback.innerHTML = "";
        carPlate.classList.add("is-valid");
      }

      if (carValue === "") {
        car.classList.add("is-invalid");
        carFeedback.innerHTML = "Please input valid car.";
        car.focus();
      } else {
        car.classList.remove("is-invalid");
        carFeedback.innerHTML = "";
        car.classList.add("is-valid");
      }

      if (ratePerDayValue === "") {
        ratePerDay.classList.add("is-invalid");
        ratePerDayFeedback.innerHTML = "Please input valid rate car per day.";
        ratePerDay.focus();
      } else {
        ratePerDay.classList.remove("is-invalid");
        ratePerDayFeedback.innerHTML = "";
        ratePerDay.classList.add("is-valid");
      }

      if (statusValue === "") {
        status.classList.add("is-invalid");
        statusFeedback.innerHTML = "Please select valid car status.";
        status.focus();
      } else {
        status.classList.remove("is-invalid");
        statusFeedback.innerHTML = "";
        status.classList.add("is-valid");
      }

      if (carPlateValue !== "" && !isDuplicate(carPlateValue) && carValue !== "" && ratePerDayValue !== "" && statusValue !== "") {
        form.submit();
      }
    });

    function isDuplicate(plat) {
      let isDuplicate = false;
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "check_plate.php", false);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          const response = JSON.parse(this.responseText);
          isDuplicate = response.isDuplicate;
        }
      };
      xhr.send(`car_plate=${plat}`);
      return isDuplicate;
    }
  </script>

  <!-- <script>
    $(document).ready(function() {
      // validate plat on blur
      $('#addCarPlate').on('blur', function() {
        const car_plate = $('#addCarPlate').val();
        if (car_plate != '') {
          $.ajax({
            url: 'cek_plat.php',
            method: 'POST',
            data: {
              car_plate: car_plate
            },
            success: function(response) {
              if (response == 'exists') {
                $('#carPlateFeedback').html('Plat ini sudah digunakan');
                $('#addCarPlate').addClass('is-invalid');
                $('#submitAdd').attr('disabled', true);
              } else {
                $('#carPlateFeedback').html('');
                $('#addCarPlate').removeClass('is-invalid');
                $('#addCarPlate').addClass('is-valid');
                // check if all fields are filled
                const car = $('#addCar').val();
                const ratePerDay = $('#addRatePerDay').val();
                const status = $('#addStatus').val();
                if (car != '' && ratePerDay != '' && status != '') {
                  $('#submitAdd').attr('disabled', false);
                }
              }
            }
          });
        } else {
          $('#carPlateFeedback').html('Harap isi field ini');
          $('#addCarPlate').addClass('is-invalid');
          $('#submitAdd').attr('disabled', true);
        }
      });

      // validate other fields on blur
      $('#addCar, #addRatePerDay, #addStatus').on('blur', function() {
        const car = $('#addCar').val();
        const ratePerDay = $('#addRate').val();
        const status = $('#addStatus').val();
        if (car == '') {
          $('#carFeedback').html('Harap isi field ini');
          $('#addCar').addClass('is-invalid');
          $('#submitAdd').attr('disabled', true);
        } else {
          $('#carFeedback').html('');
          $('#addCar').removeClass('is-invalid');
          if (tarif == '') {
            $('#ratePerDayFeedback').html('Harap isi field ini');
            $('#addRatePerDay').addClass('is-invalid');
            $('#submitAdd').attr('disabled', true);
          } else {
            $('#ratePerDayFeedback').html('');
            $('#addRatePerDay').removeClass('is-invalid');
            if (status == '') {
              $('#statusFeedback').html('Harap pilih status');
              $('#addStatus').addClass('is-invalid');
              $('#submitAdd').attr('disabled', true);
            } else {
              $('#statusFeedback').html('');
              $('#addStatus').removeClass('is-invalid');
              // check if plat is available
              const plat = $('#plat').val();
              if (plat != '' && $('#carPlateFeedback').html() == '') {
                $('#submitAdd').attr('disabled', false);
              }
            }
          }
        }
      });
    });
  </script> -->

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/car.js"></script>
</body>

</html>
