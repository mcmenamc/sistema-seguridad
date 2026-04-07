<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'required|exists:devices,id',
            'type' => 'required|string|max:255',
            'status' => 'required|in:pendiente,en proceso,resuelto',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'El dispositivo es obligatorio para registrar la incidencia.',
            'device_id.exists' => 'El dispositivo seleccionado no existe en el inventario.',
            'type.required' => 'Debes especificar el tipo de anomalía detectada.',
            'status.in' => 'El estado seleccionado no es un protocolo válido.',
            'description.required' => 'La descripción forense del evento es obligatoria.',
        ];
    }
}
