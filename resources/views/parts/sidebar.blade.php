<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <i class="bi bi-heart-fill text-danger ms-2 me-2"></i>
            <span class="brand-text fw-light"><b>Rede</b> Patas</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="navigation">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house"></i>
                        <p>Início</p>
                    </a>
                </li>

                {{-- Esconder os links é só conveniência de UI; a proteção real é o middleware EnsureAdmin. --}}
                @if (auth()->user()?->isAdmin())
                    <li class="nav-header">ADMINISTRAÇÃO</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*', 'admin.user.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Listar Usuario</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="nav-icon bi bi-person-plus"></i>
                            <p>Novo usuário</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
