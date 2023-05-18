function togglePassword(inputId, iconId) {
  const passwordInput = document.getElementById(inputId);
  const showPassword = document.getElementById(iconId);
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    showPassword.innerHTML = "<i class='bi bi-eye-slash'></i>";
  } else {
    passwordInput.type = "password";
    showPassword.innerHTML = "<i class='bi bi-eye'></i>";
  }
}
