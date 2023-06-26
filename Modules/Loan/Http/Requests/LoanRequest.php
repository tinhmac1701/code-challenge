<?php

namespace Modules\Loan\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Common\Rules\AmountRule;
use Modules\Common\Traits\FailedValidationTrait;
use Modules\Common\Traits\ResponseTrait;
use Modules\Loan\Entities\Loan;

class LoanRequest extends FormRequest
{
    use ResponseTrait;
    use FailedValidationTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return match($this->method()){
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update(),
            'DELETE' => $this->destroy(),
        };
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
    
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            'user_id' => 'required|exists:users,id',
            "amount" => [
                'required',
                new AmountRule()
            ],
            "terms" => 'required|integer'
        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        $aStatus = array_keys(Loan::STATUS);
        $start = current($aStatus);
        $end = end($aStatus);

        $rules = [];

        switch (auth()->user()->isAdmin()) {
            case User::ADMIN:
                $rules = [
                    "amount" => [
                        'sometimes',
                        new AmountRule()
                    ],
                    "terms" => 'sometimes|integer',
                    "status" => 'required|integer|between:'.$start.','.$end
                ];
                break;
            
            default:
                $rules = [
                    "amount" => [
                        'required',
                        new AmountRule()
                    ],
                    "terms" => 'required|integer',
                ];
                break;
        }
        return $rules;
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        return [
            //
        ];
    }
}
