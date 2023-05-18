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

$lease_data = query("SELECT * FROM sewa");

if (isset($_POST["search"])) {
  $lease_data = search_lease_data($_POST["keyword"]);
}

if (isset($_POST["submit_add"])) {
  add_lease_data($_POST);

  header("location: ?message=lease_data_add_success");
} else if (isset($_POST["submit_edit"])) {
  edit_lease_data($_POST);

  header("location: ?message=lease_data_edit_success");
}

$account = get_account();
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lease Data - Roam</title>
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
      if ($_GET['message'] == "delete_lease_data_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Lease data deleted successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='sewa.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "lease_data_add_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Lease data added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='sewa.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "lease_data_edit_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Lease data edited successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='sewa.php'\"></button>
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
              <a class="nav-link active opacity-75" href="../mobil/mobil.php">Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active fw-semibold" href="sewa.php">Sewa</a>
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
    <h1 class="m-5 display-5 text-center">Data Sewa</h1>

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
          <span class="search-info"><?= mysqli_num_rows($lease_data); ?> data found</span>
        <?php endif; ?>
        <div class="card table-responsive">
          <table class="table table-hover align-middle m-0">
            <thead class="table-light text-nowrap">
              <tr align="center">
                <th>id</th>
                <th>Tgl Pemesanan</th>
                <th>Nama</th>
                <th>Mobil</th>
                <th>Mulai Sewa</th>
                <th>Selesai Sewa</th>
                <th>Lama Sewa</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              <?php if (mysqli_num_rows($lease_data) > 0) : ?>
                <?php foreach ($lease_data as $row) : ?>
                  <tr align="center">
                    <td><?= $row["id_sewa"]; ?></td>
                    <td><?= $row["tgl_pemesanan"]; ?></td>
                    <td>
                      <?php
                      $id_user = $row["id_user"];
                      $user_query = query("SELECT * FROM account WHERE id = '$id_user'");
                      $user = mysqli_fetch_assoc($user_query);
                      echo $user["name"];
                      ?>
                    </td>
                    <td>
                      <?php
                      $car_plate = $row["plat_mobil"];
                      $car_query = query("SELECT * FROM mobil WHERE plat_mobil = '$car_plate'");
                      $car = mysqli_fetch_assoc($car_query);
                      echo $car["nama_mobil"];
                      ?>
                    </td>
                    <td><?= $row["mulai"]; ?></td>
                    <td><?= $row["selesai"]; ?></td>
                    <td><?= $row["lama_sewa"]; ?></td>
                    <td>Rp.<?= number_format($row["total_pembayaran"], 0, ",", "."); ?></td>
                    <td>
                      <a href="#editModal" class="link-dark text-decoration-none me-3 btn-edit" id="editModalButton" data-bs-toggle="modal" data-id="<?= $row["id_sewa"]; ?>"><i class="bi bi-pencil-fill"></i></a><a href="delete_lease_data.php?lease_id=<?= $row["id_sewa"]; ?>" class="link-dark text-decoration-none" onclick="return confirm('Selected lease data will delete permanently, are you sure?')"><i class="bi bi-trash3-fill"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="10" align="center"><em>Data not found</em></td>
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
                <h4 class="modal-title text-white">Edit Data Sewa</h4>
                <button type="button" class="btn text-white close_btn" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="lease_id" id="lease_id">
                <input type="hidden" name="past_car_plate" id="past_car_plate">
                <div class="form-group mb-3">
                  <label for="user_id" class="form-label">Nama</label>
                  <select class="form-select" id="user_id" name="user_id">
                    <?php $users = query("SELECT * FROM account"); ?>
                    <?php if (mysqli_num_rows($users) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="">Users is empty</option>
                    <?php endif; ?>
                    <?php foreach ($users as $user) : ?>
                      <option value="<?= $user["id"]; ?>"><?= $user["name"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label for="car_plate" class="form-label">Mobil</label>
                  <select class="form-select" id="car_plate_select" name="car_plate">
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
                </div>
                <div class="form-group mb-3">
                  <label for="start_date" class="form-label">Mulai Sewa</label>
                  <input type="date" class="form-control" name="start_date" id="start_date">
                </div>
                <div class="form-group mb-3">
                  <label for="finish_date" class="form-label">Selesai Sewa</label>
                  <input type="date" class="form-control" name="finish_date" id="finish_date">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_btn" data-bs-dismiss="modal">Cancel</button>
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
            <form method="post" class="needs-validation" autocomplete="off" novalidate>
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Add New Data</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="user_id" class="form-label">Nama</label>
                  <select class="form-select" id="user_id" name="user_id" required>
                    <?php $users = query("SELECT * FROM account"); ?>
                    <?php if (mysqli_num_rows($users) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="">Users is empty</option>
                    <?php endif; ?>
                    <?php foreach ($users as $user) : ?>
                      <option value="<?= $user["id"]; ?>"><?= $user["name"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid name.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="car_plate" class="form-label">Mobil</label>
                  <select class="form-select" id="car_plate" name="car_plate" required>
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
                <div class="form-group mb-3">
                  <label for="start_date" class="form-label">Mulai Sewa</label>
                  <input type="date" class="form-control" name="start_date" id="start_date" required>
                  <div class="invalid-feedback">
                    Please select a valid start date.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="finish_date" class="form-label">Selesai Sewa</label>
                  <input type="date" class="form-control" name="finish_date" id="finish_date" required>
                  <div class="invalid-feedback">
                    Please select a valid finish date.
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn text-white" style="background-color: #645CBB;" name="submit_add">Add</button>
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
      const lease_id = $(this).data("id");
      $.ajax({
        url: "get_data.php",
        type: "POST",
        data: {
          lease_id: lease_id,
        },
        dataType: "json",
        success: function(response) {
          $("#lease_id").val(response.id_sewa);
          $("#user_id").val(response.id_user);
          $("#past_car_plate").val(response.plat_mobil);
          $("#car_plate_select").append(`<option selected value="${response.plat_mobil}" id="append_option">${response.nama_mobil}</option>`);
          $("#start_date").val(response.mulai);
          $("#finish_date").val(response.selesai);
        },
      });
    });

    $(".close_btn").click(function() {
      setTimeout(() => {
        $("#append_option").remove();
      }, 300);
    });
  </script>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/validation.js"></script>
</body>

</html>