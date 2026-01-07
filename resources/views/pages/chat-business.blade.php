<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Demo</title>

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .chat-container {
            background: #fff;
            width: 400px;
            height: 600px;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .chat-header {
            background: #0066ff;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .chat-body {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }
        .message {
            margin: 8px 0;
            padding: 10px;
            border-radius: 8px;
            max-width: 80%;
            clear: both;
        }
        .message.sent {
            background: #dcf8c6;
            float: right;
        }
        .message.received {
            background: #eaeaea;
            float: left;
        }
        .chat-footer {
            display: flex;
            border-top: 1px solid #ddd;
        }
        .chat-footer input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 14px;
            outline: none;
        }
        .chat-footer button {
            background: #0066ff;
            border: none;
            color: white;
            padding: 0 20px;
            cursor: pointer;
        }
        .chat-footer button:hover {
            background: #0051cc;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">Live Chat</div>
    <div class="chat-body" id="chat-body"></div>
    <div class="chat-footer">
        <input type="text" id="message-input" placeholder="Type a message...">
        <button id="send-btn">Send</button>
    </div>
</div>

<script>
    // =======================
    // CONFIG
    // =======================
    const PUSHER_APP_KEY = "46f5a0b752ba7dad1935";
    const PUSHER_CLUSTER = "ap1";
    const API_BASE = "http://localhost:8000/api";

    const conversationId = 1;
    const senderId = 6;
    const senderType = "business";

    const chatBody = document.getElementById("chat-body");

    // =======================
    // FETCH MESSAGES
    // =======================
    function fetchMessages() {
        fetch(`${API_BASE}/conversations/${conversationId}/messages`)
            .then(res => res.json())
            .then(messages => {
                chatBody.innerHTML = "";
                messages.forEach(msg => appendMessage(msg));
                chatBody.scrollTop = chatBody.scrollHeight;
            });
    }

    function appendMessage(msg) {
        const div = document.createElement("div");
        div.classList.add("message");
        div.classList.add(msg.sender_id == senderId && msg.sender_type == senderType ? "sent" : "received");
        div.innerText = msg.message;
        chatBody.appendChild(div);
    }

    // =======================
    // SEND MESSAGE
    // =======================
    document.getElementById("send-btn").addEventListener("click", () => {
        const message = document.getElementById("message-input").value.trim();
        if (!message) return;

        fetch(`${API_BASE}/messages/send`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                conversation_id: conversationId,
                sender_id: senderId,
                sender_type: senderType,
                message: message
            }),
        })
            .then(res => res.json())
            .then(() => {
                document.getElementById("message-input").value = "";
            });
    });

    // =======================
    // PUSHER REALTIME
    // =======================
    Pusher.logToConsole = true;

    const pusher = new Pusher(PUSHER_APP_KEY, {
        cluster: PUSHER_CLUSTER,
        encrypted: true,
        authEndpoint: `${API_BASE}/broadcasting/auth`,
        auth: {
            headers: {
                Authorization: `Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvZ29vZ2xlL2NhbGxiYWNrIiwiaWF0IjoxNzYyMzEzMjc2LCJleHAiOjE3NjIzMTY4NzYsIm5iZiI6MTc2MjMxMzI3NiwianRpIjoiaHZQMEdiUnFQVW9ldEt1byIsInN1YiI6IjIiLCJwcnYiOiIxZDBhMDIwYWNmNWM0YjZjNDk3OTg5ZGYxYWJmMGZiZDRlOGM4ZDYzIn0.b4GZEtnc7HWsS7oDhC5MzyNdpD30R8hNXQs78PJJqok`
            }
        }
    });

    const channel = pusher.subscribe(`private-conversation.${conversationId}`);

    channel.bind("message.sent", function(data) {
        appendMessage(data);
        chatBody.scrollTop = chatBody.scrollHeight;
    });

    // =======================
    // INIT
    // =======================
    fetchMessages();
</script>

</body>
</html>
