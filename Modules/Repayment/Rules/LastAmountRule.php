<?php

namespace Modules\Repayment\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Repayment\Entities\Repayment;
use Modules\Loan\Repositories\LoanRepository;
use Modules\Repayment\Repositories\RepaymentRepository;

class LastAmountRule implements Rule
{
    private $_loanId = '';
    private $_loanRepository = '';
    private $_repaymentRepository = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($loanId)
    {
        $this->_loanId = $loanId;
        $this->_loanRepository = app(LoanRepository::class);
        $this->_repaymentRepository = app(RepaymentRepository::class);
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
        if( $this->_repaymentRepository->getNewestWeek($this->_loanId) == Repayment::LASTEST_WEEK ) {
            return $value == $this->_repaymentRepository->getLastestAmount($this->_loanId);
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The lastest amount is incorrect !';
    }
}
