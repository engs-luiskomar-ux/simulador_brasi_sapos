<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Cadastrar time</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <form action="{{ route('times.store') }}" method="POST">
                    @csrf
                    @include('times._form')

                    <div class="mt-6 flex items-center gap-3 border-t border-gray-200 pt-5">
                        <x-primary-button>Cadastrar</x-primary-button>
                        <a href="{{ route('times.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
