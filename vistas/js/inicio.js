document.addEventListener('DOMContentLoaded', function() {
    let modalShown = localStorage.getItem('initModal');

    // Solo mostrar el modal si no se ha mostrado antes
    if (!modalShown || modalShown === 'false') {
        swal.fire({
            html: `
                <div style='display: flex; align-items: center'>
                <img src='vistas/img/modals/img/home/modalHome.jpg' />
                </div>
                <p style='text-align: justify; font-family: Helvetica, Arial, sans-serif;' id='pTableModalPesados'>
                <strong>Nota:</strong> Tener en cuenta que aunque el cotizador genere ofertas, no todos los vehículos son asegurables. Se podrán hacer excepciones de valor asegurado superior cuando el asesor sea productivo, tenga más de 6 meses de antigüedad con Grupo Asistencia, no tenga altos índices de siniestralidad en su cartera, y si el cliente tiene vinculación con otros productos de la aseguradora. El valor de las primas de las cotizaciones puede variar al momento de emitir en los casos autorizados de manera excepcional.
                </p>
            `,
            width: '30%',
            showConfirmButton: true,
            confirmButtonText: 'Continuar',
            customClass: {
                popup: 'custom-swal-alertaMontoPesados',
                title: 'custom-swal-titlePesados',
                confirmButton: 'custom-swal-confirm-button24',
                actions: 'custom-swal-actions-pesados',
                icon: 'swal2-icon_monto',
            },
            timer: 20000,
            timerProgressBar: true,
        }).then(() => {
            // Después de cerrar el modal, establecer initModal en true para no mostrarlo de nuevo
            localStorage.setItem('initModal', true);
        });
    }
});