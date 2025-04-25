<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Helper\ApiResponse;
class StoreSalonRequest extends FormRequest
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

     protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $response = ApiResponse::sendResponse(422, 'Validation Errors', $validator->errors()->messages());
            throw new ValidationException($validator, $response);
        }
    }


    public function rules(): array
    {
        return [
            'name'=>'required|string|max:255',
            'location'=>'required|string|max:255',
            'phone'=>'required|string|max:255',
        ];
    }
}
