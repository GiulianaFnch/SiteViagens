// Configurações (views/paginas/configuracoes.php): interruptor das ofertas e formulário da palavra-passe
const interruptor = document.getElementById('interruptor-ofertas');
const lista = document.getElementById('lista-configuracoes');
const formulario = document.getElementById('form-atualizar-senha');

function alternarOfertas() {
    const ligar = interruptor.getAttribute('aria-checked') !== 'true';
    interruptor.setAttribute('aria-checked', ligar ? 'true' : 'false');
    fetch(window.location.pathname, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ notificacoes_ofertas: ligar ? '1' : '0', csrf_token: interruptor.dataset.csrf }),
    }).then(r => { if (!r.ok) throw new Error(); })
      .catch(() => interruptor.setAttribute('aria-checked', ligar ? 'false' : 'true')); // volta atrás se falhar
}

function mostrarFormulario(mostrar) {
    formulario.hidden = !mostrar;
    lista.hidden = mostrar;
}

interruptor.addEventListener('click', alternarOfertas);
interruptor.addEventListener('keydown', e => { if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); alternarOfertas(); } });
document.getElementById('abrir-senha').addEventListener('click', () => mostrarFormulario(true));
document.getElementById('voltar').addEventListener('click', () => mostrarFormulario(false));
