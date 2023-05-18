<?php
require "../functions/functions.php";

if (isset($_POST["submit"])) {
  register($_POST);

  header("location: login.php?message=register_success");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - Roam</title>
  <link rel="shortcut icon" href="../assets/img/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>

<body style="background-color: #ededed;">
  <main>
    <div class="container">
      <div class="row justify-content-sm-center h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
          <div class="text-center my-5">
            <img src="../assets/img/logo.png" alt="Roam Logo" width="150px">
          </div>
          <div class="card shadow-lg">
            <div class="card-body p-5">
              <h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
              <form method="post" class="needs-validation" autocomplete="off" novalidate>
                <div class="mb-3">
                  <label for="name" class="mb-1">Name</label>
                  <input type="text" class="form-control" id="name" name="name" autofocus required>
                  <div class="invalid-feedback">
                    Please input your name.
                  </div>
                </div>

                <div class="mb-3">
                  <label for="username" class="mb-1">Username</label>
                  <input type="text" class="form-control" id="username" name="username" required>
                  <div class="invalid-feedback">
                    Please choose a username.
                  </div>
                </div>

                <div class="mb-3">
                  <label for="password-input" class="mb-1">Password</label>
                  <div class="input-group has-validation">
                    <input type="password" class="form-control" id="password-input" name="password" required>
                    <span class="input-group-text" id="show-password" onclick="togglePassword('password-input', 'show-password')"><i class="bi bi-eye"></i></span>
                    <div class="invalid-feedback">
                      Please choose a password.
                    </div>
                  </div>
                </div>

                <div class="mb-4">
                  <input class="form-check-input" type="checkbox" id="agreement" required>
                  <label class="form-check-label" for="agreement">
                    &nbsp;Agree to terms and conditions
                  </label>
                  <div class="invalid-feedback">
                    You must agree before submitting.
                  </div>
                </div>

                <div class="align-items-center d-flex">
                  <button type="submit" class="btn ms-auto text-white" style="background-color: #645CBB;" name="submit">
                    Register
                  </button>
                </div>
              </form>
            </div>
            <div class="py-3 border-0" style="background-color: #BFACE2;">
              <div class="text-center">
                <span>Already have an account? <a href="login.php" class="text-dark">Login</a></span>
              </div>
            </div>
          </div>
          <div class="text-center my-5 text-dark">
            <span>Made with &#x2764;&#xFE0F; by Diky</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/validation.js"></script>
  <script src="../assets/js/toggle_password.js"></script>
</body>

</html>