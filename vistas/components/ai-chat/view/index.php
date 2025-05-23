<?php



if ($_SESSION["permisos"]["ai-chat"] == "x") {
?>

<style>
    .chat-section {
    min-height: 84vh;/* Ajusta 80px al alto de tu footer */
    display: flex;
    flex-direction: column;
}

@media (min-height: 1100px) {
    .chat-section {
        min-height: 80dvh;
        height: calc(100vh + 80px);
    }
}

</style>

<section class="chat-section">  
    <div id="chatAi">
        <header class="chat__header">
            <img src="./vistas/img/intermediario/SEGUROS GRUPO ASISTENCIA SAS/LogoGA.png" alt="Icono" class="header__img">
            <h1 class="header__title">Chat Assistent AI</h1>
        </header>

        <section class="chat">
            <link rel="stylesheet" href="./vistas/components/ai-chat/assets/css/styles.css">
            <script src="./vistas/components/ai-chat/js/main.js" defer></script>
            <div class="chat__messages">
                <!-- Mensajes -->
            </div>

            <div class="chat__input-group">
                <div class="chat__inputs">
                    <input type="text" id="messageInput" class="chat__input" placeholder="Escribe un mensaje...">
                    <button class="chat__button" id="sendButton">Enviar</button>
                </div>
            </div>
        </section>
    </div>
</section>
<?php
} else {
?>
    <div style="height: 100%; width: 100%; flex: 1; display: flex; visibility: hidden;"></div>
<?php
}
?>