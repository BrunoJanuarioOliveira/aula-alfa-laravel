<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso negado | Rede Patas</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="login-page bg-body-secondary">
    <main class="login-box">
        <div class="card shadow-sm">
            <div class="card-body login-card-body text-center">
                <i class="bi bi-shield-lock text-danger" style="font-size: 3rem;"></i>
                <h1 class="h4 mt-2">Acesso negado</h1>
                <p class="text-body-secondary">Sua conta não tem permissão para acessar esta área (erro 403).</p>

                <div class="d-grid gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary">Voltar ao início</a>
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Sair da conta</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </main>
</body>
</html>
