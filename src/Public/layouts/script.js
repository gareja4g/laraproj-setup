function ajaxScript(t) {
  var token = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    type: $(t).attr("method"),
    url: $(t).attr("url"),
    data: {
      data: $(t).data(),
      _token: token,
    },
    cache: false,
    beforeSend: function () {},
    success: function (data) {
      if (data.type !== undefined && data.type == "reload") {
        alert(data.message);
        location.reload();
      } else {
        $("#popup").html(data).modal("show");
      }
    },
    error: function () {},
    completed: function () {},
  });
}
