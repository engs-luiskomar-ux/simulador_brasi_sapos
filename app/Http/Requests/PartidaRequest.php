<?php

namespace App\Http\Requests;

use App\Models\Partida;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PartidaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rodada' => ['required', 'integer', 'min:1', 'max:38'],
            'data_partida' => ['nullable', 'date'],
            'mandante_id' => ['required', 'integer', 'exists:times,id'],
            'visitante_id' => ['required', 'integer', 'different:mandante_id', 'exists:times,id'],
            'gols_mandante' => ['nullable', 'required_with:gols_visitante', 'integer', 'min:0', 'max:20'],
            'gols_visitante' => ['nullable', 'required_with:gols_mandante', 'integer', 'min:0', 'max:20'],
        ];
    }

    /**
     * Cada time pode disputar somente uma partida por rodada.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $partidaAtual = $this->route('partida');
                $partidaId = $partidaAtual instanceof Partida ? $partidaAtual->id : $partidaAtual;
                $times = [$this->integer('mandante_id'), $this->integer('visitante_id')];

                $conflito = Partida::query()
                    ->where('rodada', $this->integer('rodada'))
                    ->when($partidaId, fn ($query) => $query->where('id', '!=', $partidaId))
                    ->where(function ($query) use ($times) {
                        $query->whereIn('mandante_id', $times)
                            ->orWhereIn('visitante_id', $times);
                    })
                    ->exists();

                if ($conflito) {
                    $validator->errors()->add('mandante_id', 'Um dos times já possui uma partida cadastrada nesta rodada.');
                }
            },
        ];
    }
}
