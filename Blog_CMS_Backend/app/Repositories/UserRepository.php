<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, int $id)
    {
        $record = User::find($id);
        return $record->update($data);
    }

    public function delete(int $id)
    {
        return User::destroy($id);
    }

    public function show(int $id)
    {
        return User::find($id);
    }

    public function getBySearchFields($searchData)
    {
        return User::where($searchData)->get();
    }

}
