// Chat (views/paginas/chat.php): escolher a conversa, mostrar as mensagens (atualizadas de 3 em 3 segundos)
// e enviar mensagens. O servidor devolve sempre a conversa inteira em HTML.
const chat = document.getElementById('chat-container');
const lista = document.getElementById('user-list');
const caixa = document.getElementById('chat-box');
const mensagens = document.getElementById('messages');
const campo = document.getElementById('message');
const emojis = document.getElementById('emoji-panel');
let conversa = null;
let temporizador = null;

function mostrar(html) {
    const noFim = mensagens.scrollHeight - mensagens.scrollTop - mensagens.clientHeight < 40;
    mensagens.innerHTML = html;
    if (noFim) {
        mensagens.scrollTop = mensagens.scrollHeight; // só desce se a pessoa não estiver a ler mensagens antigas
    }
}

function atualizar() {
    fetch(chat.dataset.base + 'chat_mensagens.php?user_id=' + conversa).then(r => r.text()).then(mostrar);
}

function abrirConversa(id) {
    conversa = id;
    lista.hidden = true;
    caixa.hidden = false;
    mensagens.innerHTML = '';
    atualizar();
    clearInterval(temporizador);
    temporizador = setInterval(atualizar, 3000);
    campo.focus();
}

function fecharConversa() {
    clearInterval(temporizador);
    conversa = null;
    caixa.hidden = true;
    lista.hidden = false;
}

document.querySelectorAll('.user-item').forEach(b => b.addEventListener('click', () => abrirConversa(b.dataset.id)));
document.getElementById('back-arrow').addEventListener('click', fecharConversa);
document.getElementById('abrir-emojis').addEventListener('click', () => { emojis.hidden = !emojis.hidden; });
emojis.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
    campo.value += b.textContent;
    emojis.hidden = true;
    campo.focus();
}));
document.getElementById('message-input').addEventListener('submit', e => {
    e.preventDefault();
    const texto = campo.value.trim();
    if (!texto || conversa === null) {
        return;
    }
    fetch(chat.dataset.base + 'chat_enviar.php', {
        method: 'POST',
        body: new URLSearchParams({ user_id: conversa, message: texto, csrf_token: chat.dataset.csrf }),
    }).then(r => r.text()).then(html => {
        campo.value = '';
        mostrar(html);
        mensagens.scrollTop = mensagens.scrollHeight;
    });
});
