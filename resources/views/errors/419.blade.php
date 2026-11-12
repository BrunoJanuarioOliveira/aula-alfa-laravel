<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sessão expirada | Rede Patas</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="login-page bg-body-secondary">
    <main class="login-box">
        <div class="card shadow-sm">
            <div class="card-body login-card-body text-center">
                <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                <h1 class="h4 mt-2">Sessão expirada</h1>
                <p class="text-body-secondary">A página ficou aberta por muito tempo. Volte e tente novamente.</p>
                <div class="d-grid">
                    <a href="{{ route('login') }}" class="btn btn-primary">Ir para o login</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
