<?php

namespace Modules\Common\Repositories;
use Illuminate\Database\Eloquent\Model;

Class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
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
        return $this->model->create($attributes)->fresh();
    }

    /**
     * Delete Item By Id
     *
     * @param int $id
     * @return object Deleted Item
     */
    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Get Item Details By Id
     *
     * @param int $id
     * @return object Get Item
     */
    public function getById(int $id)
    {
        return $this->model->find($id)->fresh();
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
        $model = $this->model->find($id);
        $attributes['updated_at'] = now();
        $model->update($attributes);
        return $model;
    }

     /**
     * Get All Item Details By Id
     *
     * @return object Get All Items
     */
    public function list()
    {
        return $this->model->all();
    }

    /**
     * Get a Item Details By multi condition
     *
     * @param array $condition
     * @return object Get Item
     */
    public function getOneByCondtions(array $condition)
    {
        return $this->model->where($condition)->first();
    }

    /**
     * Get List Item Details By multi condition
     *
     * @param array $condition
     * @return object Get Items
     */
    public function getMutilByCondtions(array $condition)
    {
        return $this->model->where($condition)->get();
    }
}