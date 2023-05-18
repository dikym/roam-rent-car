const autofill = (leaseId, total, leaseLength) => {
  $(document).on("input", `#${leaseId}`, function () {
    const lease_id = $(this).val();
    $.ajax({
      url: "get_total.php",
      type: "POST",
      data: {
        lease_id: lease_id,
      },
      dataType: "json",
      success: function (response) {
        $(`#${total[0]}`).val(response.total_pembayaran);
        $(`#${total[1]}`).val(response.total_pembayaran);
        $(`#${leaseLength}`).val(response.lama_sewa);
      },
    });
  });
};
