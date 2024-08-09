<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreAgentRequest extends FormRequest
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
        return [
            '*.name' => ['required', 'string'],
            '*.department' => ['required', 'string', Rule::in(['IT', 'HR', 'Finance', 'Marketing'])],
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];
        foreach ($this->toArray() as $obj){
            $obj['worker_id'] = $obj['workerId'] ?? null;
            $obj['opened_at'] = $obj['openedId'] ?? null;
            $obj['closed_at'] = $obj['closedId'] ?? null;
            $obj['status'] = $obj['status'] ?? 'pending';
            $obj['priority'] = $obj['priority'] ?? 'low';

            $data[] = $obj;
        }
        $this->merge($data);

    }
}
