<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="nome" value="Nome do time" />
        <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $time->nome)" maxlength="100" required autofocus />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sigla" value="Sigla" />
        <x-text-input id="sigla" name="sigla" type="text" class="mt-1 block w-full uppercase" :value="old('sigla', $time->sigla)" maxlength="3" required />
        <x-input-error :messages="$errors->get('sigla')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="estado" value="Estado (UF)" />
        <x-text-input id="estado" name="estado" type="text" class="mt-1 block w-full uppercase" :value="old('estado', $time->estado)" maxlength="2" required />
        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="cidade" value="Cidade" />
        <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" :value="old('cidade', $time->cidade)" maxlength="100" required />
        <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="estadio" value="Estádio" />
        <x-text-input id="estadio" name="estadio" type="text" class="mt-1 block w-full" :value="old('estadio', $time->estadio)" maxlength="100" required />
        <x-input-error :messages="$errors->get('estadio')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="cor_primaria" value="Cor principal" />
        <div class="mt-1 flex items-center gap-3">
            <input id="cor_primaria" name="cor_primaria" type="color" value="{{ old('cor_primaria', $time->cor_primaria ?: '#15803d') }}" class="h-10 w-16 cursor-pointer rounded-md border border-gray-300 bg-white p-1" required>
            <span class="text-sm text-gray-500">Escolha uma cor para identificar o time na classificação.</span>
        </div>
        <x-input-error :messages="$errors->get('cor_primaria')" class="mt-2" />
    </div>
</div>
