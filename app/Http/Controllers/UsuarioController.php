<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UsuarioRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::query()->orderBy('name')->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    public function edit(User $usuario): View
    {
        $roles = UserRole::cases();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(UsuarioRoleRequest $request, User $usuario): RedirectResponse
    {
        $novoPapel = UserRole::from($request->validated('role'));

        if ($usuario->is($request->user()) && $novoPapel !== UserRole::ADMIN) {
            return back()->with('error', 'Você não pode remover o próprio acesso de administrador.');
        }

        $ultimoAdmin = $usuario->isAdmin()
            && $novoPapel !== UserRole::ADMIN
            && User::query()->where('role', UserRole::ADMIN->value)->count() === 1;

        if ($ultimoAdmin) {
            return back()->with('error', 'O sistema precisa manter pelo menos um administrador.');
        }

        $usuario->update(['role' => $novoPapel]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Papel do usuário atualizado com sucesso!');
    }
}
