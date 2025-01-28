// document.addEventListener('DOMContentLoaded',  function() {

//     if(localStorage.getItem("lastUserLogged") === null || localStorage.getItem("lastUserLogged") === "") {
//         localStorage.setItem("lastUserLogged", permisos.usu_documento);
//     }

//     fetch("controladores/session.php" ,{
//         method: "POST",
//         body: JSON.stringify({ heartbeat: true }),
//         headers: { "Content-Type": "application/json" }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.popUpLogIn === "logIn") {
//             if (permisos.id_Intermediario === "3" ) {
//                 console.log("Sesión activa: popUpLogIn es 'ok'");
//                 const formData = new FormData();
//                 formData.append("popUpLogIn", "inSession");
//                 fetch("controladores/session.php", { 
//                     method: "POST", 
//                     body: formData 
//                 }).then(response => response.json()).then(data => {
//                     console.log(data);
//                 }).catch(error => { 
//                     console.error("Error al actualizar la sesión:", error);
//                     console.log(error);
//                 });
//                    swal.fire({
//                        html: `
//                            <div style='display: flex; align-items: center; justify-content: center;'>
//                             <img id="modalHome" src='vistas/img/modals/img/home/homeModal.png'/>
//                            </div>
//                        `,
//                        showConfirmButton: true,
//                        confirmButtonText: 'Continuar',
//                        customClass: {
//                            popup: "popup_control",
//                            confirmButton: 'popup-confirm-button24',
//                        },
//                        timer: 20000,
//                        timerProgressBar: true,
//                    })
               
//            } else {
//                return;
//            }
       
//         }else{
//             console.log("Sesión activa: 'ok'");
//             return;
       
//         }
//     })
//     .catch(error => {
//         return 
//     });
// });