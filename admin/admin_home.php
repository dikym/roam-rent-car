<?php
session_start();

if ($_SESSION['level'] == "") {
  header("location: ../auth/login.php?message=unrecognized");
  exit;
} else if ($_SESSION['level'] == "user") {
  header("location: ../user/user_home.php?message=invalid_access");
  exit;
}

require "../functions/functions.php";

$account = get_account();
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Roam</title>
  <link rel="shortcut icon" href="../assets/img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

  <style>
    .bd-masthead {
      margin-top: 39vh;
    }

    @media (max-height: 800px) {
      .bd-masthead {
        margin-top: 34vh;
      }
    }

    @media (max-width: 300px) {
      .bd-masthead {
        margin-top: 33vh;
      }
    }

    @media (min-width: 1200px) {
      .bd-masthead {
        margin-top: 37vh;
      }
    }

    @media only screen and (max-width: 768px) {

      p,
      .footer,
      .nav-link,
      .dropdown-item,
      .dropdown-toggle {
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
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color: #645CBB">
      <div class="container">
        <a href="admin_home.php"><img src="../assets/img/white_logo.png" alt="Roam Logo" width="70px" class="navbar-brand"></a>
        <a href="#navbarToggler" id="navbar-toggler" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler text-white p-0 border-0">
          <i class="bi bi-list display-6" id="navbar-toggler-icon"></i>
        </a>
        <div class="collapse navbar-collapse" id="navbarToggler">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active fw-semibold" aria-current="page" href="admin_home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="mobil/mobil.php">Mobil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="sewa/sewa.php">Sewa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="pembayaran/pembayaran.php">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="account_managment/account_managment.php">Accounts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active opacity-75" href="diskon/diskon.php">Diskon</a>
            </li>
          </ul>
          <div class="dropdown">
            <a class="dropdown-toggle link-light text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person"></i> <?= $account["name"]; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg-end shadow">
              <li><a class="dropdown-item opacity-75" href="admin_account/admin_account_profile.php">Profiles</a></li>
              <li><a class="dropdown-item opacity-75" href="admin_account/admin_account_setting.php">Settings</a></li>
              <li><a class="dropdown-item opacity-75" href="../auth/logout.php">Logout</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item opacity-75" href="../user/user_home.php">Switch to User</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="container">
    <div class="container-xxl bd-masthead mb-3">
      <div class="col-md-8 mx-auto text-center">
        <img src="../assets/img/logo.png" width="200px" alt="roam" class="d-block mx-auto mb-4">
        <p>Halo <b><?php echo $account['name']; ?></b>, Anda telah login sebagai <b><?php echo $_SESSION['level']; ?></b>.</p>
      </div>
    </div>
  </main>

  <footer class="footer mt-auto pt-4">
    <div class="text-center py-2 bg-light">
      <span class="mb-0 text-muted">Made with &#x2764;&#xFE0F; by Diky</span>
    </div>
  </footer>

  <script src="../assets/js/nav_toggler_icon.js"></script>
</body>

</html>