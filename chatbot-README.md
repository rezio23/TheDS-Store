# DS Assistant — AI Customer Support Chatbot

An embeddable AI chatbot widget for **The DS**, a premium multi-brand clothing store. Powered by the Groq API.

## Quick Start

### 1. Get a Groq API Key (free tier available!)

1. Visit [console.groq.com](https://console.groq.com)
2. Sign up with your email or Google account
3. Go to **API Keys** → **Create API Key**
4. Copy your key (starts with `gsk_`)

Groq offers a generous **free tier** — no credit card required to get started.

### 2. Configure Environment

Open the `.env` file in the project root and paste your key:

```
GROQ_API_KEY=gsk_your-key-here
CHATBOT_PORT=3001
```

### 3. Install & Run

```bash
npm install
npm run chatbot
```

The server starts on `http://localhost:3001`.

- **Demo page:** `http://localhost:3001/chatbot-demo.html`
- **API endpoint:** `POST http://localhost:3001/api/chat`

### 4. Test It

Open the demo page and click the chat bubble (bottom-right corner). Try asking:
- "What brands do you carry?"
- "Help me pick an outfit for a summer wedding"
- "What's your return policy?"

## Embed on Any Page

Add these two lines to any HTML page:

```html
<link rel="stylesheet" href="http://localhost:3001/chatbot.css">
<script src="http://localhost:3001/chatbot.js"></script>
```

That's it. The chatbot bubble appears at the bottom-right of the page.

## API Reference

### `POST /api/chat`

**Request body:**
```json
{
  "messages": [
    { "role": "user", "content": "Do you carry Nike sneakers?" },
    { "role": "assistant", "content": "Yes, we carry..." },
    { "role": "user", "content": "What sizes are available?" }
  ]
}
```

**Response:**
```json
{
  "reply": "We carry a full range of sizes from US 7 to US 14..."
}
```

## File Structure

```
server.js              Express backend + Groq API proxy
public/
  chatbot.css           Widget styles (self-contained, no conflicts)
  chatbot.js            Widget logic (vanilla JS, no dependencies)
  chatbot-demo.html     Demo store landing page
.env.example           Environment variable template
```

## Customization

- **Colors:** Edit the CSS variables at the top of `public/chatbot.css` (`--ds-accent`, `--ds-dark`)
- **System prompt:** Edit `SYSTEM_PROMPT` in `server.js`
- **Contact info:** Update email/phone in both `server.js` (system prompt) and `public/chatbot-demo.html`
- **Model:** Change the `model` parameter in `server.js`. Groq options: `llama-3.3-70b-versatile`, `llama-4-scout-17b-16e-instruct`, `mixtral-8x7b-32768`, `gemma2-9b-it`

## Tech Stack

- **Backend:** Node.js, Express
- **AI:** Groq API (Llama 3.3 70B)
- **Frontend:** Vanilla JavaScript, CSS (no frameworks)
