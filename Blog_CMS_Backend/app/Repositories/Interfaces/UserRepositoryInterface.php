<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all($page, $limit);

    public function create(array $data);

    public function updateById(array $data, int $id);

    public function deleteById(int $id);

    public function show(int $id);

    public function getBySearchFields(array $searchData);
}
