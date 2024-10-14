# Site Viagens
# Bem-vindo ao repositório do Site Viagens!

Este projeto é uma aplicação web para gerenciar reservas de viagens, hospedagens, passagens aéreas e passeios turísticos. A aplicação permite que os usuários pesquisem, adicionem itens ao carrinho, façam reservas e visualizem perfis de vendedores.

## Funcionalidades

- **Hospedagem**: Pesquisa e reserva de hospedagens.
- **Passagens Aéreas**: Pesquisa e reserva de passagens aéreas.
- **Passeios Turísticos**: Pesquisa e reserva de passeios turísticos.
- **Carrinho de Compras**: Adição de itens ao carrinho e cálculo do total.
- **Perfil**: Configurações de conta do usuário.
- **Perfil do Vendedor**: Visualização do perfil do vendedor.
- **Chat**: Comunicação entre usuários e vendedores.

## Estrutura do Projeto


- **adm/**: Painel de gestão para o administrador/developer.
- **assets/**: Arquivos de estilo e imagens.
- **config/**: Arquivos de configuração e validação.
- **database/**: Arquivos da base de dados.
- **public/**: Arquivos públicos acessíveis via URL.
   - **carrinho/**: Funcionalidades relacionadas ao carrinho de compras.
   - **hotels/**: Funcionalidades relacionadas a hospedagens.
   - **pacotes/**: Funcionalidades relacionadas a pacotes de viagens.
   - **tours/**: Funcionalidades relacionadas a passeios turísticos.
   - **voos/**: Funcionalidades relacionadas a passagens aéreas.
   - **vendedor/**: Funcionalidades do perfil do vendedor.
   - **reservas.php**: Página de gerenciamento de reservas do usuário normal.
- **views/**: Partials e templates reutilizáveis.

## Requisitos

Para executar este projeto localmente, certifique-se de que possui os seguintes requisitos:

- **PHP** 7.4 ou superior
- **MySQL** 5.7 ou superior
- **Servidor Web** (Apache recomendado)
- **Git** para clonar o repositório

Ferramentas recomendadas para configurar o ambiente:

- [XAMPP](https://www.apachefriends.org/pt_br/index.html) ou [WAMP](https://www.wampserver.com/en/) para configurar o servidor local com Apache, PHP e MySQL.


## Instalação

1. Clone o repositório:
    ```bash 
    git clone https://github.com/GiulianaFnch/SiteViagens.git
    ```

2. Navegue até o diretório do projeto:
    ```bash
    cd SiteViagens
    ```

3. Configure o banco de dados:
    - Crie um banco de dados MySQL.
    - Importe o arquivo `site_viagens.sql` que está na pasta `database` para criar as tabelas necessárias.
    - Edite o arquivo `config/liga_bd.php` com as credenciais do seu banco de dados.

4. Inicie o servidor:
    - Se estiver usando o XAMPP, coloque o projeto na pasta `htdocs` e inicie o Apache e MySQL.

## Uso

### Perfil do Utilizador
Para visualizar o perfil do usuário normal, use o arquivo `perfil.php`. Este arquivo busca as informações do vendedor na tabela `t_user` onde `tipo_user` é 0.

### Adicionar ao Carrinho
Para adicionar um item ao carrinho, realize a pesquisa que quiser até o item específico, e clique em "Adicionar ao carrinho". Será verificado se o item já está no carrinho e atualiza a quantidade ou insere um novo registro.

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
