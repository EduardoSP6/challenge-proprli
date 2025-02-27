<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id' => [
                'required',
                'uuid',
                'exists:buildings,id',
            ],
            'created_by' => [
                'required',
                'uuid',
                'exists:users,id',
            ],
            'assigned_to' => [
                'required',
                'uuid',
                'exists:users,id',
            ],
            'title' => [
                'required',
                'string',
                'max:200',
            ],
            'description' => [
                'nullable',
                'string',
            ]
        ];
    }
}
