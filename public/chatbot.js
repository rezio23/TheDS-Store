(function () {
  'use strict';

  var conversation = [];
  var isOpen = false;
  var isWaiting = false;

  // ── API config ──
  var API_URL = 'http://localhost:3001/api/chat';

  // ── SVG icons ──
  var ICON_CHAT = '<svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/><path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/></svg>';
  var ICON_CLOSE = '<svg viewBox="0 0 24 24"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>';
  var ICON_SEND = '<svg viewBox="0 0 24 24"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>';

  // ── Build DOM ──
  function buildWidget() {
    var container = document.createElement('div');
    container.id = 'ds-chatbot-container';
    container.innerHTML =
      '<button id="ds-chat-bubble" aria-label="Chat with DS Assistant">' +
        '<span class="ds-bubble-chat">' + ICON_CHAT + '</span>' +
        '<span class="ds-bubble-close">' + ICON_CLOSE + '</span>' +
        '<span class="ds-badge"></span>' +
      '</button>' +
      '<div id="ds-chat-window">' +
        '<div class="ds-chat-header">' +
          '<div class="ds-chat-avatar">DS</div>' +
          '<div class="ds-chat-header-info">' +
            '<div class="ds-chat-header-name">DS Assistant <span class="ds-online-dot"></span></div>' +
            '<div class="ds-chat-header-status">Online 24/7</div>' +
          '</div>' +
        '</div>' +
        '<div class="ds-chat-messages" id="ds-chat-messages"></div>' +
        '<div class="ds-chat-input-area">' +
          '<input type="text" class="ds-chat-input" id="ds-chat-input" placeholder="Ask me anything..." maxlength="500" />' +
          '<button class="ds-chat-send" id="ds-chat-send" aria-label="Send message">' + ICON_SEND + '</button>' +
        '</div>' +
      '</div>';

    document.body.appendChild(container);

    // Bind events
    document.getElementById('ds-chat-bubble').addEventListener('click', toggleChat);
    document.getElementById('ds-chat-send').addEventListener('click', sendMessage);
    document.getElementById('ds-chat-input').addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
  }

  // ── Toggle chat window ──
  function toggleChat() {
    isOpen = !isOpen;
    var bubble = document.getElementById('ds-chat-bubble');
    var windowEl = document.getElementById('ds-chat-window');

    if (isOpen) {
      bubble.classList.add('ds-open');
      windowEl.classList.add('ds-open');
      document.getElementById('ds-chat-input').focus();

      // Show welcome message on first open
      if (!windowEl.dataset.visited) {
        windowEl.dataset.visited = '1';
        setTimeout(function () {
          addMessage('bot', "Hi! I'm DS Assistant, your 24/7 fashion guide at The DS. Ask me anything about our brands, sizing, or orders!");
        }, 400);
      }
    } else {
      bubble.classList.remove('ds-open');
      windowEl.classList.remove('ds-open');
    }
  }

  // ── Send message ──
  function sendMessage() {
    if (isWaiting) return;

    var input = document.getElementById('ds-chat-input');
    var btn = document.getElementById('ds-chat-send');
    var text = input.value.trim();

    if (!text) return;

    input.value = '';
    input.focus();

    addMessage('user', text);
    conversation.push({ role: 'user', content: text });

    // Show typing indicator
    isWaiting = true;
    btn.disabled = true;
    showTyping();

    fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ messages: conversation }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        hideTyping();
        isWaiting = false;
        btn.disabled = false;
        document.getElementById('ds-chat-input').focus();

        if (data.reply) {
          addMessage('bot', data.reply);
          conversation.push({ role: 'assistant', content: data.reply });
        }
      })
      .catch(function () {
        hideTyping();
        isWaiting = false;
        btn.disabled = false;
        addMessage('bot', "I'm having a bit of trouble right now — sorry about that! Our team is still available 24/7 at support@theds-store.com or (555) 123-4567. We'll get you sorted!");
      });
  }

  // ── Add message to chat ──
  function addMessage(role, text) {
    var msgs = document.getElementById('ds-chat-messages');
    var div = document.createElement('div');
    div.className = 'ds-message ds-message--' + role;
    div.textContent = text;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
  }

  // ── Typing indicator ──
  function showTyping() {
    var msgs = document.getElementById('ds-chat-messages');
    var div = document.createElement('div');
    div.className = 'ds-typing';
    div.id = 'ds-typing-indicator';
    div.innerHTML = '<span></span><span></span><span></span>';
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
  }

  function hideTyping() {
    var el = document.getElementById('ds-typing-indicator');
    if (el) el.remove();
  }

  // ── Init ──
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', buildWidget);
  } else {
    buildWidget();
  }
})();
