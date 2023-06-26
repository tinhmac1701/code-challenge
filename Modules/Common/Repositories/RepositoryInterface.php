<?php

namespace Modules\Common\Repositories;

interface RepositoryInterface
{
    /**
     * Store New Item
     *
     * @param array $attributes
     * @return object Stored Item
     */
    public function store(array $attributes);

    /**
     * Delete Item By Id
     *
     * @param int $id
     * @return object Deleted Item
     */
    public function delete(int $id);

    /**
     * Get Item Details By Id
     *
     * @param int $id
     * @return object Get Item
     */
    public function getById(int $id);

    /**
     * Update Item By Id and Attributes
     *
     * @param int $id
     * @param array $attributes
     * @return object Updated Item Information
     */
    public function update(int $id, array $attributes);

    /**
     * Get All Item Details By Id
     *
     * @return object Get All Items
     */
    public function list();

    /**
     * Get Item Details By multi condition
     *
     * @param array $condition
     * @return object Get Items
     */
    public function getOneByCondtions(array $condition);

    /**
     * Get Item Details By multi condition
     *
     * @param array $condition
     * @return object Get Items
     */
    public function getMutilByCondtions(array $condition);
}