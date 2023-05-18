$(document).on("click", ".btn-edit", function() {
  const lease_id = $(this).data("id");
  $.ajax({
    url: "get_data.php",
    type: "POST",
    data: {
      lease_id: lease_id,
    },
    dataType: "json",
    success: function(response) {
      $("#lease_id").val(response.id_sewa);
      $("#user_id").val(response.id_user);
      $("#past_car_plate").val(response.plat_mobil);
      $("#car_plate_select").append(`<option selected value="${response.plat_mobil}" id="append_option">${response.nama_mobil}</option>`);
      $("#start_date").val(response.mulai);
      $("#finish_date").val(response.selesai);
    },
  });
});

$(".close_btn").click(function() {
  setTimeout(() => {
    $("#append_option").remove();
  }, 300);
});