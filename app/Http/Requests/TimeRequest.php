<?php

namespace App\Http\Requests;

use App\Models\Time;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimeRequest extends FormRequest
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
        $time = $this->route('time');
        $timeId = $time instanceof Time ? $time->id : $time;

        return [
            'nome' => ['required', 'string', 'min:3', 'max:100', Rule::unique('times', 'nome')->ignore($timeId)],
            'sigla' => ['required', 'string', 'size:3', Rule::unique('times', 'sigla')->ignore($timeId)],
            'cidade' => ['required', 'string', 'min:2', 'max:100'],
            'estado' => ['required', 'string', 'size:2'],
            'estadio' => ['required', 'string', 'min:3', 'max:100'],
            'cor_primaria' => ['required', 'regex:/^#[0-9A-F]{6}$/i'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nome' => trim((string) $this->nome),
            'sigla' => mb_strtoupper(trim((string) $this->sigla)),
            'cidade' => trim((string) $this->cidade),
            'estado' => mb_strtoupper(trim((string) $this->estado)),
            'estadio' => trim((string) $this->estadio),
            'cor_primaria' => mb_strtoupper(trim((string) $this->cor_primaria)),
        ]);
    }
}
