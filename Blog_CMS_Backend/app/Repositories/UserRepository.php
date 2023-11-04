<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all($page, $limit, $search)
    {
        $query =  User::with(['roles']);

        foreach($search as $key => $value){

            if(Schema::table('users', $key)){
                $query->where($key, 'like', "%".$value."%");
            }

        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function updateById(array $data, int $id)
    {
        $user = User::find($id);

        $role = $data['role'];
        data_forget($data, 'role');
        $user->syncRoles([$role]);

        return $user->update($data);
    }

    public function deleteById(int $id)
    {
        return User::destroy($id);
    }

    public function show(int $id)
    {
        return User::with(['roles'])->find($id);
    }

    public function getBySearchFields($searchData)
    {
        return User::with(['roles'])->where($searchData)->get();
    }

}
