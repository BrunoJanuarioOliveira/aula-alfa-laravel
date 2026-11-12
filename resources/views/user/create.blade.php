<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro | Rede Patas</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="register-page bg-body-secondary">
    <main class="register-box">
        <div class="register-logo">
            <a href="{{ route('login') }}"><i class="bi bi-heart-fill text-danger"></i> <b>Rede</b> Patas</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Preencha os campos para se cadastrar</p>

                <form action="{{ route('users.store') }}" method="POST" novalidate>
                    @csrf

                    <label class="visually-hidden" for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nome"
                            autocomplete="name"
                            required
                            autofocus
                        />
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            autocomplete="email"
                            required
                        />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="password">Senha</label>
                    <div class="input-group mb-1">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Senha (mínimo 8 caracteres)"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        />
                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="password"
                            aria-label="Mostrar senha">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text mb-3">Use pelo menos 8 caracteres.</div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    @auth
                        <a href="{{ route('admin.users.index') }}"><i class="bi bi-arrow-left"></i> Voltar ao painel</a>
                    @else
                        Já tem uma conta? <a href="{{ route('login') }}">Entrar</a>
                    @endauth
                </p>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.dataset.togglePassword);
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
                button.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
            });
        });

        var form = document.querySelector('form');
        var submit = form.querySelector('[type="submit"]');
        var submitLabel = submit.innerHTML;
        form.addEventListener('submit', function () {
            submit.disabled = true;
            submit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Aguarde...';
        });
        window.addEventListener('pageshow', function () {
            submit.disabled = false;
            submit.innerHTML = submitLabel;
        });
    </script>
</body>
</html>
