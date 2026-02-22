<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserInformationRequest extends FormRequest
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
            'grade_id' => 'sometimes|required|exists:grades,id',
            'difficulty_id' => 'sometimes|required|exists:difficulties,id',
            'child_name' => 'sometimes|required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_id.required' => 'Grade ID harus diisi',
            'grade_id.exists' => 'Grade ID tidak valid',
            'difficulty_id.required' => 'Difficulty ID harus diisi',
            'difficulty_id.exists' => 'Difficulty ID tidak valid',
            'child_name.required' => 'Nama anak harus diisi',
            'child_name.string' => 'Nama anak harus berupa string',
            'child_name.max' => 'Nama anak maksimal 255 karakter',
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
