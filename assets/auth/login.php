<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Roam</title>
  <link rel="shortcut icon" href="../assets/img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>

<body style="background-color: #ededed;">
  <header>
    <?php
    if (isset($_GET['message'])) {
      if ($_GET['message'] == "unvalid") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
              Wrong username or password!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='login.php'\"></button>
            </div>";
      } else if ($_GET['message'] == "unrecognized") {
        echo "<div class='alert alert-danger alert-dismissible fade show text-center m-0' role='alert'>
              Please input username and password first!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='login.php'\"></button>
            </div>";
      } else if ($_GET['message'] == "register_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
              Register successfully!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='login.php'\"></button>
            </div>";
      } else if ($_GET['message'] == "delete_account_success") {
        echo "<div class='alert alert-success alert-dismissible fade show text-center m-0' role='alert'>
              Account deleted successfully!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick=\"location.href='login.php'\"></button>
            </div>";
      }
    }
    ?>
  </header>
  <main>
    <div class="container">
      <div class="row justify-content-sm-center h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
          <div class="text-center my-5">
            <img src="../assets/img/logo.png" alt="Roam Logo" width="150px">
          </div>
          <div class="card shadow-lg">
            <div class="card-body p-5">
              <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
              <form action="check_level.php" method="post" autocomplete="off">
                <div class="mb-3">
                  <label for="username" class="mb-1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" autofocus>
                </div>

                <div class="mb-4">
                  <label for="password-input" class="mb-1">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password-input" name="password">
                    <span class="input-group-text" id="show-password" onclick="togglePassword('password-input', 'show-password')"><i class="bi bi-eye"></i></span>
                  </div>
                </div>

                <div class="d-flex align-items-center">
                  <button type="submit" class="btn ms-auto text-white" style="background-color: #645CBB;">
                    Login
                  </button>
                </div>
              </form>
            </div>
            <div class="py-3 border-0" style="background-color: #BFACE2;">
              <div class="text-center">
                <span>Don't have an account? <a href="register.php" class="text-dark">Create One</a></span>
              </div>
            </div>
          </div>
          <div class="text-center my-5 text-muted">
            <span>Made with &#x2764;&#xFE0F; by Diky</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/toggle_password.js"></script>
</body>

</html>