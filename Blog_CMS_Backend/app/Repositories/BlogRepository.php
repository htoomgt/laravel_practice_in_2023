<?php

namespace App\Repositories;

use App\Models\Blog;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Interfaces\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    public function getAll($page, $limit, $search)
    {
        $query = Blog::with(['user']);


        foreach($search as $key => $value){

            if(Schema::table('blogs', $key)){
                $query->where($key, 'like', "%".$value."%");
            }

        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return Blog::create($data);
    }

    public function update(array $data, int $id)
    {
        $record = Blog::find($id);
        return $record->update($data);
    }

    public function deleteById(int $id)
    {
        return Blog::destroy($id);
    }

    public function showById(int $id)
    {
        return Blog::find($id);
    }
}
