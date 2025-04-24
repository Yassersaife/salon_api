<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)  {
        $validator= validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if($validator->fails()){

            return ApiResponse::sendResponse(422, 'login Validation Errors', $validator->messages()->all());
            
                 }

                 if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $user = Auth::user();
                    $data['token'] = $user->createToken('MobileApp')->plainTextToken;
                    $data['name'] = $user->name;
                    $data['email'] =  $user->email;
                    return ApiResponse::sendResponse(200, 'User login successfully', $data);
            }
            else {
                return ApiResponse::sendResponse(401, 'Unauthorized', null);
            }

        }
    public function register(Request $request){
        $validater=validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:20'
        ]);
          if($validater->fails()){
            return ApiResponse::sendResponse(422, 'Register Validation Errors', $validater->messages()->all());
          }
          
          $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
          ]);
          $data['token'] = $user->createToken('MobileApp')->plainTextToken;
          $data['name']= $user->name;
          $data['email']= $user->email;
            return ApiResponse::sendResponse(200, 'User register successfully', $data);

          

        }

       public function logout(Request $request){
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponse::sendResponse(200, 'User logout successfully', null);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'Internal Server Error', null);
        }
    }

    public function getUser(Request $request){
        try {
            $user = $request->user();
            $data=$user->only(['id', 'name', 'email']);
           return ApiResponse::sendResponse(200, 'User data retrieved successfully', $data);
                         

        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'Internal Server Error', null);
        }

    }

    public function updataUser(Request $request){
        try{
            $validator=Validator::make($request->all(),[
                'name'=>'required',
                'email'=>'required|email|unique:users,email,'.$request->user()->id,
                'password'=>'nullable|min:6|max:20'
            ]);
            if($validator->fails()){
                return ApiResponse::sendResponse(422, 'Update Validation Errors', $validator->messages()->all());
            }
            $data = $request->only('name', 'email');
            
            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password);
            }     
            $user = $request->user();
            $user->update($data);
            return ApiResponse::sendResponse(200, 'User data updated successfully', $user->only(['id', 'name', 'email']));
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'Internal Server Error', $e->getMessage());
           }
    }

    public function deleteUser(Request $request){
        try {
           $user=$request->user();
              $user->delete();

        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'Internal Server Error', null);
        }

    }
}





    


              

