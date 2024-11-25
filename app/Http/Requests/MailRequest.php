<?php

namespace App\Http\Requests;

use App\Rules\UsernameRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->check()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => ['required', new UsernameRules($this->email)],
            'email' => 'required|email',
            'location' => 'required',
            'question' => 'required|min:20'
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'The :attribute is required.',
            'last_name.required' => 'The :attribute is required.',
            'username.required' => 'The :attribute is required.',
            'email.required' => 'The :attribute is required.',
            'email.email' => 'The :attribute should contain valid email.',
            'location.required' => 'The :attribute is required.',
            'question.required' => 'The :attribute is required.',
            'question.min' => 'The :attribute must be atleast 20 characters.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => 0, 
            'msg' => 'Validation Failed',
            'errors' => $this->sendArrayMessage($validator->errors())
        ])); 
    }

    public function sendArrayMessage($validation): array
    {
        $returnData = [];
        $validation = $validation->toArray();
        foreach ($validation as $key => $value) {
            $returnData[$key] = $value[0];
        }
        return $returnData;
    }


}
