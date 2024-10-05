<script>
    
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    const destinosContainer = document.querySelector('.destinos-container');

    // Calcula a largura de um cartão (incluindo a margem entre os cartões)
    const cardWidth = document.querySelector('.destino-card').offsetWidth + 20; // Ajuste conforme necessário

    // Função para avançar no carrossel
    nextButton.addEventListener('click', () => {
        destinosContainer.scrollBy({
            top: 0,
            left: cardWidth,  // TEM QUE MESCLAR ESTE CODIGO COM O CODIGO QUE JA EXISTE 
            behavior: 'smooth'
        });
    });

    // Função para retroceder no carrossel
    prevButton.addEventListener('click', () => {
        destinosContainer.scrollBy({
            top: 0,
            left: -cardWidth,  // Usa a largura calculada
            behavior: 'smooth'
        });
    });
</script>
