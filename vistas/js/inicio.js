document.addEventListener("DOMContentLoaded", function () {
  const navCRM = () => {
    fetch("http://localhost/integradoorQAS/API/login/SSO/", {
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
            "http://localhost:5173/login?token=" + data.token;
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