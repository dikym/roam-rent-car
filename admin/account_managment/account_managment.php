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

$accounts = query("SELECT * FROM account");

if (isset($_POST["search"])) {
  $accounts = search_account($_POST["keyword"]);
}

$account = get_account();

if (isset($_POST["submit_add"])) {
  add_account($_POST);

  header("location: ?message=account_add_success");
} else if (isset($_POST["submit_edit"])) {
  $id = $_POST["id"];
  $edit_account_query = query("SELECT * FROM account WHERE id = $id");
  $edit_account = mysqli_fetch_assoc($edit_account_query);

  if (empty($_POST["new_name"])) {
    $_POST["new_name"] = $edit_account["name"];
  }

  if (empty($_POST["new_username"])) {
    $_POST["new_username"] = $edit_account["username"];
  }

  if (empty($_POST["new_password"])) {
    $_POST["new_password"] = $edit_account["password"];
  }

  edit_account($_POST);

  header("location: ?message=account_edit_success");
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Managment - Roam</title>
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
      if ($_GET['message'] == "delete_account_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Account deleted successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='account_managment.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "account_add_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Account added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='account_managment.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "account_edit_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Account edited successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='account_managment.php'\"></button>
              </div>";
      } else if ($_GET['message'] == "error_delete_account") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
                Fail to delete account, selected account is still renting a car!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='account_managment.php'\"></button>
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
              <a class="nav-link active fw-semibold" href="account_managment.php">Accounts</a>
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
    <h1 class="m-5 display-5 text-center">Account Managment</h1>

    <div class="d-flex justify-content-center">
      <div class="col-xxl-12 col-md-12 table-responsive p-1">
        <div class="row mb-4 m-0">
          <div class="col-sm-8 p-0">
            <button class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add New Account</button>
          </div>
          <div class=" col-sm-4 p-0">
            <form class="input-group" method="post">
              <input class="form-control border-dark-subtle" type="search" placeholder="Search" name="keyword">
              <button class="btn btn-dark" type="submit" name="search"><i class=" bi bi-search"></i></button>
            </form>
          </div>
        </div>
        <?php if (isset($_POST["search"])) : ?>
          <span class="search-info"><?= mysqli_num_rows($accounts); ?> data found</span>
        <?php endif; ?>
        <div class="card table-responsive">
          <table class="table table-hover align-middle m-0">
            <thead class="table-light text-nowrap">
              <tr align="center">
                <th>id</th>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Created Date</th>
                <th>Level</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-nowrap">
              <?php if (mysqli_num_rows($accounts) > 0) : ?>
                <?php foreach ($accounts as $row) : ?>
                  <tr align="center">
                    <td><?= $row["id"]; ?></td>
                    <td><?= $row["name"]; ?></td>
                    <td><?= $row["username"]; ?></td>
                    <td><?= $row["password"]; ?></td>
                    <td><?= $row["created_date"]; ?></td>
                    <td>
                      <?php if ($row["level"] == "admin") : ?>
                        <span class="badge bg-primary-subtle border border-primary-subtle text-primary-emphasis rounded-pill"><?= $row["level"]; ?></span>
                      <?php else : ?>
                        <span class="badge bg-dark-subtle border border-dark-subtle text-dark-emphasis rounded-pill"><?= $row["level"]; ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="#editModal" class="link-dark text-decoration-none me-3 btn-edit" data-bs-toggle="modal" data-id="<?= $row["id"]; ?>"><i class="bi bi-pencil-fill"></i></a><a href="delete_account.php?id=<?= $row["id"]; ?>" class="link-dark text-decoration-none" onclick="return confirm('Selected account data will delete permanently, are you sure?')"><i class="bi bi-trash3-fill"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="7" align="center"><em>Data not found</em></td>
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
                <h4 class="modal-title text-white">Edit Account</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <div class="form-group mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="new_name">
                </div>
                <div class="form-group mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="new_username">
                </div>
                <div class="form-group mb-3">
                  <label for="password-input" class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password-input" name="new_password">
                    <span class="input-group-text" id="show-password" onclick="togglePassword('password-input', 'show-password')"><i class="bi bi-eye"></i></span>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="level_account" class="d-block form-label">Level Account</label>
                  <select class="form-select" id="level_account" name="new_level_account">
                    <option selected disabled value="">Choose...</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
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
              <div class="modal-header" style="background-color: #A084DC;">
                <h4 class="modal-title text-white">Add New Account</h4>
                <button type="button" class="btn text-white" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="modal-body">
                <div class="form-group mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                  <div class="invalid-feedback">
                    Please input your name.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" required>
                  <div class="invalid-feedback">
                    Please choose a username.
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="password-input" class="form-label">Password</label>
                  <div class="input-group has-validation">
                    <input type="password" class="form-control" id="password-input2" name="password" required>
                    <span class="input-group-text" id="show-password2" onclick="togglePassword('password-input2', 'show-password2')"><i class="bi bi-eye"></i></span>
                    <div class="invalid-feedback">
                      Please choose a password.
                    </div>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="level_account" class="d-block form-label">Level Account</label>
                  <select class="form-select" id="level_account" name="level_account" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </select>
                  <div class="invalid-feedback">
                    Please select valid level account.
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

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/toggle_password.js"></script>
  <script src=" ../../assets/js/validation.js"></script>
  <script src="../../assets/js/account.js"></script>
</body>

</html>
