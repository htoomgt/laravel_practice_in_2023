<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends BaseController
{

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }


    /**
     * To register the subscriber from public API
     * @author Htoo Maung Thait
     * @since 2023-11-02
     * @param Request $request
     * @return JSON
     *
     */
    public function subscriberRegister(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string'
        ]);

        if($validator->fails()){
            $this->setResponseInfo('invalid', $validator->errors(), '', '', '');
            return response()->json($this->response, $this->httpStatus);
        }

        try{
            $validatedData = $validator->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);

            $user = $this->userRepository->create($validatedData);
            $user->assignRole('subscriber');

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
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }




        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * To login from the public API
     * @author Htoo Maung Thait
     * @since 2023-11-02
     * @param Request $request
     * @return JSON
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            $this->setResponseInfo('invalid', '', $validator->errors(), '', '');
            return response()->json($this->response, $this->httpStatus);
        }



        try{
            $user =  $this->userRepository->getBySearchFields(['email' => $request->email])->first();
            $user = $user->makeHidden(['created_at', 'updated_at']);

            foreach($user->roles as $role){
                unset($role->pivot);
            }
            
            

            if (!$user || !Hash::check($request['password'], $user->password)) {

                $this->setResponseInfo('unauthorized', '', '', '', 'email or passwords wrong');

                return response()->json($this->response, $this->httpStatus);

            }


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


            $this->setResponseInfo('success', 'User can login successfully!', '', '', '');
        }catch(Throwable $th){
            $this->setResponseInfo('error', '', [], '', $th->getMessage());
        }




        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * To logout from the authenticated API
     * @author Htoo Maung Thait
     * @since 2023-11-02
     * @param Request $request
     * @return JSON
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        $this->setResponseInfo('success', 'User logged out successfully!', '', '', '');

        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * To refresh from the authenticated API
     * @author Htoo Maung Thait
     * @since 2023-11-02
     * @param Request $request
     * @return JSON
     */
    public function refreshTokens()
    {
        auth()->user()->tokens()->delete();
        $user = auth()->user();

        try{


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


            $this->setResponseInfo('success', 'User can refresh tokens successfully!', '', '', '');

        }catch(Throwable $th){
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);

    }
}
