<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Times</h2>
                <p class="mt-1 text-sm text-gray-500">Clubes participantes do campeonato.</p>
            </div>

            @can('create', App\Models\Time::class)
                <a href="{{ route('times.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                    Novo time
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Sigla</th>
                                <th class="px-5 py-3">Nome</th>
                                <th class="px-5 py-3">Cidade</th>
                                <th class="px-5 py-3">Estádio</th>
                                <th class="px-5 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($times as $time)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-700">
                                        <span class="mr-2 inline-block h-3 w-3 rounded-full border border-gray-300 align-middle" style="background-color: {{ $time->cor_primaria }}"></span>
                                        {{ $time->sigla }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ $time->nome }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $time->cidade }}/{{ $time->estado }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $time->estadio }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('times.show', $time) }}" class="font-medium text-green-700 hover:text-green-800">Ver</a>

                                            @can('update', $time)
                                                <a href="{{ route('times.edit', $time) }}" class="font-medium text-blue-700 hover:text-blue-800">Editar</a>
                                            @endcan

                                            @can('delete', $time)
                                                <form action="{{ route('times.destroy', $time) }}" method="POST" onsubmit="return confirm('Deseja excluir este time?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-700 hover:text-red-800">Excluir</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">Nenhum time cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($times->hasPages())
                <div class="mt-6">{{ $times->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
