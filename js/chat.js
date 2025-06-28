//
//
//
import { deleteChat, getChat, sendNewMessage } from "./api/chat_bot.js";

const suggestedMessages = [
  "ساعدني في ترتيب مهامي",
  "اعطني نصائح لتنظيم وقتي"
];


const suggestionsBox = document.getElementById("suggestions");
const chatBox = document.getElementById("chat-box");
const messageInput = document.getElementById("message-input");
const sendBtn = document.getElementById("send-btn");
const clearBtn = document.getElementById("clear-chat-btn");

function loadSuggestions() {
  suggestionsBox.innerHTML = '';
  suggestedMessages.forEach(text => {
    const btn = document.createElement("button");
    btn.textContent = text;
    btn.onclick = () => {
      messageInput.value = text;
      sendMessage();
    };
    suggestionsBox.appendChild(btn);
  });
}

function appendMessage(text, sender = "user") {
  const msg = document.createElement("div");
  msg.className = `message ${sender}`;
  msg.textContent = text;
  chatBox.appendChild(msg);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function sendMessage() {
  const text = messageInput.value.trim();
  if (!text) return;
  appendMessage(text, "user");
  messageInput.value = "";

  
  const waitingMsg = document.createElement("div");
  waitingMsg.className = "message bot";
  waitingMsg.textContent = "انتظر قليلاً...";
  chatBox.appendChild(waitingMsg);
  chatBox.scrollTop = chatBox.scrollHeight;

  sendToBackend(text)
    .then(response => {
      chatBox.removeChild(waitingMsg); 
      appendMessage(response, "bot");  
    })
    .catch(() => {
      chatBox.removeChild(waitingMsg); 
      appendMessage("حدث خطأ في الاتصال", "bot");
    });
}

async function loadChatFromBackend() {

  const user_id = localStorage.getItem('user_id');
  if (!user_id) return;

  try {
    const result = await getChat();

    console.log(result);

    var flag = true;
    result.data.messages.forEach(msg => {
      if (flag) {
        flag = false;
        appendMessage(msg.content, "user");
      }
      else {
        flag = true;
        appendMessage(msg.content, "bot");
      }
    })
  }
  catch (err) {
    appendMessage("فشل تحميل المحادثة", "bot");
  }
}

async function sendToBackend(message) {

  const data = {
    'content': message
  };

  const result = await sendNewMessage(data);

  console.log(result);

  return result.data.content;
}

async function clearChat() {
  try {
    const result = await deleteChat();
    appendMessage("تم مسح المحادثة", "bot");
    chatBox.innerHTML = "";
  }
  catch (err) {
    console.error(err);
    appendMessage("فشل حذف المحادثة", "bot");
  }
}

sendBtn.onclick = sendMessage;
clearBtn.onclick = clearChat;

loadSuggestions();
loadChatFromBackend();