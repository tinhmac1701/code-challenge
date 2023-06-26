<?php

namespace Modules\Repayment\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Common\Rules\AmountRule;
use Modules\Common\Traits\FailedValidationTrait;
use Modules\Common\Traits\ResponseTrait;
use Modules\Loan\Rules\OwnerLoanRule;
use Modules\Repayment\Entities\Repayment;
use Modules\Repayment\Rules\LastAmountRule;
use Modules\Repayment\Rules\ScheduledAmountRule;

class RepaymentRequest extends FormRequest
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
            'loan_id' => [
                'required',
                new OwnerLoanRule()
            ],
            "scheduled_date" => 'required|date_format:Y-m-d',
            "amount" => [
                'required',
                new AmountRule(),
                new ScheduledAmountRule($this->loan_id),
                new LastAmountRule($this->loan_id)
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        $aStatus = array_keys(Repayment::STATUS);
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
