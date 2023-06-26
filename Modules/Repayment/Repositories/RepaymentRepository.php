<?php

namespace Modules\Repayment\Repositories;
use Modules\Common\Repositories\BaseRepository;
use Modules\Loan\Repositories\LoanRepository;
use Modules\Repayment\Entities\Repayment;
use Modules\Repayment\Transformers\RepaymentResource;

class RepaymentRepository extends BaseRepository
{
    /**
     * @var Repayment
     */
    protected $model;

    protected $loanRepository = '';

    /**
     * BaseRepository constructor.
     *
     * @param Repayment $model
     */
    public function __construct(Repayment $model)
    {
        $this->model = $model;
        $this->loanRepository = app(LoanRepository::class);
    }

    /**
     * Store New Item
     *
     * @param array $attributes
     * @return object Stored Item
     */
    public function store(array $attributes)
    {
        return new RepaymentResource(parent::store($attributes));
    }

    private function getPreviousRepaymentProcess($loanId)
    {
        $previousRepayment = $this->model->where('loan_id', $loanId)
            ->orderBy('id', 'DESC')
            ->first();

        return $previousRepayment;
    }

    public function getNewestWeek($loanId)
    {
        $previousRepayment = $this->getPreviousRepaymentProcess($loanId);
        return ($previousRepayment) ? $previousRepayment->week + 1 : 1;
    }

    public function getLastestAmount($loanId)
    {
        $ownerLoan = $this->loanRepository->getById($loanId);
        $sumAmount = $this->sumAmount($loanId);
        return $ownerLoan->amount - $sumAmount;
    }

    public function sumAmount($loanId)
    {
        $repaymentCollection = $this->getMutilByCondtions(['loan_id' => $loanId]);
        return $repaymentCollection->sum('amount');
    }

    // /**
    //  * Get Item Details By Id
    //  *
    //  * @param int $id
    //  * @return object Get Item
    //  */
    // public function getById(int $id)
    // {
    //     return new LoanResource(parent::getById($id));
    // }

    // /**
    //  * Get All Item Details By Id
    //  *
    //  * @return object Get All Items
    //  */
    // public function list()
    // {
    //     return new LoanCollection(parent::list());
    // }

    //     /**
    //  * Update Item By Id and Attributes
    //  *
    //  * @param int $id
    //  * @param array $attributes
    //  * @return object Updated Item Information
    //  */
    // public function update(int $id, array $attributes)
    // {
    //     return new LoanResource(parent::update($id, $attributes));
    // }
}