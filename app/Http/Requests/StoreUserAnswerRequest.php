<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserAnswerRequest extends FormRequest
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
            'answers' => 'required|array|min:10',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Jawaban harus diisi',
            'answers.array' => 'Jawaban harus berupa array',
            'answers.min' => 'Jawaban minimal 10 item',
            'answers.*.question_id.required' => 'ID pertanyaan harus diisi',
            'answers.*.question_id.exists' => 'ID pertanyaan tidak valid',
            'answers.*.answer.required' => 'Jawaban harus diisi',
            'answers.*.answer.string' => 'Jawaban harus berupa string',
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
