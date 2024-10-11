# Site Viagens
# Bem-vindo ao repositório do Site Viagens!

Este projeto é uma aplicação web para gerenciar reservas de viagens, hospedagens, passagens aéreas e passeios turísticos. A aplicação permite que os usuários pesquisem, adicionem itens ao carrinho, façam reservas e visualizem perfis de vendedores.

## Funcionalidades

- **Hospedagem**: Pesquisa e reserva de hospedagens.
- **Passagens Aéreas**: Pesquisa e reserva de passagens aéreas.
- **Passeios Turísticos**: Pesquisa e reserva de passeios turísticos.
- **Carrinho de Compras**: Adição de itens ao carrinho e cálculo do total.
- **Perfil do Vendedor**: Visualização do perfil do vendedor.
- **Chat**: Comunicação entre usuários e vendedores.
- **Configurações**: Configurações de conta do usuário.

## Estrutura do Projeto

- **config/**: Arquivos de configuração e validação.
- **public/**: Arquivos públicos acessíveis via URL.
   - **carrinho/**: Funcionalidades relacionadas ao carrinho de compras.
   - **hotels/**: Funcionalidades relacionadas a hospedagens.
   - **tours/**: Funcionalidades relacionadas a passeios turísticos.
   - **reservas.php**: Página de gerenciamento de reservas.
   - **perfil_vendedor.php**: Página de visualização do perfil do vendedor.
- **assets/**: Arquivos de estilo e imagens.
- **views/**: Partials e templates reutilizáveis.

## Instalação

1. Clone o repositório:
    ```bash
    git clone https://github.com/seu-usuario/site-viagens.git
    ```

2. Navegue até o diretório do projeto:
    ```bash
    cd site-viagens
    ```

3. Configure o banco de dados:
    - Crie um banco de dados MySQL.
    - Importe o arquivo `site_viagens08.10.sql` para criar as tabelas necessárias.
    - Edite o arquivo `config/liga_bd.php` com as credenciais do seu banco de dados.

4. Inicie o servidor:
    - Se estiver usando o XAMPP, coloque o projeto na pasta `htdocs` e inicie o Apache e MySQL.

## Uso

### Adicionar ao Carrinho
Para adicionar um item ao carrinho, use o arquivo `adicionar_ao_carrinho.php`. Este arquivo verifica se o item já está no carrinho e atualiza a quantidade ou insere um novo registro.

### Visualizar Carrinho
Para visualizar os itens no carrinho, use o arquivo `carrinho.php`. Este arquivo exibe os itens adicionados e calcula o total do carrinho.

### Perfil do Vendedor
Para visualizar o perfil do vendedor, use o arquivo `perfil_vendedor.php`. Este arquivo busca as informações do vendedor na tabela `t_user` onde `tipo_user` é 2.

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

## Licença

Este projeto está licenciado sob a MIT License.

## Contato

Para mais informações, entre em contato com um dos colaboradores do projeto:
    - `Giuliana Finochio`
    - `Guilherme Silva`
    - `Gabrielle Reis`
    - `Karoline Moura`

### Resumo

- **Funcionalidades:** Descreve as principais funcionalidades do projeto.
- **Estrutura do Projeto:** Explica a estrutura de diretórios e arquivos.
- **Instalação:** Passos para clonar o repositório, configurar o banco de dados e iniciar o servidor.
- **Uso:** Instruções para adicionar itens ao carrinho, visualizar o carrinho e visualizar o perfil do vendedor.
- **Contribuição:** Informações sobre como contribuir para o projeto.
- **Licença:** Informação sobre a licença do projeto.
- **Contato:** Informações de contato para mais informações.