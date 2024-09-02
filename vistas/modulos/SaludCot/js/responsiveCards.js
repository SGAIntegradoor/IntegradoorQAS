document.addEventListener("DOMContentLoaded", function() {
    function ajustarAlturaTarjetas() {
        var filas = document.querySelectorAll('.row.card-container');

        filas.forEach(function(fila) {
            var tarjetas = fila.querySelectorAll('.card-exequias');

            var alturaMaxima = 0;

            tarjetas.forEach(function(tarjeta) {
                tarjeta.style.height = 'auto';
                var altura = tarjeta.offsetHeight;

                if (altura > alturaMaxima) {
                    alturaMaxima = altura;
                }
            });

            tarjetas.forEach(function(tarjeta) {
                tarjeta.style.height = alturaMaxima + 'px';

                if (tarjeta.classList.contains('special-card')) {
                    tarjeta.style.display = 'flex';
                    tarjeta.style.flexDirection = 'column';
                    tarjeta.style.alignItems = 'center';
                    tarjeta.style.justifyContent = 'center';
                }

            });
        });

    }
    ajustarAlturaTarjetas();
    window.addEventListener('resize', ajustarAlturaTarjetas);
});