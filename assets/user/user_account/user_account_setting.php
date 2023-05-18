<?php
session_start();

if ($_SESSION['level'] == "") {
  header("location: ../auth/login.php?message=unrecognized");
  exit;
}

require "../../functions/functions.php";

$account = get_account();

if (isset($_POST["submit"])) {
  if (empty($_POST["new_name"])) {
    $_POST["new_name"] = $account["name"];
  }

  if (empty($_POST["new_username"])) {
    $_POST["new_username"] = $account["username"];
  }

  if (empty($_POST["new_password"])) {
    $_POST["new_password"] = $account["password"];
  }

  setting_edit_account($_POST);

  header("location: user_account_setting.php?message=update_account_success");
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Setting - Roam</title>
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
      .btn {
        font-size: 0.9rem;
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
  <?php
  if (isset($_GET['message'])) {
    if ($_GET['message'] == "invalid_access") {
      echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
              You don't have access to Admin!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } else if ($_GET['message'] == "update_account_success") {
      echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
                Profile update successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='user_account_setting.php'\"></button>
              </div>";
    } else if ($_GET['message'] == "error_delete_account") {
      echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
              Fail to delete account, you are still renting a car!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='user_account_setting.php'\"></button>
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
            <a class="nav-link active opacity-75" href="../pemesanan/pemesanan.php">Pemesanan</a>
          </li>
        </ul>
        <div class="dropdown">
          <a class="dropdown-toggle link-light text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person"></i> <?= $account["name"]; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg-end shadow">
            <li><a class="dropdown-item opacity-75" href="user_account_profile.php">Profile</a></li>
            <li><a class="dropdown-item fw-semibold" href="user_account_setting.php">Settings</a></li>
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

  <main class="container">
    <h1 class="m-5 display-5 text-center">User Account Settings</h1>

    <div class="d-flex justify-content-center m-0">
      <div class="p-1">
        <h1 class="fs-4 fw-bold mb-5 text-center">Edit Profile</h1>
        <form method="post" class="row g-3" autocomplete="off">
          <div class=" col-md-2 mb-2">
            <div class="input-group">
              <div class="input-group-text text-muted">ID</div>
              <input type="text" class="form-control" aria-label="id" value="<?= $_SESSION["id"]; ?>" name="id" disabled readonly>
            </div>
          </div>

          <div class="col-md-10 mb-2">
            <div class="input-group">
              <div class="input-group-text">Name</div>
              <input type="text" class="form-control" value="<?= $account["name"]; ?>" name="new_name">
            </div>
          </div>

          <div class="col-md-5 mb-2">
            <div class="input-group">
              <div class="input-group-text">@</div>
              <input type="text" class="form-control" value="<?= $account["username"]; ?>" name="new_username">
            </div>
          </div>

          <div class="col-md-7 mb-3">
            <div class="input-group">
              <div class="input-group-text">Password</div>
              <input type="password" class="form-control" id="password-input" placeholder="New Password" name="new_password">
              <span class="input-group-text" id="show-password" onclick="togglePassword('password-input', 'show-password')"><i class="bi bi-eye"></i></span>
            </div>
          </div>

          <div class="d-flex">
            <a href="user_account_delete.php?id=<?= $_SESSION["id"] ?>" class="btn btn-danger me-auto text-decoration-none" onclick="return confirm('Account will delete permanently, are you sure?');">Delete account</a>
            <button type="submit" class="btn text-white" name="submit" style="background-color: #645CBB;">
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="footer mt-auto pt-4">
    <div class="text-center py-2 bg-light">
      <span class="mb-0 text-muted">Made with &#x2764;&#xFE0F; by Diky</span>
    </div>
  </footer>

  <script src="../../assets/js/nav_toggler_icon.js"></script>
  <script src="../../assets/js/toggle_password.js"></script>
</body>

</html>