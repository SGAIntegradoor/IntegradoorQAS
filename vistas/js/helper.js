export default function decresCotTotales() {
  return new Promise(function(resolve, reject) {
    $.ajax({
        type: "POST",
        url: "src/updateCotizacionesTotales.php",
        dataType: "json",
        success: function (data){
            resolve(data);
        },
        error: function (xhr, status, error){
            reject(error);
        }
    });
});
}