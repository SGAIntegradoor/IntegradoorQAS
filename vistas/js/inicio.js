document.addEventListener('DOMContentLoaded', function() {
    let modalShown = localStorage.getItem('initModal');

    // Solo mostrar el modal si no se ha mostrado antes
    if ((!modalShown || modalShown === 'false') && permisos.id_Intermediario === "3" ) {
        swal.fire({
            html: `
                <div style='display: flex; align-items: center; justify-content: center;'>
                 <img id="modalHome" src='vistas/img/modals/img/home/modalHome.jpg'/>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Continuar',
            customClass: {
                popup: "popup_control",
                confirmButton: 'popup-confirm-button24',
            },
            timer: 20000,
            timerProgressBar: true,
        }).then(() => {
            // Después de cerrar el modal, establecer initModal en true para no mostrarlo de nuevo
            localStorage.setItem('initModal', true);
        });
    }
});