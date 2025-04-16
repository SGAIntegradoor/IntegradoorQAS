const sendButton = document.querySelector("#sendButton");
const messagesContainer = document.querySelector(".chat__messages");

sendButton.addEventListener("click", async () => {
  // sentences to translate
  let text = document.querySelector("#messageInput");

  let message = text.value.trim();

  // selecting lenguage targe

  if (!message) return;

  // let lenguage = document.querySelector("#targetLang").value;

  // add message from user on the chat box

  // document.querySelector('.chat__messages').innerHTML += `<p class="chat_message--user">${message}</p>`;

  const userMessage = document.createElement("div");
  userMessage.className = "chat__message chat__message--user";
  userMessage.innerHTML = message;

  const messagesContainer = document.querySelector(".chat__messages");
  messagesContainer.appendChild(userMessage);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;

  try {
    // request to back
    // const response = await fetch("/api/chat", {
    //   method: "POST",
    //   headers: {
    //     "Content-Type": "application/json",
    //   },
    //   body: JSON.stringify({ message, targetLang: lenguage }),
    // });

    // const data = await response.json();

    const response = await fetch("https://10djhr3as6.execute-api.us-east-1.amazonaws.com/Stage/agent", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjg1NjAsImlkIjo4NTYwLCJ1c2VyX2lkIjoiODU2MCIsImlhdCI6MTczMzc1ODQ4MywiZXhwIjoxNzMzODQ0ODgzfQ.dDpt_HrdHYY9VXPUsx2wP6odLSpqF2YJ-fZIMjBo8HI"
      },
      body: JSON.stringify({ session_id: 12345678 , input_text: message }),
    });

    const data = await response.json();

    console.log(data)

    // add response mesg to chat box
    const botMessage = document.createElement("p");
    botMessage.className = "chat__message chat__message--bot";
    botMessage.innerHTML = data.response.replace(/\n/g, "<br>");

    messagesContainer.appendChild(botMessage);
    messagesContainer.scrollTop = messagesContainer.scrollHeight


  } catch (error) {
    // console.error("Error en la solicitud a OpenAI:", error);
    messagesContainer.appendChild("No puedo responderte eso.");
    messagesContainer.scrollTop = messagesContainer.scrollHeight
  }

//   clear input message
text.value = "";
});



// sendButton.addEventListener("click", () => {
//     let text = document.querySelector("#messageInput");
//     let message = text.value.trim();

//     if (!message) return;

//     let userMessage = document.createElement("div");
//     userMessage.className = "chat__message chat__message--user";
//     userMessage.innerHTML = message;

//     messagesContainer.appendChild(userMessage);

//     // Mostrar el scroll cuando llegue un nuevo mensaje
//     messagesContainer.style.overflowY = "auto";

//     // Hacer scroll al final
//     messagesContainer.scrollTop = messagesContainer.scrollHeight;

//     /

//     text.value = "";
// });