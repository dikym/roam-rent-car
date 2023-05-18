$(document).on("click", ".btn-edit", function () {
  const id = $(this).data("id");
  $.ajax({
    url: "get_data.php",
    type: "POST",
    data: {
      id: id,
    },
    dataType: "json",
    success: function (response) {
      $("#id").val(response.id);
      $("#name").val(response.name);
      $("#username").val(response.username);
      $("#password-input").val(response.password);
      $("#level_account").val(response.level);
    },
  });
});
