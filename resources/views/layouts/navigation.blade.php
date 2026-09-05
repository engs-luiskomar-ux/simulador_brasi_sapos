@php
    $papel = Auth::user()->role;
    $papelLabel = is_object($papel) && method_exists($papel, 'label')
        ? $papel->label()
        : ucfirst((string) ($papel ?? 'torcedor'));
@endphp

<nav x-data="{ open: false }" class="border-b border-gray-200 bg-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                        <x-application-logo class="block h-9 w-9 text-green-700" />
                        <span class="hidden lg:inline">Bet dos Sapo Véio</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 lg:-my-px lg:ms-6 lg:flex">
                    <x-nav-link :href="route('apostas.index')" :active="request()->routeIs('apostas.index')">Palpitar</x-nav-link>
                    <x-nav-link :href="route('apostas.historico')" :active="request()->routeIs('apostas.historico')">Meus palpites</x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Início
                    </x-nav-link>

                    <x-nav-link :href="route('classificacao.index')" :active="request()->routeIs('classificacao.*')">
                        Classificação
                    </x-nav-link>

                    <x-nav-link :href="route('partidas.index')" :active="request()->routeIs('partidas.*')">
                        Partidas
                    </x-nav-link>

                    <x-nav-link :href="route('times.index')" :active="request()->routeIs('times.*')">
                        Times
                    </x-nav-link>

                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                            Usuários
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="text-left">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="mt-0.5 text-xs font-normal text-gray-500">{{ $papelLabel }} · {{ number_format(Auth::user()->saldo_creditos, 0, ',', '.') }} créditos</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Meu perfil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            Sair
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('apostas.index')" :active="request()->routeIs('apostas.index')">Palpitar</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('apostas.historico')" :active="request()->routeIs('apostas.historico')">Meus palpites · {{ number_format(Auth::user()->saldo_creditos, 0, ',', '.') }} créditos</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Início
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('classificacao.index')" :active="request()->routeIs('classificacao.*')">
                Classificação
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('partidas.index')" :active="request()->routeIs('partidas.*')">
                Partidas
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('times.index')" :active="request()->routeIs('times.*')">
                Times
            </x-responsive-nav-link>

            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    Usuários
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="mt-1 text-xs text-gray-500">{{ $papelLabel }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Meu perfil
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                    Sair
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
