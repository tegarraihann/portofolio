<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $id = $this->route('tag')?->id ?? null;
        return [
            'name' => ['required', 'string', 'max:100', "unique:tags,name,$id"],
        ];
    }

    public function attributes()
    {
        return ['name' => 'nama tag'];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tag wajib diisi.',
            'name.unique'   => 'Tag sudah ada.',
        ];
    }
}
