<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = [
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'phone'           => ['required', 'string', 'digits:11', 'unique:users'],
            'document_number' => ['required', 'string', 'digits:10'],
            'password'        => ['required', 'string', 'min:8'],
        ];

        $data = $request->input();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
               'error' => [
                   'code'       => 422,
                   'message'    => "Validation error",
                   'errors'     => $validator->errors(),
               ],
            ], 422);
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json()->setStatusCode(204);
    }

    public function login(Request $request)
    {
        $rules = [
            'phone'           => ['required', 'string', 'digits:11'],
            'password'        => ['required', 'string', 'min:8'],
        ];

        $data = $request->input();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code'       => 422,
                    'message'    => "Validation error",
                    'errors'     => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::where('phone', $data['phone'])->first();

        $token = Str::random(60);
        $hashToken = hash('sha256', $token);

        if (Auth::validate($data)) {
            $user->forceFill([
                'api_token' => $hashToken,
            ])->save();

            return response()->json([
                'data' => [
                    'token' => $token,
                ],
            ], 200);
        }

        return response()->json([
            'error' => [
                'code'       => 401,
                'message'    => "Unauthorized",
                'errors'     => [
                    'phone'  => "phone or password incorrect",
                ],
            ],
        ], 401);
    }
}
