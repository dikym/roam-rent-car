$(document).on("click", ".btn-edit", function () {
  const car_id = $(this).data("id");
  $.ajax({
    url: "get_data.php",
    type: "POST",
    data: {
      car_id: car_id,
    },
    dataType: "json",
    success: function (response) {
      $("#car_id").val(response.id_mobil);
      $("#past_car_plate").val(response.plat_mobil);
      $("#car_plate").val(response.plat_mobil);
      $("#car").val(response.nama_mobil);
      $("#rate_per_day").val(response.tarif_per_hari);
      $("#status").val(response.status);
    },
  });
});
