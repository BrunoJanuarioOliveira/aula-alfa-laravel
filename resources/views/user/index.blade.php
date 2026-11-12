@extends('layouts.default')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Usuários cadastrados</h3>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i> Novo usuário
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" role="table">
                    <thead>
                        <tr>
                            <th style="width: 10px" scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Perfil</th>
                            <th scope="col" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}.</td>
                                <td>
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span class="badge text-bg-info ms-1">você</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->isAdmin())
                                        <span class="badge text-bg-danger">Administrador</span>
                                    @else
                                        <span class="badge text-bg-secondary">Usuário</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>

                                    {{-- Ninguém exclui a própria conta pelo painel. --}}
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-body-secondary py-4">Nenhum usuário encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
