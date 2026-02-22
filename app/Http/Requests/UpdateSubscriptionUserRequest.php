<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubscriptionUserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'subscription_id' => 'sometimes|required|exists:subscriptions,id',
            'amount' => 'sometimes|required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
            'duration' => 'sometimes|required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'subscription_id.required' => 'Subscription ID harus diisi',
            'subscription_id.exists' => 'Subscription ID tidak valid',
            'amount.required' => 'Amount harus diisi',
            'amount.numeric' => 'Amount harus berupa angka',
            'discount.numeric' => 'Discount harus berupa angka',
            'discount.min' => 'Discount minimal 0%',
            'discount.max' => 'Discount maksimal 100%',
            'duration.required' => 'Duration harus diisi',
            'duration.integer' => 'Duration harus berupa integer',
            'duration.min' => 'Duration minimal 1 bulan',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
