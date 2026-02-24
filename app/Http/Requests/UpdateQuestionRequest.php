<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateQuestionRequest extends FormRequest
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
            'content' => 'sometimes|required|string',
            'image_url' => 'nullable|url',
            'answer' => 'sometimes|required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_id.required' => 'Grade ID harus diisi',
            'grade_id.exists' => 'Grade ID tidak valid',
            'difficulty_id.required' => 'Difficulty ID harus diisi',
            'difficulty_id.exists' => 'Difficulty ID tidak valid',
            'content.required' => 'Konten pertanyaan harus diisi',
            'content.string' => 'Konten pertanyaan harus berupa string',
            'image_url.url' => 'URL gambar tidak valid',
            'answer.required' => 'Jawaban harus diisi',
            'answer.string' => 'Jawaban harus berupa string',
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
