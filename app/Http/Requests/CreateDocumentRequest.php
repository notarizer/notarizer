<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Anyone can make a request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required|string|max:255',
            'sha256' => 'required|string|size:64|alpha_num',
            'size'   => 'required|integer|min:0|max:' . PHP_INT_MAX,
            'compare_to' => 'string|size:64|alpha_num'
        ];
    }
}
