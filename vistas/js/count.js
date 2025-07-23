// const getTime = dateTo => {
//     let now = new Date(),
//         time = (new Date(dateTo) - now + 1000) / 1000,
//         seconds = ('0' + Math.floor(time % 60)).slice(-2),
//         minutes = ('0' + Math.floor(time / 60 % 60)).slice(-2),
//         hours = ('0' + Math.floor(time / 3600 % 24)).slice(-2),
//         days = Math.floor(time / (3600 * 24));

//     return {
//         seconds,
//         minutes,
//         hours,
//         days,
//         time
//     }
// };

const getTime = dateTo => {
    const target = new Date(dateTo);
    if (isNaN(target.getTime())) {
        return { time: 0, seconds: '00', minutes: '00', hours: '00', days: 0 };
    }
    let now = new Date(),
        timeDiff = (target - now + 1000) / 1000,
        seconds = ('0' + Math.floor(timeDiff % 60)).slice(-2),
        minutes = ('0' + Math.floor(timeDiff / 60 % 60)).slice(-2),
        hours = ('0' + Math.floor(timeDiff / 3600 % 24)).slice(-2),
        days = Math.floor(timeDiff / (3600 * 24));

    return { seconds, minutes, hours, days, time: timeDiff };
};

let cont = 0
const countdown = (dateTo, element, rol) => {
    if(rol == "20" || rol == "19" || rol == "2"){
        const item = document.getElementById(element);
        if (rol == "19" && item) {
            item.style.display = "none";
        }
        const timerUpdate = setInterval( () => {
            let currenTime = getTime(dateTo);          
            if(currenTime.hours != 'NaN'){
                if (currenTime.time <= 0) {
                    clearInterval(timerUpdate);
                    Swal.fire({
                        icon: 'error',
                        title: '!Tu tiempo de uso se agoto!',
                        confirmButtonText: 'Ok',
                    }).then(() => {
                        window.location = "salir";
                    });
                    setTimeout(function(){
                        window.location = "salir";
                    }, 10000);
                }else{
                    if(item && (rol == "20" || rol == "2")){
                        item.innerHTML =  currenTime.days + "D " + currenTime.hours +  'H ' + currenTime.minutes + 'M ' + currenTime.seconds + 'S';
                        item.style.display = "block";
                    }
                }
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
        // Valid que la fecha esta bien antes de iniciar el countdown el cual es le que arroja el mensaje
        if (fecha && !isNaN(new Date(fecha).getTime()) && (rolId == "20" || rolId == "19" || rolId == "2")) {
            countdown(fecha, 'cuentatras', rolId);
        }
    }
}).catch(function(error) {
    console.error("Error fetching user role:", error);
});

async function mostrarCotRestantes() {
    let fecha1 = new Date();

    const year = fecha1.getFullYear();
    const month = String(fecha1.getMonth() + 1).padStart(2, '0');
    const startDay = '01';
    const nowDate = `${year}-${month}-${startDay}`;

    let endOfMonthDate = new Date(fecha1.getFullYear(), fecha1.getMonth() + 1, 0);
    const yearEndOfMonth = endOfMonthDate.getFullYear();
    const monthEndOfMonth = String(endOfMonthDate.getMonth() + 1).padStart(2, '0');
    const dayEndOfMonth = String(endOfMonthDate.getDate()).padStart(2, '0');
    const lastDateOfCurrentMonth = `${yearEndOfMonth}-${monthEndOfMonth}-${dayEndOfMonth}`;

    // AJAX como una promesa para el manejo del then y el chatch 
    const response = await new Promise((resolve) => {
        $.ajax({
            url: "ajax/compararFecha.php",
            method: "POST",
            data: {
                fechaInicio: nowDate,
                fechaFin: lastDateOfCurrentMonth
            },
            success: function (respuesta) {
                let $p = document.getElementById("cotRestantes1");
                if ($p) {
                    $p.innerHTML = respuesta;
                }
                resolve(respuesta);
            },
            error: function () {
                // Muesta una notificación de error
                Swal.fire({
                    icon: 'error',
                    title: 'Problema de conexión',
                    text: 'No se pudo obtener la cantidad de cotizaciones. Revisa tu conexión o sesión.',
                    confirmButtonColor: '#3085d6'
                });

                // Mostrar 0 en el contador
                let $p = document.getElementById("cotRestantes1");
                if ($p) {
                    $p.innerHTML = 0;
                }

                resolve(0);
            }
        });
    });

    return response;
}

mostrarCotRestantes();
