# 🎬 MyWatchList - App de Gerenciamento de Filmes e Séries

## 📋 Descrição

MyWatchList é uma aplicação web desenvolvida em PHP que permite gerenciar uma lista pessoal de filmes e séries, integrada com a API do TMDB (The Movie Database). Similar ao MyAnimeList, permite adicionar, editar e categorizar conteúdo com status e avaliações.

## ✨ Funcionalidades

- 🔐 **Sistema de Autenticação**: Crie sua conta e faça login com segurança.
- 👥 **Multiusuário**: Cada usuário possui sua própria lista privada e independente.
- 🔍 **Busca integrada com TMDB**: Busque filmes e séries em tempo real.
- 🏷️ **Status de Visualização**:
    - ❌ Não assistido
    - ▶️ Assistindo
    - ✅ Assistido
    - ⏳ Pretendo assistir
- ⭐ **Sistema de Classificação**: Avalie de 1 a 5 estrelas.
- 🎨 **Interface moderna e responsiva**: Design atraente em gradiente roxo com alertas visuais.
- 💾 **Banco de dados MySQL**: Armazene seus dados de forma persistente.

## 📁 Estrutura de Arquivos

```
my-watchlist-app-main/
├── config.php          # Configurações do banco de dados e TMDB API
├── login.php           # Autenticação e criação de usuários
├── logout.php          # Script para encerrar sessão
├── index.php           # Página principal - lista e busca do usuário logado
├── add.php             # Página para adicionar novo item
├── edit.php            # Página para editar item existente
├── delete.php          # Script para remover item
├── database.sql        # Script SQL para criar o banco de dados e tabelas relacionais
└── README.md           # Este arquivo
```

## 🚀 Instalação

### Pré-requisitos

- **PHP 7.0+** instalado
- **MySQL 5.7+** ou MariaDB instalado
- **Servidor web** (Apache, Nginx, etc.)
- **Conta TMDB** (gratuita em https://www.themoviedb.org/)

### Passo 1: Obter Chave API do TMDB

1. Acesse https://www.themoviedb.org/settings/api
2. Crie uma conta (se não tiver)
3. Copie sua **API Key v3**

### Passo 2: Criar Banco de Dados

1. Abra seu gerenciador MySQL (phpMyAdmin, MySQL Workbench, etc.)
2. Execute o conteúdo do arquivo `database.sql`:
   ```sql
   -- Copie todo o conteúdo de database.sql e execute
   ```

### Passo 3: Configurar a Aplicação

1. Extraia os arquivos em sua pasta web (ex: `htdocs` no XAMPP)
2. Abra `config.php` e substitua:
   ```php
   define('TMDB_API_KEY', 'COLOQUE_SUA_API_KEY_AQUI');
   define('DB_HOST', 'localhost');        // Host do banco
   define('DB_USER', 'root');             // Usuário do banco
   define('DB_PASS', '');                 // Senha do banco
   define('DB_NAME', 'watchlist_db');     // Nome do banco
   ```

### Passo 4: Acessar a Aplicação

Abra seu navegador e acesse:
```
http://localhost/my-watchlist-app-main/login.php
```

## 📖 Como Usar

### Criar Conta e Acessar

1. Acesse a página inicial, preencha um nome de usuário e senha e clique em "Criar Conta".
2. Faça o login com suas novas credenciais para acessar sua lista privada.

### Buscar e Adicionar Conteúdo

1. Na página inicial, use o campo de busca para procurar filmes ou séries
2. Selecione o tipo (Tudo, Filme, Série)
3. Clique em "Buscar"
4. Clique no botão "Adicionar +" em qualquer resultado
5. Escolha o status inicial e rating (opcional)
6. Clique em "Adicionar". O sistema não permitirá adicionar itens duplicados.

### Editar Item

1. Na sua watchlist, use os botões de edição ou lixeira no card do item desejado.
2. Suas alterações afetarão apenas a sua conta.

### Filtrar por Status

Você pode filtrar sua watchlist observando os badges de status coloridos:
- 🔴 **Não assistido** - Não iniciou ainda
- 🔵 **Assistindo** - Atualmente assistindo
- 🟢 **Assistido** - Já terminou de assistir
- 🟡 **Pretendo assistir** - Adicionado na lista de desejos

## 🔒 Segurança

- **Proteção de Senhas (Hashing)**: Senhas criptografadas no banco de dados utilizando o algoritmo BCRYPT (`password_hash`).
- **SQL Injection Prevention**: Usando prepared statements com PDO.
- **XSS Prevention**: Utilizando `htmlspecialchars()` em todas as saídas e alertas de sessão.
- **Controle de Acesso**: Proteção de rotas garantindo que usuários só gerenciem suas próprias listas via `user_id`.
- **API Key**: Mantenha sua chave TMDB em segredo (em `config.php`).

## 📚 Recursos

- [TMDB API Documentation](https://developer.themoviedb.org/docs)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)

## 📄 Licença

Este projeto é de código aberto e pode ser usado livremente.

## 👨‍💻 Desenvolvido com

- [**PHP 7.0+**](https://www.php.net/) - Lógica de backend e autenticação.
- [**MySQL 5.7+**](https://www.mysql.com/) - Banco de dados relacional (PDO).
- [**TMDB API v3**](https://developer.themoviedb.org/docs) - Fornecimento de dados e pôsteres em tempo real.
- **HTML5 & CSS3** - Estruturação e estilização da interface.
