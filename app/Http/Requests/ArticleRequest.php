<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        return [
            'category_id'  => 'nullable|exists:categories,id',
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string',
            'content'      => 'required|string',
            'status'       => 'required|in:draft,published',
            'thumbnail'    => 'nullable|image|max:2048',
            'tags'         => 'nullable|array',
            'tags.*'       => 'exists:tags,id',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'kategori',
            'title'       => 'judul',
            'excerpt'     => 'ringkasan',
            'content'     => 'konten',
            'status'      => 'status',
            'thumbnail'   => 'gambar thumbnail',
            'tags'        => 'tag',
        ];
    }

    public function messages()
    {
        return [
            'title.required'   => 'Judul artikel wajib diisi.',
            'content.required' => 'Konten artikel wajib diisi.',
            'status.in'        => 'Status harus bernilai draft atau published.',
            'thumbnail.image'  => 'Thumbnail harus berupa file gambar.',
            'tags.*.exists'    => 'Tag yang dipilih tidak valid.',
        ];
    }
}
