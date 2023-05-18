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

$payments = query("SELECT * FROM pembayaran JOIN sewa ON pembayaran.id_sewa = sewa.id_sewa");

if (isset($_POST["search"])) {
  $payments = search_payment($_POST["keyword"]);
}

$account = get_account();

if (isset($_POST["submit_add"])) {
  add_payment($_POST);

  header("location: ?message=payment_data_add_success");
} else if (isset($_POST["submit_edit"])) {
  $payment_id = $_POST["payment_id"];
  $edit_payment_query = query("SELECT * FROM pembayaran WHERE id_pembayaran = $payment_id");
  $edit_payment = mysqli_fetch_assoc($edit_payment_query);

  if (empty($_POST["user_cash"])) {
    $_POST["user_cash"] = $edit_payment["uang_user"];
  }

  edit_payment($_POST);

  header("location: ?message=payment_data_edit_success");
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Data - Roam</title>
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
      if ($_GET['message'] == "delete_payment_data_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Payment data deleted successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='pembayaran.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "payment_data_add_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Payment data added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='pembayaran.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "payment_data_edit_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Payment data edited successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='pembayaran.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "error_delete_payment_data") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to delete selected payment, payment is unavailable!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='pembayaran.php'\"></button>
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
              <a class="nav-link active fw-semibold" href="pembayaran.php">Pembayaran</a>
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
    <h1 class="m-5 display-5 text-center">Data Pembayaran</h1>

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
          <span class="search-info"><?= mysqli_num_rows($payments); ?> data found</span>
        <?php endif; ?>
        <div class="card table-responsive">
          <table class="table table-hover align-middle m-0">
            <thead class="table-light text-nowrap">
              <tr align="center">
                <th>id</th>
                <th>id Sewa</th>
                <th>Tgl Pembayaran</th>
                <th>Total</th>
                <th>Diskon</th>
                <th>Uang User</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              <?php if (mysqli_num_rows($payments) > 0) : ?>
                <?php foreach ($payments as $row) : ?>
                  <tr align="center">
                    <td><?= $row["id_pembayaran"]; ?></td>
                    <td><?= $row["id_sewa"]; ?></td>
                    <td><?= $row["tgl_pembayaran"]; ?></td>
                    <?php if ($row["diskon"] > 0) : ?>
                      <td><span class="text-muted"><s>Rp.<?= number_format($row["total_pembayaran"], 0, ",", "."); ?></s></span> Rp.<?= number_format($row["total"], 0, ",", "."); ?></td>
                    <?php else : ?>
                      <td>Rp.<?= number_format($row["total"], 0, ",", "."); ?></td>
                    <?php endif; ?>
                    <td><?= $row["diskon"]; ?>%</td>
                    <td>Rp.<?= number_format($row["uang_user"], 0, ",", "."); ?></td>
                    <td>
                      <?php if ($row["status"] == "lunas") : ?>
                        <span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill"><?= $row["status"]; ?></span>
                      <?php else : ?>
                        <span class="badge bg-warning-subtle border border-warning-subtle text-warning-emphasis rounded-pill"><?= $row["status"]; ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="#editModal" class="link-dark text-decoration-none me-3 btn-edit" data-bs-toggle="modal" data-ids="<?= $row["id_pembayaran"]; ?>, <?= $row["id_sewa"]; ?>"><i class="bi bi-pencil-fill"></i></a><a href="delete_payment_data.php?payment_id=<?= $row["id_pembayaran"]; ?>" class="link-dark text-decoration-none" onclick="return confirm('Selected payment data will delete permanently, are you sure?')"><i class="bi bi-trash3-fill"></i></a>
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
            <form method="post">
              <div class=" modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Edit Data Pembayaran</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="payment_id" id="payment_id">
                <input type="hidden" name="past_total" id="past_total">
                <div class="form-group mb-3">
                  <label for="lease_id" class="form-label">id Sewa</label>
                  <select class="form-select" id="leaseIdEdit" name="lease_id" onfocus="autofill('leaseIdEdit', ['totalEdit', 'past_total'], 'leaseLengthEdit')">
                    <?php $leases = query("SELECT * FROM sewa"); ?>
                    <?php if (mysqli_num_rows($leases) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="">Lease id is empty</option>
                    <?php endif; ?>
                    <?php foreach ($leases as $lease) : ?>
                      <option value="<?= $lease["id_sewa"]; ?>"><?= $lease["id_sewa"]; ?> - <?= $lease["plat_mobil"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label for="total" class="form-label">Total</label>
                  <input type="number" class="form-control" name="total" id="totalEdit" readonly>
                </div>
                <div class="form-group mb-3">
                  <label for="lease_length" class="form-label">Lama sewa</label>
                  <input type="text" class="form-control" name="lease_length" id="leaseLengthEdit" readonly>
                </div>
                <div class="form-group mb-3">
                  <label for="user_cash" class="form-label">Uang User</label>
                  <input type="number" class="form-control" name="user_cash" id="user_cash">
                </div>
                <div class="form-group mb-3">
                  <label for="discount" class="form-label">Diskon</label>
                  <select class="form-select" id="discount" name="discount">
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
              <div class=" modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Add New Data</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="lease_id" class="form-label">id Sewa</label>
                  <select class="form-select" id="leaseIdAdd" name="lease_id" onfocus="autofill('leaseIdAdd', ['totalAdd'], 'leaseLengthAdd')" required>
                    <?php $leases = query("SELECT * FROM sewa"); ?>
                    <?php if (mysqli_num_rows($leases) > 0) : ?>
                      <option selected disabled value="">Choose...</option>
                    <?php else : ?>
                      <option selected disabled value="">Car plate is empty</option>
                    <?php endif; ?>
                    <?php foreach ($leases as $lease) : ?>
                      <option value="<?= $lease["id_sewa"]; ?>"><?= $lease["id_sewa"]; ?> - <?= $lease["plat_mobil"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid car plate.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="total" class="form-label">Total</label>
                  <input type="number" class="form-control" name="total" id="totalAdd" required readonly>
                </div>
                <div class="form-group mb-3">
                  <label for="lease_length" class="form-label">Lama sewa</label>
                  <input type="text" class="form-control" name="lease_length" id="leaseLengthAdd" required readonly>
                </div>
                <div class="form-group mb-3">
                  <label for="user_cash" class="form-label">Uang User</label>
                  <input type="number" class="form-control" name="user_cash" id="user_cash" required>
                  <div class="invalid-feedback">
                    Please input a valid user cash.
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
      const ids = $(this).data("ids").split(",");
      const payment_id = ids[0];
      const lease_id = ids[1];
      $.ajax({
        url: "get_data.php",
        type: "POST",
        data: {
          payment_id: payment_id,
          lease_id: lease_id,
        },
        dataType: "json",
        success: function(response) {
          $("#payment_id").val(response.id_pembayaran);
          $("#leaseIdEdit").val(response.id_sewa);
          $("#user_cash").val(response.uang_user);
          $("#discount").val(response.diskon);
          $("#past_total").val(response.total_pembayaran);
          $("#totalEdit").val(response.total);
          $("#leaseLengthEdit").val(response.lama_sewa);
        },
      });
    });

    const autofill = (leaseId, total, leaseLength) => {
      $(document).on("input", `#${leaseId}`, function() {
        const lease_id = $(this).val();
        $.ajax({
          url: "get_total.php",
          type: "POST",
          data: {
            lease_id: lease_id,
          },
          dataType: "json",
          success: function(response) {
            $(`#${total[0]}`).val(response.total_pembayaran);
            $(`#${total[1]}`).val(response.total_pembayaran);
            $(`#${leaseLength}`).val(response.lama_sewa);
          },
        });
      });
    };
  </script>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/autofill.js"></script>
  <script src="../../assets/js/validation.js"></script>
  <script src="../../assets/js/payment.js"></script>
</body>

</html>
