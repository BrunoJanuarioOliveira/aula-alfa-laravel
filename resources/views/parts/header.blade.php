<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Alternar menu lateral">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto align-items-center gap-2">
            @auth
                @php($currentUser = auth()->user())
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-semibold"
                            style="width: 32px; height: 32px;" aria-hidden="true">
                            {{ mb_strtoupper(mb_substr($currentUser->name, 0, 1)) }}
                        </span>
                        <span class="d-none d-md-inline">{{ $currentUser->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-0 shadow" style="min-width: 260px;">
                        <li class="p-3 text-center border-bottom">
                            <div class="fw-semibold">{{ $currentUser->name }}</div>
                            <div class="small text-body-secondary">{{ $currentUser->email }}</div>
                            <span class="badge mt-2 {{ $currentUser->isAdmin() ? 'text-bg-danger' : 'text-bg-secondary' }}">
                                {{ $currentUser->isAdmin() ? 'Administrador' : 'Usuário' }}
                            </span>
                        </li>
                        <li class="p-2">
                            {{-- Logout é POST com CSRF: um link GET permitiria deslogar o usuário por requisição forjada. --}}
                            <form action="{{ route('logout') }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                {{-- Atalho visível fora do menu (telas médias para cima). --}}
                <li class="nav-item d-none d-md-block">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>
