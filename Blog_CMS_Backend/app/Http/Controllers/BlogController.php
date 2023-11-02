<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BlogRepository;

class BlogController extends BaseController
{
    private $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        parent::__construct();

        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request) {}

    public function store(Request $request) {}

    public function updateById(Request $request, $id) {}

    public function deleteById(Request $request, $id) {}

    public function getById(Request $request, $id) {}

    public function customSearch(Request $request) {}
}
}
