<?php

namespace Modules\Repayment\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Loan\Repositories\LoanRepository;

class ScheduledAmountRule implements Rule
{
    private $_loanId = '';
    private $_loanRepository = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($loanId)
    {
        $this->_loanId = $loanId;
        $this->_loanRepository = app(LoanRepository::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ownerLoan = $this->_loanRepository->getById($this->_loanId);
        return ( $value < $ownerLoan->scheduled_amount || $value > $ownerLoan->amount ) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The amount should be larger than the scheduled amount and less than loan amount.';
    }
}
