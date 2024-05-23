<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /*
        $user = $this->user();

        return $user != null && $user->tokenCan('create');
        */
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
            '*.workerId' => ['required', 'integer'],
            '*.department' => ['required', 'string'],
            '*.message' => ['required', 'string'],
            '*.status' => ['string', Rule::in(['pending', 'opened', 'closed'])],
            '*.priority' => ['string', Rule::in(['low', 'medium', 'high'])],
            '*.openedAt' => ['date'],
            '*.closedAt' => ['date'],
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
