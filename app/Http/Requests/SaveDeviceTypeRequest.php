<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDeviceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('device_type') ? $this->route('device_type')->id : null;

        return [
            'name' => 'required|string|max:255|unique:device_types,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'Ya existe un registro con este :attribute en el catálogo.',
            'string' => 'El :attribute debe ser una cadena de texto válida.',
            'max' => 'El :attribute no puede exceder los :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del tipo',
        ];
    }
}
