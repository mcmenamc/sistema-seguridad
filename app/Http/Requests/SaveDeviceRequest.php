<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->device ? $this->device->id : null;

        return [
            'name' => 'required|string|max:255|unique:devices,name,' . $id,
            'device_type_id' => 'required|exists:device_types,id',
            'status' => 'required|in:activo,inactivo,alerta',
            'location' => 'required|string|max:255',
            'client_id' => 'nullable|exists:users,id',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'deleted_images' => 'nullable|array',
            'metadata' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya ha sido registrado por otro equipo.',
            'exists' => 'El :attribute seleccionado no existe en el sistema.',
            'image' => 'El archivo debe ser una imagen válida.',
            'max' => 'La imagen es demasiado pesada (máximo 2MB).',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'device_type_id' => 'tipo',
            'status' => 'estatus',
            'location' => 'ubicación',
            'client_id' => 'responsable',
            'new_images' => 'imágenes',
        ];
    }
}
