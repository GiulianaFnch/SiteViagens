// Perfil (views/paginas/perfil.php): foto em grande, nome da foto escolhida e palavra-passe ao mudar o e-mail
const popup = document.querySelector('.popup');
const fundo = document.querySelector('.popup-background');
const abrir = aberto => { popup.classList.toggle('active', aberto); fundo.classList.toggle('active', aberto); };
document.querySelector('.foto-perfil').addEventListener('click', () => abrir(true));
document.querySelector('.close-btn').addEventListener('click', () => abrir(false));
fundo.addEventListener('click', () => abrir(false));

const ficheiro = document.querySelector('.account-settings-fileinput');
ficheiro.addEventListener('change', () => {
    document.querySelector('.ficheiro-escolhido').textContent = ficheiro.files[0] ? ficheiro.files[0].name : '';
});

const email = document.getElementById('email');
const campoSenha = document.getElementById('campo-senha');
email.addEventListener('input', () => {
    const mudou = email.value.trim().toLowerCase() !== email.dataset.original.toLowerCase();
    campoSenha.hidden = !mudou;
    document.getElementById('pass').required = mudou;
});
