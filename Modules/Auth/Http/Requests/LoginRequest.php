<?php

namespace Modules\Auth\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Modules\Common\Traits\FailedValidationTrait;
use Modules\Common\Traits\ResponseTrait;

class LoginRequest extends FormRequest
{
    use ResponseTrait;
    use FailedValidationTrait;

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:50',
            'password' => 'required|min:6',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
