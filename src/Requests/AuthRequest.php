<?php

namespace Hos3ein\NovelAuth\Requests;

use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return NovelAuth::validationRules();
    }
}
