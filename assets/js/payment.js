$(document).on("click", ".btn-edit", function () {
  const ids = $(this).data("ids").split(",");
  const payment_id = ids[0];
  const lease_id = ids[1];
  $.ajax({
    url: "get_data.php",
    type: "POST",
    data: {
      payment_id: payment_id,
      lease_id: lease_id,
    },
    dataType: "json",
    success: function (response) {
      $("#payment_id").val(response.id_pembayaran);
      $("#leaseIdEdit").val(response.id_sewa);
      $("#user_cash").val(response.uang_user);
      $("#discount").val(response.diskon);
      $("#totalEdit").val(response.total);
      $("#leaseLengthEdit").val(response.lama_sewa);
    },
  });
});
