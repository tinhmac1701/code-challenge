<?php

namespace Modules\Loan\Repositories;
use Modules\Common\Repositories\BaseRepository;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Transformers\LoanCollection;
use Modules\Loan\Transformers\LoanResource;

class LoanRepository extends BaseRepository
{
        /**
     * @var Loan
     */
    protected $model;

        /**
     * BaseRepository constructor.
     *
     * @param Loan $model
     */
    public function __construct(Loan $model)
    {
        $this->model = $model;
    }

    /**
     * Store New Item
     *
     * @param array $attributes
     * @return object Stored Item
     */
    public function store(array $attributes)
    {
        return new LoanResource(parent::store($attributes));
    }

    /**
     * Get Item Details By Id
     *
     * @param int $id
     * @return object Get Item
     */
    public function getById(int $id)
    {
        return new LoanResource(parent::getById($id));
    }

    /**
     * Get All Item Details By Id
     *
     * @return object Get All Items
     */
    public function list()
    {
        return new LoanCollection(parent::list());
    }

        /**
     * Update Item By Id and Attributes
     *
     * @param int $id
     * @param array $attributes
     * @return object Updated Item Information
     */
    public function update(int $id, array $attributes)
    {
        return new LoanResource(parent::update($id, $attributes));
    }
}