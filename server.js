import 'dotenv/config';
import express from 'express';
import cors from 'cors';
import Groq from 'groq-sdk';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const app = express();
const PORT = process.env.CHATBOT_PORT || 3001;

app.use(cors());
app.use(express.json());
app.use(express.static(join(__dirname, 'public')));

const groq = new Groq({
  apiKey: process.env.GROQ_API_KEY,
});

const SYSTEM_PROMPT = `You are 'DS Assistant', the official 24/7 AI support chatbot for The DS — a premium multi-brand clothing store carrying top global brands including Nike, Polo, Puma, Adidas, and many more. Your job is to assist customers in a friendly, professional, and helpful manner.

You can help customers with:
- Information about the brands we carry (Nike, Polo, Puma, Adidas, etc.)
- General sizing guidance and how to choose the right fit
- Product recommendations based on style, occasion, or budget
- Store policies: returns, exchanges, shipping
- Order-related questions (tracking, delivery estimates)
- Promotions, new arrivals, and seasonal collections
- General fashion advice

Rules you must follow:
1. Always be warm, enthusiastic, and on-brand — The DS is a premium, trendy store.
2. If a question is too specific (e.g. exact stock availability, a specific order number, payment issues, or complaints) and you cannot confidently answer, say something like: 'That's a great question — for the most accurate answer, I recommend reaching out to our team directly. You can email us at support@theds-store.com or call us at (555) 123-4567. We're happy to help!'
3. Never make up prices, stock numbers, or policies you are not sure about.
4. Keep responses concise — 2 to 4 sentences unless the customer needs a detailed answer.
5. Always end difficult or unanswered questions by redirecting to the contact details above.
6. The DS operates 24/7, so always reassure customers that support is always available.`;

app.post('/api/chat', async (req, res) => {
  try {
    const { messages } = req.body;

    if (!messages || !Array.isArray(messages)) {
      return res.status(400).json({ error: 'Invalid request: messages array required' });
    }

    // Prepend system prompt as the first message
    const fullMessages = [
      { role: 'system', content: SYSTEM_PROMPT },
      ...messages,
    ];

    const response = await groq.chat.completions.create({
      model: 'llama-3.3-70b-versatile',
      max_tokens: 1024,
      messages: fullMessages,
      temperature: 0.7,
    });

    const reply = response.choices[0]?.message?.content
      || "I'm sorry, I couldn't process that. Please reach out to our team at support@theds-store.com or call (555) 123-4567.";

    res.json({ reply });
  } catch (error) {
    console.error('Groq API error:', error.message);

    if (error.status === 429) {
      return res.status(429).json({
        reply: "We're experiencing high traffic right now! Please try again in a moment, or reach our team anytime at support@theds-store.com or (555) 123-4567."
      });
    }

    res.status(500).json({
      reply: "I'm having a bit of trouble right now — sorry about that! Our team is still available 24/7 at support@theds-store.com or (555) 123-4567. We'll get you sorted!"
    });
  }
});

app.listen(PORT, () => {
  console.log(`[DS Assistant] Chatbot server running on http://localhost:${PORT}`);
  console.log(`[DS Assistant] Demo page: http://localhost:${PORT}/chatbot-demo.html`);
  console.log(`[DS Assistant] Embed widget with: <link rel="stylesheet" href="/chatbot.css"> + <script src="/chatbot.js"></script>`);
});
