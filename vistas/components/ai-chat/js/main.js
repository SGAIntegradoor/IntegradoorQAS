const sendButton = document.querySelector("#sendButton");
const messageInput = document.querySelector("#messageInput");
const messagesContainer = document.querySelector(".chat__messages");

// Función que contiene la lógica de enviar mensaje
async function sendMessage() {
  let text = messageInput;
  let message = text.value.trim();
  if (!message) return;

  const userMessage = document.createElement("div");
  userMessage.className = "chat__message chat__message--user";
  userMessage.innerHTML = message;

  messagesContainer.appendChild(userMessage);
  userMessage.scrollIntoView({ behavior: "smooth", block: "start" });

  doLoad();

  try {
    const response = fetch(
      "https://10djhr3as6.execute-api.us-east-1.amazonaws.com/Stage/agent",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization:
            "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjg1NjAsImlkIjo4NTYwLCJ1c2VyX2lkIjoiODU2MCIsImlhdCI6MTczMzc1ODQ4MywiZXhwIjoxNzMzODQ0ODgzfQ.dDpt_HrdHYY9VXPUsx2wP6odLSpqF2YJ-fZIMjBo8HI",
        },
        body: JSON.stringify({ session_id: 12345678, input_text: message }),
      }
    );

    response
      .then((res) => {
        if (!res.status === 200) {
          doResponse(res.statusText, 2);
          throw new Error("Error en la respuesta del servidor");
        }
        return res.json();
      })
      .then((data) => {
        doResponse(data.response, 1);
      })
      .catch((error) => {
        doResponse(error, 3);
      });
  } catch (error) {
    doResponse(error, 3);
  }

  text.value = "";
}

// Cuando hacen click en el botón
sendButton.addEventListener("click", sendMessage);

// Cuando presionan Enter en el input
messageInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault(); // Previene el salto de línea
    sendMessage();
  }
});


function doResponse(response, type) {
  const botMessage = document.createElement("p");
  botMessage.className = "chat__message chat__message--bot";
  switch (type) {
    case 1:
      botMessage.innerHTML = response.replace(/\n/g, "<br>");
      $("#loader").remove();
      break;
    case 2:
    case 3:
      botMessage.innerHTML = response;
      $("#loader").remove();
      break;
    default:
      break;
  }
  messagesContainer.appendChild(botMessage);
  botMessage.scrollIntoView({ behavior: "smooth", block: "start" });
}

function doLoad() {
  const loaderMessage = document.createElement("div");
  loaderMessage.id = "loader";

  Object.assign(loaderMessage.style, {
    display: "flex",
    width: "70px",
    margin: "0",
    marginTop: "44px",
    backgroundColor: "#e0e0e0",
    borderTopRightRadius: "10px",
    borderTopLeftRadius: "10px",
    borderBottomRightRadius: "10px",
  });

  loaderMessage.className = "chat__message chat__message--bot";
  loaderMessage.innerHTML = `
    <div class="typing-loader">
      <svg width="50" height="20" viewBox="0 0 50 20" xmlns="http://www.w3.org/2000/svg">
        <circle class="dot dot1" cx="10" cy="10" r="5" />
        <circle class="dot dot2" cx="25" cy="10" r="5" />
        <circle class="dot dot3" cx="40" cy="10" r="5" />
      </svg>
    </div>`;
  messagesContainer.appendChild(loaderMessage);
  loaderMessage.scrollIntoView({ behavior: "smooth", block: "start" });
}

