<?php
// session_start();

// if (isset($_SESSION["popUpLogIn"]) && $_SESSION["popUpLogIn"] == "logIn") {
//     $_SESSION["popUpLogIn"] = "inSession";
//     echo json_encode(["popUpLogIn" => "logIn"]);
// } else if ($_SESSION["popUpLogIn"] && $_SESSION["popUpLogIn"] == "inSession") {
//     echo json_encode(["popUpLogIn" => $_SESSION["popUpLogIn"] ?? ""]);
// }

// if(isset($_POST['heartbeat']) && $_POST['heartbeat'] == 'true') {
//     echo json_encode(['heartbeat' => 'true']);
//     return;
// }else{
//     echo json_encode(['heartbeat' => 'false']);
//     return;
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//      if (isset($_POST['heartbeat']) && $_POST['heartbeat'] === "true") {
//         // Actualizar la base de datos para mantener el estado `in_session`
//         if (isset($_SESSION['iniciarSesion'])) {
//             echo json_encode(['heartbeat' => 'success']);
//             exit;
//         } else {
//             echo json_encode(['heartbeat' => 'error']);
//             exit;
//         }
//     }
// }

// session_start();

// // Si la sesión no existe, responde que ha expirado
// if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
//     echo "expired";
//     exit();
// }

// // Si la sesión sigue activa, responde con "active"
// echo "active";
// ?>

<?php
session_start();

// Configura un límite de inactividad (por ejemplo, 60 segundos)
$inactivityLimit = 3600;

// Si la sesión está inactiva por más del límite, destruye la sesión
if (isset($_SESSION['lastActivity'])) {
    var_dump($_SESSION['lastActivity']);
    var_dump(time());
    $timeInactive = time() - $_SESSION['lastActivity'];
    var_dump($timeInactive);
    if ($timeInactive > $inactivityLimit) {
        session_unset();
        session_destroy();
        echo "expired"; // Informa al frontend que la sesión ha expirado
        exit();
    }
}

// Verifica si la solicitud es AJAX o una interacción directa
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // Si es una solicitud AJAX, no actualizar `lastActivity`
    echo "active";
    exit();
}

// Actualiza `lastActivity` para solicitudes normales (no AJAX)
$_SESSION['lastActivity'] = time();
echo "active";
?>
