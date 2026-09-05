<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Usuários</h2>
            <p class="mt-1 text-sm text-gray-500">Defina o papel de cada usuário no sistema.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Nome</th>
                                <th class="px-5 py-3">E-mail</th>
                                <th class="px-5 py-3">Papel</th>
                                <th class="px-5 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($usuarios as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">
                                        {{ $usuario->name }}
                                        @if ($usuario->is(auth()->user()))
                                            <span class="ml-1 text-xs font-normal text-gray-400">(você)</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $usuario->email }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">{{ $usuario->role->label() }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <a href="{{ route('usuarios.edit', $usuario) }}" class="font-medium text-blue-700 hover:text-blue-800">Alterar papel</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-gray-500">Nenhum usuário cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($usuarios->hasPages())
                <div class="mt-6">{{ $usuarios->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
