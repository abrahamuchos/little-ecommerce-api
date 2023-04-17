<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $address
 * @property int $role
 */
class RegisterUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'name' => 'required|min:1|max:65',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:100|confirmed',
            'address' => 'required|string|min:5|max:255',
            'role' => 'required|numeric'
        ];
    }
}
