<?php



if ($_SESSION["permisos"]["ai-chat"] == "x") {
?>
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
                <div style="display: flex;  width: 50px ; margin: 0; margin-top: 244px; background-color: #e0e0e0; border-top-right-radius: 10px; border-top-left-radius: 10px; border-bottom-right-radius: 10px; ">
                    <div class="typing-loader" style="margin: 0;">
                        <svg width="50" height="20" viewBox="0 0 50 20" xmlns="http://www.w3.org/2000/svg">
                            <circle class="dot dot1" cx="10" cy="10" r="5" />
                            <circle class="dot dot2" cx="25" cy="10" r="5" />
                            <circle class="dot dot3" cx="40" cy="10" r="5" />
                        </svg>
                    </div>

                </div>
            </div>

            <div class="chat__input-group">
                <div class="chat__inputs">
                    <input type="text" id="messageInput" class="chat__input" placeholder="Escribe un mensaje...">
                    <button class="chat__button" id="sendButton">Enviar</button>
                </div>
            </div>
        </section>
    </div>
<?php
}
?>