<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:400',
            ],
            'user_id' => [
                'required',
                'uuid',
                'exists:users,id',
            ],
        ];
    }
}
