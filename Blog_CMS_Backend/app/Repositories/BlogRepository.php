<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    public function all()
    {
        return Blog::all();
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

    public function delete(int $id)
    {
        return Blog::destroy($id);
    }

    public function show(int $id)
    {
        return Blog::find($id);
    }
}
