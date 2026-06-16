const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessage = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = fileUploadWrapper ? fileUploadWrapper.querySelector("#file-cancel") : null;
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");

const userData = {
  message: null,
  file: {
    data: null,
    mime_type: null,
  },
};

const chatHistory = [];
const initialInputHeight = messageInput ? messageInput.scrollHeight : 0;

const createMessageElement = (content, ...classes) => {
  const div = document.createElement("div");
  div.classList.add("message", ...classes);
  div.innerHTML = content;
  return div;
};

async function sendMessageToBackend(message, file = null, history = []) {
  try {
    const response = await fetch("gemini-chat.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        message: message,
        file: file,
        history: history,
      }),
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
      throw new Error(data.error || "Something went wrong.");
    }

    return data.reply;
  } catch (error) {
    console.error("Chatbot error:", error);
    return "Sorry, I had trouble responding. Please try again or contact us directly.";
  }
}

const generateBotResponse = async (incomingMessageDiv) => {
  const messageElement = incomingMessageDiv.querySelector(".message-text");

  const currentUserMessage = userData.message;
  const currentFile = userData.file && userData.file.data ? userData.file : null;

  chatHistory.push({
    role: "user",
    parts: [{ text: currentUserMessage }],
  });

  try {
    const apiResponseText = await sendMessageToBackend(
      currentUserMessage,
      currentFile,
      chatHistory
    );

    messageElement.innerText = apiResponseText;

    chatHistory.push({
      role: "model",
      parts: [{ text: apiResponseText }],
    });
  } catch (error) {
    console.error(error);
    messageElement.innerText = "Sorry, something went wrong.";
    messageElement.style.color = "#ff0000";
  } finally {
    userData.file = {
      data: null,
      mime_type: null,
    };

    incomingMessageDiv.classList.remove("thinking");
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
  }
};

const handleOutgoingMessage = (e) => {
  e.preventDefault();

  userData.message = messageInput.value.trim();

  if (!userData.message && !userData.file.data) {
    return;
  }

  messageInput.value = "";
  messageInput.dispatchEvent(new Event("input"));

  if (fileUploadWrapper) {
    fileUploadWrapper.classList.remove("file-uploaded");
  }

  const messageContent = `
    <div class="message-text"></div>
    ${
      userData.file.data
        ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" alt="Uploaded file" />`
        : ""
    }
  `;

  const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
  outgoingMessageDiv.querySelector(".message-text").innerText = userData.message;

  chatBody.appendChild(outgoingMessageDiv);
  chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

  setTimeout(() => {
    const botMessageContent = `
      <svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
        <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"/>
      </svg>
      <div class="message-text">
        <div class="thinking-indicator">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
        </div>
      </div>
    `;

    const incomingMessageDiv = createMessageElement(
      botMessageContent,
      "bot-message",
      "thinking"
    );

    chatBody.appendChild(incomingMessageDiv);
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

    generateBotResponse(incomingMessageDiv);
  }, 600);
};

if (messageInput) {
  messageInput.addEventListener("input", () => {
    messageInput.style.height = `${initialInputHeight}px`;
    messageInput.style.height = `${messageInput.scrollHeight}px`;

    const chatForm = document.querySelector(".chat-form");

    if (chatForm) {
      chatForm.style.borderRadius =
        messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
    }
  });

  messageInput.addEventListener("keydown", (e) => {
    const userMessage = e.target.value.trim();

    if (e.key === "Enter" && !e.shiftKey && userMessage && window.innerWidth > 768) {
      handleOutgoingMessage(e);
    }
  });
}

if (fileInput) {
  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];

    if (!file) return;

    const allowedTypes = ["image/png", "image/jpeg", "image/webp", "image/gif"];

    if (!allowedTypes.includes(file.type)) {
      alert("Please upload a valid image file.");
      fileInput.value = "";
      return;
    }

    const maxSize = 5 * 1024 * 1024;

    if (file.size > maxSize) {
      alert("File is too large. Please upload an image under 5MB.");
      fileInput.value = "";
      return;
    }

    const reader = new FileReader();

    reader.onload = (e) => {
      fileInput.value = "";

      if (fileUploadWrapper) {
        const previewImage = fileUploadWrapper.querySelector("img");

        if (previewImage) {
          previewImage.src = e.target.result;
        }

        fileUploadWrapper.classList.add("file-uploaded");
      }

      const base64String = e.target.result.split(",")[1];

      userData.file = {
        data: base64String,
        mime_type: file.type,
      };
    };

    reader.readAsDataURL(file);
  });
}

if (fileCancelButton) {
  fileCancelButton.addEventListener("click", () => {
    userData.file = {
      data: null,
      mime_type: null,
    };

    if (fileUploadWrapper) {
      fileUploadWrapper.classList.remove("file-uploaded");
    }
  });
}

if (typeof EmojiMart !== "undefined" && messageInput) {
  const picker = new EmojiMart.Picker({
    theme: "light",
    skinTonePosition: "none",
    previewPosition: "none",
    onEmojiSelect: (emoji) => {
      const { selectionStart: start, selectionEnd: end } = messageInput;
      messageInput.setRangeText(emoji.native, start, end, "end");
      messageInput.focus();
    },
    onClickOutside: (e) => {
      if (e.target.id === "emoji-picker") {
        document.body.classList.toggle("show-emoji-picker");
      } else {
        document.body.classList.remove("show-emoji-picker");
      }
    },
  });

  const chatForm = document.querySelector(".chat-form");

  if (chatForm) {
    chatForm.appendChild(picker);
  }
}

if (sendMessage) {
  sendMessage.addEventListener("click", (e) => handleOutgoingMessage(e));
}

const fileUploadButton = document.querySelector("#file-upload");

if (fileUploadButton && fileInput) {
  fileUploadButton.addEventListener("click", () => fileInput.click());
}

if (chatbotToggler) {
  chatbotToggler.addEventListener("click", () => {
    document.body.classList.toggle("show-chatbot");
  });
}

if (closeChatbot) {
  closeChatbot.addEventListener("click", () => {
    document.body.classList.remove("show-chatbot");
  });
}