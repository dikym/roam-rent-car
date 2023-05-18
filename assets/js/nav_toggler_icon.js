const navbarToggler = document.getElementById("");
const navbarTogglerIcon = document.getElementById("");

$(document).on("click", "#navbar-toggler", function () {
  $("#navbar-toggler-icon").toggleClass("bi bi-list");
  $("#navbar-toggler-icon").toggleClass("bi bi-x");
});
