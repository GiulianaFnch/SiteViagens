// Seleciona os elementos dos botões de navegação e o container do carrossel
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');
const destinosContainer = document.querySelector('.destinos-container');

// Função para calcular a largura total de um cartão (incluindo margens)
function getCardWidth() {
    const card = document.querySelector('.destino-card');
    if (!card) return 0; // Verifica se o cartão existe no DOM
    const cardStyle = window.getComputedStyle(card);
    const cardMarginRight = parseInt(cardStyle.marginRight) || 0; // Garante que a margem seja um número
    return card.offsetWidth + cardMarginRight;
}

// Verifica se os botões existem antes de adicionar os event listeners
if (nextButton && prevButton && destinosContainer) {

    // Função para avançar no carrossel
    nextButton.addEventListener('click', () => {
        const cardWidth = getCardWidth();  // Recalcula a largura do cartão ao clicar
        destinosContainer.scrollBy({
            top: 0,
            left: cardWidth,  // Rola para a direita pela largura de um cartão
            behavior: 'smooth'
        });
    });

    // Função para retroceder no carrossel
    prevButton.addEventListener('click', () => {
        const cardWidth = getCardWidth();  // Recalcula a largura do cartão ao clicar
        destinosContainer.scrollBy({
            top: 0,
            left: -cardWidth,  // Rola para a esquerda pela largura de um cartão
            behavior: 'smooth'
        });
    });

} else {
    console.error('Botões ou container do carrossel não encontrados.');
}
