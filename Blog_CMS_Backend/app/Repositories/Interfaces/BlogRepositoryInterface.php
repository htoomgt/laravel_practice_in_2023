<?php

namespace App\Repositories\Interfaces;

interface BlogRepositoryInterface
{
    public function getAll($page, $limit, $search);

    public function create(array $data);

    public function update(array $data, int $id);

    public function deleteById(int $id);

    public function showById(int $id);
}
