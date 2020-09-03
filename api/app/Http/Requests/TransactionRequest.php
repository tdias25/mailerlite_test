<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => ['required'], //could have used exists:accounts,id, but kind of more coupled to laravel?
            'amount' => ['required', 'between:0,9999.99'],
            'details' => ['required', 'max:255'],
        ];
    }
}
