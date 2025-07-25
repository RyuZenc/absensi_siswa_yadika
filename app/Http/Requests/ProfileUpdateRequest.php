<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];

        if ($this->user()->role == 'admin') {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        if (in_array($this->user()->role, ['admin', 'guru'])) {
            $rules['username'] = ['required', 'string', 'max:255', 'alpha_dash', Rule::unique(User::class)->ignore($this->user()->id)];
        }

        return $rules;
    }
}
