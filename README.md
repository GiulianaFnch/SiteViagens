# Site de Viagens
Este é um sistema de 

## Organização dos diretórios

/projeto_viagens/
│
├── /assets/              # Arquivos estáticos (CSS, JS, imagens, etc.)
│   ├── /css/             # Arquivos de estilo (CSS)
│   │   └── styles.css    # Arquivo CSS principal
│   ├── /js/              # Arquivos JavaScript
│   │   └── scripts.js    # Arquivo JS principal
│   └── /img/             # Imagens (logo, fotos de destinos, etc.)
│       └── logo.png      # Exemplo de imagem
│
├── /config/              # Configurações do projeto (banco de dados, variáveis de ambiente)
│   └── database.php      # Conexão com o banco de dados
│
├── /controllers/         # Controladores (lógica de controle das páginas e funções)
│   ├── UserController.php # Gerencia login, cadastro e perfil do usuário
│   ├── FlightController.php # Gerencia a busca e reserva de voos
│   ├── HotelController.php  # Gerencia a busca e reserva de hotéis
│   └── ActivityController.php # Gerencia a busca e reserva de atividades
│
├── /models/              # Modelos (classes que interagem com o banco de dados)
│   ├── User.php          # Classe do usuário (CRUD do usuário)
│   ├── Flight.php        # Classe para manipulação de voos
│   ├── Hotel.php         # Classe para manipulação de hotéis
│   └── Activity.php      # Classe para manipulação de atividades
│
├── /views/               # Templates HTML (onde o conteúdo é exibido)
│   ├── /partials/        # Componentes reutilizáveis (cabeçalho, rodapé, etc.)
│   │   ├── header.php    # Cabeçalho do site
│   │   └── footer.php    # Rodapé do site
│   ├── /auth/            # Páginas de autenticação
│   │   ├── login.php     # Página de login
│   │   └── register.php  # Página de registro
│   ├── /flights/         # Páginas relacionadas a voos
│   │   └── search.php    # Página de busca de voos
│   ├── /hotels/          # Páginas relacionadas a hotéis
│   │   └── search.php    # Página de busca de hotéis
│   └── /activities/      # Páginas relacionadas a atividades
│       └── search.php    # Página de busca de atividades
│
├── /public/              # Arquivos acessíveis diretamente pelo navegador (ponto de entrada)
│   ├── index.php         # Página inicial do site
│   ├── login.php         # Página de login (se for a rota direta)
│   ├── register.php      # Página de registro
│   └── reservas.php      # Página de reservas
│
├── /routes/              # Definição de rotas (separação de lógica entre URLs)
│   └── web.php           # Rotas da aplicação (mapeamento de URLs)
│
├── /vendor/              # Dependências externas (caso usem Composer para pacotes)
│
├── .gitignore            # Arquivos/pastas a serem ignorados pelo Git
├── README.md             # Documentação do projeto
└── composer.json         # Arquivo de configuração do Composer (dependências)


## Funcionalidades

## Tecnologias Utilizadas

## Instalação

### Pré-requisitos

Antes de rodar o projeto, certifique-se de que tem as seguintes ferramentas instaladas:


### Passos de Instalação

1. **Clone o repositório do projeto:**

   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git

2. **Inicie o XAMPP**:

   - Abra o XAMPP Control Panel.
   - Inicie os serviços **Apache** e **MySQL** clicando no botão "Start" ao lado de cada um.

3. **Importe o banco de dados no phpMyAdmin**:

4. **Abra o projeto no navegador**:

   - Após configurar o XAMPP e importar o banco de dados, abra o navegador e digite o seguinte endereço:

     ```
     http://localhost/nome-do-projeto
     ```

     - Substitua `nome-do-projeto` pelo nome da pasta do seu projeto dentro do diretório `htdocs` do XAMPP.

   - Você será direcionado para a página inicial do sistema de gestão de biblioteca, onde poderá realizar login, cadastrar usuários e gerenciar livros, autores, clientes e empréstimos.

## Estrutura do Projeto

O projeto está organizado da seguinte forma:

- `/exemplos`: Aqui está projetos que utilizamos como inspiração para algumas partes do desenvolvimento.
- `/database`: Contém o arquivo SQL (`biblioteca.sql`) necessário para criar e popular o banco de dados MySQL.

## Como Usar o Sistema

### 1. **Login/Logout**

- Acesse a página de login na URL principal do sistema (`http://localhost/nome-do-projeto`).
- Faça login com seu nome de usuário e senha, ou crie um novo usuário clicando em "Cadastrar".
- Após o login, você será direcionado ao painel de controle.

### 2. 

## Melhorias Futuras

## Contribuição

Se você deseja contribuir com melhorias para o projeto, faça um fork do repositório, crie uma nova branch para sua feature e envie um pull request. Discussões sobre novos recursos e melhorias são sempre bem-vindas!

## Licença
