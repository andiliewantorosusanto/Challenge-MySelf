<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use Illuminate\Http\Response;

use App\Services\User\CreateUser;
use App\Services\User\AuthUser;
use App\Services\User\ChangeUserPassword;

class UserController extends Controller
{
    /**
     * Create a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => 'required|max:55',
            'email' => 'email|required',
            'password' => 'required|confirmed'
        ]);
        
        $result = CreateUser::execute($validatedData);
        
        return response($result,isset($result['errors']) ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        $result = AuthUser::execute($validatedData); 
        
        return response($result,isset($result['errors']) ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);
    }

    public function change(Request $request) 
    {
        $validatedData = $request->validate([
            'password' => 'required',
            'new_password' => 'required'
        ]);
        
        $result = ChangeUserPassword::execute($validatedData,$request->user());
        
        return response($result,isset($result['errors']) ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);
    }

    public function reset()
    {
        User::truncate();
    }
}
