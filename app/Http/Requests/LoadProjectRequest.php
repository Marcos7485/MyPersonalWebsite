<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoadProjectRequest extends FormRequest
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
            'code' => ['required', 'exists:projects,hash']
        ];
    }

     /**
     * Customize the error messages for the defined validation rules.
     *
     * @return array<string, string>
     **/
    public function messages(): array
    {
        return [
            'code.required' => 'El código del proyecto es obligatorio.',
            'code.exists'   => 'El código ingresado no coincide con ningún proyecto en nuestra base de datos.',
        ];
    }
}
