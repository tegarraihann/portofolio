<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('category')?->id ?? null;
        return [
            'name' => ['required', 'string', 'max:100', "unique:categories,name,$id"],
        ];
    }

    public function attributes()
    {
        return ['name' => 'nama kategori'];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Kategori sudah ada.',
        ];
    }
}
