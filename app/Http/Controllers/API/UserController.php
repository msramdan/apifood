<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            // 1. buat validasi
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
                ]);
            // 2. jika validasi gagal
            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Validasi Gagal'
                ],$validator->errors(), 500);
            }else{ //jika validasi benar
                // ambil data user dengan email inputan
                $user = User::where('email', $request->email)->first();
                //cek password
                if ( ! Hash::check($request->password, $user->password, [])) {
                    throw new \Exception('Invalid Credentials');
                }else{
                    // create token
                    $tokenResult = $user->createToken('authToken')->plainTextToken;
                    return ResponseFormatter::success([
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                        'user' => $user
                    ],'Login Berhasil');
                }
            }
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // 1. buat validasi
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules() //fungsi bawaah laravel dengan use dlu PasswordValidationRules;
            ]);

            // 2. jika validasi gagal
            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Validasi Gagal'
                ],$validator->errors(), 500);
            }else{
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'houseNumber' => $request->houseNumber,
                    'phoneNumber' => $request->phoneNumber,
                    'city' => $request->city,
                    'password' => Hash::make($request->password),
                ]);
                $user = User::where('email', $request->email)->first();
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                return ResponseFormatter::success([
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user
                ],'Registrasi user berhasil');

            }
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function logout(Request $request){
        $removeToken = $request->user()->currentAccessToken()->delete();
        if($removeToken) {
            return ResponseFormatter::success($removeToken,'Logout Success!');
        }
    }

    // ambil data user terlogin
    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(),'Data profile user berhasil diambil');
    }

    //update data user terlogin
    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->update($data);
        return ResponseFormatter::success($data,'Profile Updated');
    }





}
