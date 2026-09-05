<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Alterar papel do usuário</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-6 rounded-md bg-gray-50 px-4 py-3 text-sm">
                    <p class="font-medium text-gray-900">{{ $usuario->name }}</p>
                    <p class="mt-1 text-gray-500">{{ $usuario->email }}</p>
                </div>

                <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="role" value="Papel no sistema" />
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}" @selected($role->value === old('role', $usuario->role->value))>
                                    {{ $role->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="mt-6 rounded-md border border-gray-200 p-4 text-sm text-gray-600">
                        <p><strong>Administrador:</strong> gerencia times, partidas e usuários.</p>
                        <p class="mt-1"><strong>Organizador:</strong> cadastra e simula partidas.</p>
                        <p class="mt-1"><strong>Torcedor:</strong> consulta jogos e classificação.</p>
                    </div>

                    <div class="mt-6 flex items-center gap-3 border-t border-gray-200 pt-5">
                        <x-primary-button>Salvar papel</x-primary-button>
                        <a href="{{ route('usuarios.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
