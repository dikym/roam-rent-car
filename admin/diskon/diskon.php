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

query("DELETE FROM diskon WHERE selesai = '$current_date'");

$discounts = query("SELECT * FROM diskon");

if (isset($_POST["search"])) {
  $discounts = search_discount($_POST["keyword"]);
}

$account = get_account();

if (isset($_POST["submit_add"])) {
  $total_discount = $_POST["total_discount"];
  $start_date = $_POST["start_date"];
  $finish_date = $_POST["finish_date"];

  if ($total_discount > 99) {
    header("location: ?message=invalid_total_discount");
    exit;
  } else if ($start_date === $finish_date) {
    header("location: ?message=invalid_discount_lease");
    exit;
  }

  add_discount($_POST);

  header("location: ?message=discount_data_add_success");
} else if (isset($_POST["submit_edit"])) {
  $total_discount = $_POST["total_discount"];
  $start_date = $_POST["start_date"];
  $finish_date = $_POST["finish_date"];

  if ($total_discount > 99) {
    header("location: ?message=invalid_total_discount");
    exit;
  } else if ($start_date === $finish_date) {
    header("location: ?message=invalid_discount_lease");
    exit;
  }

  $discount_id = $_POST["discount_id"];
  $edit_discount_query = query("SELECT * FROM diskon WHERE id_diskon = $discount_id");
  $edit_discount = mysqli_fetch_assoc($edit_discount_query);

  if (empty($_POST["discount_name"])) {
    $_POST["discount_name"] = $edit_discount["nama_diskon"];
  }

  if (empty($_POST["total_discount"])) {
    $_POST["total_discount"] = $edit_discount["total_diskon"];
  }

  edit_discount($_POST);

  header("location: ?message=discount_data_edit_success");
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discount Managment - Roam</title>
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
      if ($_GET['message'] == "delete_discount_data_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Discount deleted successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='diskon.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "discount_data_add_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Discount added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='diskon.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "discount_data_edit_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Discount edited successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='diskon.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "invalid_total_discount") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to add or edit discount, total discount should be less than 100%!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='diskon.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "invalid_discount_lease") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to add or edit discount, start and finish date should be not same!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='diskon.php'\"></button>
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
              <a class="nav-link active opacity-75" href="../sewa/sewa.php">Sewa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../pembayaran/pembayaran.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="../account_managment/account_managment.php">Accounts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active fw-semibold" href="diskon.php">Diskon</a>
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
    <h1 class="m-5 display-5 text-center">Discount Managment</h1>

    <div class="d-flex justify-content-center">
      <div class="col-xxl-12 col-md-12 table-responsive p-1">
        <div class="row mb-4 m-0">
          <div class="col-sm-8 p-0">
            <button class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add New Discount</button>
          </div>
          <div class=" col-sm-4 p-0">
            <form class="input-group" method="post">
              <input class="form-control border-dark-subtle" type="search" placeholder="Search" name="keyword">
              <button class="btn btn-dark" type="submit" name="search"><i class=" bi bi-search"></i></button>
            </form>
          </div>
        </div>
        <?php if (isset($_POST["search"])) : ?>
          <span class="search-info"><?= mysqli_num_rows($discounts); ?> data found</span>
        <?php endif; ?>
        <div class="card table-responsive">
          <table class="table table-hover align-middle m-0">
            <thead class="table-light text-nowrap">
              <tr align="center">
                <th>id</th>
                <th>Nama Diskon</th>
                <th>Total Diskon</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Lama Diskon</th>
                <th>Created Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              <?php if (mysqli_num_rows($discounts) > 0) : ?>
                <?php foreach ($discounts as $row) : ?>
                  <tr align="center">
                    <td><?= $row["id_diskon"]; ?></td>
                    <td><?= $row["nama_diskon"]; ?></td>
                    <td><?= $row["total_diskon"]; ?>%</td>
                    <td><?= $row["mulai"]; ?></td>
                    <td><?= $row["selesai"]; ?></td>
                    <td><?= $row["lama_diskon"]; ?></td>
                    <td><?= $row["created_date"]; ?></td>
                    <td>
                      <a href="#editModal" class="link-dark text-decoration-none me-3 btn-edit" data-bs-toggle="modal" data-id="<?= $row["id_diskon"]; ?>"><i class="bi bi-pencil-fill"></i></a><a href="delete_discount_data.php?discount_id=<?= $row["id_diskon"]; ?>" class="link-dark text-decoration-none" onclick="return confirm('Selected discount data will delete permanently, are you sure?')"><i class="bi bi-trash3-fill"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="8" align="center"><em>Data not found</em></td>
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
                <h4 class="modal-title text-white">Edit Discount</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" id="discount_id" name="discount_id">
                <div class="form-group mb-3">
                  <label for="discount_name" class="form-label">Nama Diskon</label>
                  <input type="text" class="form-control" id="discount_name" name="discount_name">
                </div>
                <div class="form-group mb-3">
                  <label for="total_discount" class="form-label">Total Diskon</label>
                  <div class="input-group">
                    <input type="number" class="form-control" id="total_discount" name="total_discount">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="start_date" class="form-label">Tgl Mulai</label>
                  <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="form-group mb-3">
                  <label for="finish_date" class="form-label">Tgl Selesai</label>
                  <input type="date" class="form-control" id="finish_date" name="finish_date">
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
            <form method="post" class="needs-validation" autocomplete="off" novalidate>
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Add New Discount</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="discount_name" class="form-label">Nama Diskon</label>
                  <input type="text" class="form-control" id="discount_name" name="discount_name" required>
                  <div class="invalid-feedback">
                    Please input valid discount name.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="total_discount" class="form-label">Total Diskon</label>
                  <div class="input-group has-validation">
                    <input type="number" class="form-control" id="total_discount" name="total_discount" required>
                    <span class="input-group-text">%</span>
                    <div class="invalid-feedback">
                      Please input valid total discount.
                    </div>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="start_date" class="form-label">Tgl Mulai</label>
                  <input type="date" class="form-control" id="start_date" name="start_date" required>
                  <div class="invalid-feedback">
                    Please select a valid start date.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="finish_date" class="form-label">Tgl Selesai</label>
                  <input type="date" class="form-control" id="finish_date" name="finish_date" required>
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
      const discount_id = $(this).data("id");
      $.ajax({
        url: "get_data.php",
        type: "POST",
        data: {
          discount_id: discount_id,
        },
        dataType: "json",
        success: function(response) {
          $("#discount_id").val(response.id_diskon);
          $("#discount_name").val(response.nama_diskon);
          $("#total_discount").val(response.total_diskon);
          $("#start_date").val(response.mulai);
          $("#finish_date").val(response.selesai);
        },
      });
    });
  </script>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src=" ../../assets/js/validation.js"></script>
  <script src="../../assets/js/discount.js"></script>
</body>

</html>