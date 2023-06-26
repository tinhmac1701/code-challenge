<?php

namespace Modules\Loan\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Loan\Repositories\LoanRepository;

class OwnerLoanRule implements Rule
{
    private $_loanRepository = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $ownerLoan = $this->_loanRepository->getOneByCondtions(['user_id' => auth()->user()->id]);
        return auth()->user()->isAdmin() || $ownerLoan->id == $value;
    }

    /**
     * Get the validation error message
     *
     * @return string
     */
    public function message()
    {
        return 'You only can update your owner loan.';
    }
}
