<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSubscriptionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
            'duration' => 'required|integer|min:1',
            'details' => 'required|array|min:1',
            'details.*.feature' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama maksimal 255 karakter',
            'amount.required' => 'Jumlah harus diisi',
            'amount.numeric' => 'Jumlah harus berupa angka',
            'discount.numeric' => 'Diskon harus berupa angka',
            'discount.min' => 'Diskon minimal 0%',
            'discount.max' => 'Diskon maksimal 100%',
            'duration.required' => 'Durasi harus diisi',
            'duration.integer' => 'Durasi harus berupa angka bulat',
            'duration.min' => 'Durasi minimal 1 bulan',
            'details.required' => 'Detail harus diisi',
            'details.array' => 'Detail harus berupa array',
            'details.min' => 'Detail minimal 1 item',
            'details.*.feature.required' => 'Fitur detail harus diisi',
            'details.*.feature.string' => 'Fitur detail harus berupa string',
            'details.*.feature.max' => 'Fitur detail maksimal 255 karakter',
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
