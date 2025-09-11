document.addEventListener("DOMContentLoaded", function () {
  const navCRM = () => {
    fetch("https://grupoasistencia.com/Auth/Login/SSO/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        usu_usuario: permisos.usu_usuario,
        usu_password: permisos.usu_password,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "Ok" && data.token) {
          // Redirigir al CRM con el token en la URL
          console.log("entre aca")
          window.location.href =
            "http://integradoor.com/crm/login?token=" + data.token;
        } else {
          alert("Error al iniciar sesión: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  // ejemplo: enlazar al botón del menú
  document.getElementById("btnCRM").addEventListener("click", navCRM);
});