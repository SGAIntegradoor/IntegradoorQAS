const getTime = dateTo => {
    let now = new Date(),
        time = (new Date(dateTo) - now + 1000) / 1000,
        seconds = ('0' + Math.floor(time % 60)).slice(-2),
        minutes = ('0' + Math.floor(time / 60 % 60)).slice(-2),
        hours = ('0' + Math.floor(time / 3600 % 24)).slice(-2),
        days = Math.floor(time / (3600 * 24));

    return {
        seconds,
        minutes,
        hours,
        days,
        time
    }
};
let cont = 0
const countdown = (dateTo, element, rol) => {
    if(rol == "20" || rol == "19" || rol == "2"){
        const item = document.getElementById(element);
        const timerUpdate = setInterval( () => {
            let currenTime = getTime(dateTo);          
            if(currenTime.hours != 'NaN'){
                if (currenTime.hours <= 1) {
                    clearInterval(timerUpdate);
                    Swal.fire({
                        icon: 'error',
                        title: '!Tu tiempo de uso se agoto!',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "salir";
                        } else if (result.isDenied) {
                        }
                    })
                    setTimeout(function(){
                        window.location = "salir";
                    }, 10000);
                }else{
                    if(item){
                        item.innerHTML =  currenTime.days + "D " + currenTime.hours +  'H ' + currenTime.minutes + 'M ' + currenTime.seconds + 'S';
                    }
                }
            }else{
            }
        }, 1000);
    }
};

let fecha = $("#fechaLimi").val();
let rolId;
function getRolUser() {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "ajax/getRolUser.php",
            method: "POST",
            success: function (respuesta) {
                const parsedResponse = JSON.parse(respuesta);                
                resolve(parsedResponse);
            },
            error: function (xhr, status, error) {
                reject(error);
            }
        });
    });
}

getRolUser().then(function(respuesta) {
    if (respuesta.error) {
        console.error(respuesta.error);
    } else {
        rolId = respuesta.rol;
        countdown(fecha, 'cuentatras', rolId);
    }
}).catch(function(error) {
    console.error("Error fetching user role:", error);
});

async function mostrarCotRestantes() {
    let fecha1 = new Date();

    // Obtener el último día del mes pasado
    const year = fecha1.getFullYear();
    const month = String(fecha1.getMonth() + 1).padStart(2, '0');
    const startDay = '01';
    const nowDate = `${year}-${month}-${startDay}`;

    // Obtener el último día del mes actual
    let endOfMonthDate = new Date(fecha1.getFullYear(), fecha1.getMonth() + 1, 0);
    const yearEndOfMonth = endOfMonthDate.getFullYear();
    const monthEndOfMonth = String(endOfMonthDate.getMonth() + 1).padStart(2, '0'); // +1 porque getMonth() devuelve 0-11
    const dayEndOfMonth = String(endOfMonthDate.getDate()).padStart(2, '0');
    const lastDateOfCurrentMonth = `${yearEndOfMonth}-${monthEndOfMonth}-${dayEndOfMonth}`;

    // Convertir la solicitud AJAX a una promesa
    const response = await new Promise((resolve, reject) => {
        $.ajax({
            url: "ajax/compararFecha.php",
            method: "POST",
            data: { fechaInicio: nowDate, fechaFin: lastDateOfCurrentMonth },
            success: function (respuesta) {
                let $p = document.getElementById("cotRestantes1");
                if ($p) {
                    $p.innerHTML = respuesta;
                }
                resolve(respuesta);
            },
            error: function (xhr, status, error) {
                reject(error);
            }
        });
    });

    return response;
}


mostrarCotRestantes();