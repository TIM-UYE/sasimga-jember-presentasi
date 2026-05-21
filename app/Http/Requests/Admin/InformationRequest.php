<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $informationId = $this->route('information')?->id;

        $rules = [
            'slug' => [
                'required',
                'string',
                'max:100',
                Rule::unique('information', 'slug')->ignore($informationId),
            ],
            'title' => 'required|string|max:255',
        ];

        // Add image validation for About page
        if ($this->slug === 'about') {
            $rules['image_file'] = [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096',
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'slug' => 'Tipe Halaman',
            'title' => 'Judul Halaman',
            'image_file' => 'Gambar',
        ];
    }

    public function messages(): array
    {
        return [
            'image_file.image' => 'File harus berupa gambar',
            'image_file.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'image_file.max' => 'Ukuran gambar maksimal 4MB',
        ];
    }
}
