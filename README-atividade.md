## Developers

Bruno Januario de Olvieira RA:250276    

Lucas Dantas RA:250332

# Atividade prática: autenticação e área administrativa

Aplicação Laravel com autenticação baseada em sessão, cadastro público de contas comuns e área administrativa (`/admin/*`) protegida por middleware de autorização.

## Funcionalidades

- **Login** (`GET/POST /login`): tela independente do layout administrativo, com CSRF, erros de validação, mensagem genérica para credenciais inválidas, e-mail preservado após falha (a senha nunca é repopulada) e limite de tentativas.
- **Logout** (`POST /logout`): invalida a sessão, regenera o token CSRF e redireciona ao login. O botão **Sair** fica no menu do usuário (`resources/views/parts/header.blade.php`) e envia um formulário POST com CSRF.
- **Cadastro público** (`GET /register`, `POST /store`): cria sempre uma conta **comum**. Nenhum campo do formulário concede privilégio administrativo.
- **Área administrativa** (`/admin/*`): protegida pelo middleware `EnsureAdmin`.

## Regras de acesso

| Método e caminho | Acesso | Resultado |
|---|---|---|
| `GET /` | Autenticado | Página inicial (visitante vai para `/login`) |
| `GET /login` | Público | Formulário de login |
| `POST /login` | Público, com limite de tentativas | Valida credenciais e autentica |
| `POST /logout` | Autenticado | Encerra a sessão |
| `GET /register` | Público | Formulário de cadastro |
| `POST /store` | Público | Valida e cria conta comum |
| `/admin/*` | Perfil `admin` | Funcionalidades do painel |

Comportamento do middleware `EnsureAdmin` (`app/Http/Middleware/EnsureAdmin.php`):

- Visitante não autenticado: redireciona para `/login`.
- Autenticado sem `profile.type = admin`: responde **403**.
- Autenticado com `profile.type = admin`: a requisição prossegue.

Após o login:

- Usuário comum vai para `/`.
- Administrador vai para `/admin/users`, ou para o destino interno que tentou acessar antes (somente URLs do mesmo host são aceitas).

## Limite de tentativas de login

Implementado em `AuthController::login` com `RateLimiter`:

- **5 tentativas falhas** por combinação **e-mail + IP** em **60 segundos**.
- Na tentativa seguinte o login é bloqueado, com a mensagem informando quantos segundos faltam.
- O contador é zerado quando o login é bem-sucedido.
- Erros de validação (campo vazio ou e-mail malformado) não contam como tentativa.

Limitações: um atacante pode alternar IPs ou e-mails; o bloqueio pode ser usado para atrapalhar o login de outra pessoa; e o limite não substitui senhas fortes nem autenticação em dois fatores.

## Perfil e schema

- O model `Profile` usa a tabela `profile`, que corresponde ao schema real.
- A migration original criou a coluna `description` (NOT NULL), mas o model e as views usam `address`. Em vez de editar uma migration já compartilhada, a migration `2026_11_03_000001_rename_description_to_address_in_profile_table` renomeia a coluna e a torna nullable.
- A migration `2026_11_03_000002_create_user_languages_table` e o model `UserLanguages` sustentam a tela de edição do painel.

## Como executar

Pré-requisitos: PHP 8.3+ (com `pdo_sqlite`), Composer e Node.js.

Caminho rápido (cria `.env`, gera a `APP_KEY`, cria o banco SQLite, roda migrations e seeders e compila os assets):

```bash
composer setup
php artisan serve
```

Ou, passo a passo:

```bash
composer install
cp .env.example .env          # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate
touch database/database.sqlite   # PowerShell: New-Item database/database.sqlite -ItemType File
php artisan migrate --seed
npm install
npm run build                 # ou, em desenvolvimento: npm run dev
php artisan serve
```

Acesse `http://127.0.0.1:8000`.

### Problemas comuns

| Erro | Causa | Solução |
|---|---|---|
| `No application encryption key has been specified` | Falta `APP_KEY` | `php artisan key:generate` |
| `Vite manifest not found` | Assets não compilados | `npm install` e `npm run build` |
| `no such table: sessions` | Migrations não rodaram | `php artisan migrate --seed` |
| `could not find driver` | Extensão do banco desativada no PHP | Ativar `pdo_sqlite` (ou `pdo_mysql`) no `php.ini` |
| Config antiga em cache | Cache de configuração | `php artisan optimize:clear` |
| Banco em estado inconsistente | Migrations parciais | `php artisan migrate:fresh --seed` (apaga os dados) |

O banco padrão é SQLite. Para usar MySQL, veja os comentários em `.env.example` (compatíveis com `database/docker-compose.yml`).

### Contas de demonstração (apenas ambiente local)

O `AdminUserSeeder` só executa com `APP_ENV=local`:

| Tipo | E-mail | Senha |
|---|---|---|
| Administrador | `admin@example.test` | `admin-local-123` |

Essas credenciais são apenas para demonstração local. Não use senhas reais nem este seeder em produção. O administrador nunca é criado pelo cadastro público.

Uma conta comum pode ser criada em `/register`.

## Testes

```bash
php artisan test
```

`tests/Feature/AuthenticationTest.php` cobre:

- visitante redirecionado ao tentar abrir `/` e `/admin/users`;
- cadastro de conta comum (sem perfil admin, mesmo se `type=admin` for enviado);
- login e logout de conta comum;
- 403 para conta comum em `/admin/users`;
- login de administrador e acesso ao painel;
- retorno ao destino interno solicitado;
- credenciais inválidas e limite de tentativas;
- logout apenas por POST;
- verificação de que toda rota `admin*` registrada usa `EnsureAdmin`.

## Cenários para demonstração (capturas de tela)

1. Visitante abrindo `/` e `/admin/users` (redireciona para `/login`).
2. Cadastro de uma conta comum em `/register`.
3. Login e logout dessa conta.
4. Acesso da conta comum a `/admin/users` (resposta 403).
5. Login do administrador e acesso ao painel.

> Adicione as capturas em `docs/` e referencie-as aqui, por exemplo: `![Login](docs/login.png)`.

## Reflexões

**Por que um usuário autenticado ainda pode receber 403?**
Autenticação identifica quem é o usuário; autorização define o que ele pode fazer. Uma conta comum está logada, mas seu perfil não é `admin`.

**Qual o risco de confiar apenas em um botão escondido na interface?**
Esconder um link não impede ninguém de digitar a URL. A regra precisa ser aplicada no servidor, no middleware. A interface é apenas conveniência.

**Por que logout deve ser POST protegido por CSRF?**
Um `GET /logout` poderia ser disparado por uma imagem ou link em outro site, deslogando o usuário sem que ele perceba. Com POST e token CSRF, só o formulário legítimo da aplicação consegue encerrar a sessão.

**Como o limite de tentativas ajuda e quais são suas limitações?**
Reduz ataques de força bruta contra uma conta. Mas pode ser contornado trocando de IP ou de e-mail, pode ser usado para bloquear terceiros e não substitui senhas fortes ou 2FA.

**Como provisionar o primeiro administrador sem expor isso ao público?**
Por seeder (restrito ao ambiente local) ou comando artisan executado no servidor, com credenciais vindas do ambiente. Nunca por um formulário público que aceite `type = admin`.
