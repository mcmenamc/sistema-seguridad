<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'required|exists:devices,id',
            'type' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'exists' => 'El :attribute seleccionado no es válido.',
            'max' => 'El :attribute no puede exceder los :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'device_id' => 'dispositivo',
            'type' => 'tipo de evento',
        ];
    }
}
