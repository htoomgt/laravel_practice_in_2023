<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }

}
