<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <x-input-label for="rodada" value="Rodada" />
        <x-text-input id="rodada" name="rodada" type="number" class="mt-1 block w-full" :value="old('rodada', $partida->rodada)" min="1" max="38" required />
        <x-input-error :messages="$errors->get('rodada')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="data_partida" value="Data e horário" />
        <x-text-input id="data_partida" name="data_partida" type="datetime-local" class="mt-1 block w-full" value="{{ old('data_partida', $partida->data_partida?->format('Y-m-d\TH:i')) }}" />
        <x-input-error :messages="$errors->get('data_partida')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="mandante_id" value="Mandante" />
        <select id="mandante_id" name="mandante_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Selecione</option>
            @foreach ($times as $time)
                <option value="{{ $time->id }}" @selected((string) $time->id === (string) old('mandante_id', $partida->mandante_id))>
                    {{ $time->nome }} ({{ $time->sigla }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mandante_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="visitante_id" value="Visitante" />
        <select id="visitante_id" name="visitante_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Selecione</option>
            @foreach ($times as $time)
                <option value="{{ $time->id }}" @selected((string) $time->id === (string) old('visitante_id', $partida->visitante_id))>
                    {{ $time->nome }} ({{ $time->sigla }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('visitante_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="gols_mandante" value="Gols do mandante" />
        <x-text-input id="gols_mandante" name="gols_mandante" type="number" class="mt-1 block w-full" :value="old('gols_mandante', $partida->gols_mandante)" min="0" max="20" />
        <x-input-error :messages="$errors->get('gols_mandante')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="gols_visitante" value="Gols do visitante" />
        <x-text-input id="gols_visitante" name="gols_visitante" type="number" class="mt-1 block w-full" :value="old('gols_visitante', $partida->gols_visitante)" min="0" max="20" />
        <x-input-error :messages="$errors->get('gols_visitante')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <p class="rounded-md bg-gray-50 px-4 py-3 text-sm text-gray-600">
            Informe os dois placares para marcar a partida como finalizada. Sem placar, ela permanecerá agendada.
        </p>
    </div>
</div>
