<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|min:8|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'roles' => 'sometimes|required|in:admin,user,superadmin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.min' => 'Nama minimal 8 karakter',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Email harus berupa email yang valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak cocok dengan konfirmasi password',
            'roles.required' => 'Role harus diisi',
            'roles.in' => 'Role harus salah satu dari admin, user, superadmin',
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
