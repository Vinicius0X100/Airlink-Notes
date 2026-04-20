<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Verify2faRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $otp = (string) $this->input('otp', '');
        $otp = preg_replace('/\D+/', '', $otp) ?? $otp;

        $challengeId = $this->input('challenge_id');
        if ($challengeId === '') {
            $challengeId = null;
        }

        $this->merge([
            'otp' => $otp,
            'challenge_id' => $challengeId,
        ]);
    }

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'digits_between:6,8'],
            'challenge_id' => ['nullable', 'uuid'],
        ];
    }
}
