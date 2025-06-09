<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'tech_stack' => ['required', 'array'],
            'tech_stack.*' => ['string'],
            'thumbnail' => ['nullable', 'image'],
            'live_demo_url' => ['nullable', 'url'],
            'github_url' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
