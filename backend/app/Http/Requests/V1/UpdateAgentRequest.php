<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method === 'PUT'){
            return [
                'name' => ['required', 'string', 'max:255'],
                'department' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
            ];
        }
        else{
            return [
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'department' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => ['sometimes','required', 'email', 'max:255'],
                'password' => ['sometimes','required', 'string', 'max:255'],
            ];
        }
    }
}
