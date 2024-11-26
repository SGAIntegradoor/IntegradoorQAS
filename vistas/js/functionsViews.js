let contier = 0;

$("#dianacimiento, #mesnacimiento, #anionacimiento").on("change", function () {
  if ($("#tipoDocumentoID").val() == "2") {
    if (this.id == "dianacimiento") {
      if ($(this).val() == "") {
        contier++;
      }
      $(this).prop("required", true);
      $("#mesnacimiento").prop("required", true);
      $("#anionacimiento").prop("required", true);
    } else if (this.id == "mesnacimiento") {
      if ($(this).val() == "") {
        contier++;
      } else {
        $(this).prop("required", true);
        $("#dianacimiento").prop("required", true);
        $("#anionacimiento").prop("required", true);
      }
    } else {
      if ($(this).val() == "") {
        contier++;
      } else {
        $(this).prop("required", true);
        $("#dianacimiento").prop("required", true);
        $("#mesnacimiento").prop("required", true);
      }
    }

    if (contier == 3) {
      $("#dianacimiento").prop("required", false);
      $("#mesnacimiento").prop("required", false);
      $("#anionacimiento").prop("required", false);
    }
  }
});
