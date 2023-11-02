<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DateTime;

class UserController extends BaseController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        
        $limit = $request->limit ?? 10;

        try{
            $users = $this->userRepository->all($page, $limit);

            $this->response['data'] = $users;

            $this->setResponseInfo('success', 'User queried  successfully!', '', '', '');
        }catch(Throwable $th){
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }        
        

        return response()->json($this->response, $this->httpStatus);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string',
            'password_confirmation' => 'required|string'
        ]);

        if($validator->fails()){

            
            $this->setResponseInfo('invalid', '', $validator->errors()->toArray(), '', '');
            return response()->json($this->response, $this->httpStatus);
        }

        try{
            $validatedData = $validator->validated();
            data_forget($validatedData, 'role');
            $validatedData['password'] = Hash::make($validatedData['password']);

            $user = $this->userRepository->create($validatedData);
            $user->assignRole($request->role);

            $accessToken = $user->createToken('access_token', [config('sanctum.token_ability.access_api')], new DateTime(config('sanctum.expiration'). " minutes"))->plainTextToken;
            $refreshToken = $user->createToken('refresh_token', [config('sanctum.token_ability.issue_access_token')], new DateTime(config('sanctum.rt_expiration'). " minutes"))->plainTextToken;

            $this->response['data'] =  [
                "user" => $user,
                "token_type" => 'bearer',
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "access_token_expiration" => config('sanctum.expiration')." minutes",
                "refresh_token_expiration" => config('sanctum.rt_expiration')." minutes"
            ];




            $this->setResponseInfo('success', 'Subscriber has been registered successfully!', '', '', '');

        }catch(Throwable $th){
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    public function showUserById($id)
    {

        if(!$id){
            $this->setResponseInfo('invalid', 'please provide id', '', '', '');
            return response()->json($this->response, $this->httpStatus);
        }   

        try{
            $user = $this->userRepository->show($id);

            if($user){
                $this->response['data'] = $user;
                $this->setResponseInfo('success', 'User can be found successfully!', '', '', '');

            }
            else{
                $this->response['data'] = null;
                $this->setResponseInfo('no data', '', '', 'User cannot be found', '');

            }
        }catch(Throwable $th){
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    public function updateUserById(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'role' => 'required|string|exists:roles,name',
            'password' => 'required|string',
            'password_confirmation' => 'required|string'
        ]);

        if($validator->fails()){
            $this->setResponseInfo('invalid', '', $validator->errors()->toArray(), '', '');
            return response()->json($this->response, $this->httpStatus);
        }

        if(!$id){
            $this->setResponseInfo('invalid', 'please provide id', '', '', '');
            return response()->json($this->response, $this->httpStatus);
        }   

        try{

            $validatedData = $validator->validated();
            
            $validatedData['password'] = Hash::make($validatedData['password']);


            $status = $this->userRepository->updateById($validatedData, $id);

            if($status){
                
                $this->setResponseInfo('success', 'User has been updated successfully!', '', '', '');
            }
            else{
                $this->setResponseInfo('fail', '', '', '', 'User cannot be updated ! Try later');
            }
            

        }catch(Throwable $th)
        {
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);


    }

    public function deleteById($id)
    {
        if(!$id){
            $this->setResponseInfo('invalid', '', 'please provide id', '', '');
            return response()->json($this->response, $this->httpStatus);
        }   

        try{

            $status = $this->userRepository->deleteById($id);

            if($status){
                $this->setResponseInfo('success', 'User has been deleted successfully!', '', '', '');
            }
            else{
                $this->setResponseInfo('fail', '', '', '', 'User cannot be deleted ! Try later');
            }

        }catch(Throwable $th){
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    public function searchByFields(Request $request)
    {
        try{

            $users = $this->userRepository->getBySearchFields($request->all());

            if($users){
                $this->response['data'] = $users;
                $this->setResponseInfo('success', 'Users can be serached successfully!', '', '', '');
            }
            else{
                $this->setResponseInfo('fail', '', '', '', 'User cannot be searched ! Try later');
            }

        }catch(Throwable $th){
            Log::error("Cannot query user detail => ". $th->getMessage());
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

}
