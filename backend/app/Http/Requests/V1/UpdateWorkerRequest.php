<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('create');
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
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'position' => ['required', 'string', 'max:255'],
            ];
        }
        else{
            return [
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => ['sometimes', 'required', 'email', 'max:255'],
                'password' => ['somethimes', 'required', 'string', 'max:255'],
                'phone' => ['sometimes', 'required', 'string', 'max:255'],
                'position' => ['sometimes', 'required', 'string', 'max:255'],
            ];
        }

    }
}
