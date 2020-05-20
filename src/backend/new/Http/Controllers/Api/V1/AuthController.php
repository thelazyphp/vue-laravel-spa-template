<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Hash;
use App\Company;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $client = Client::where('password_client', true)->first();

        if (!$client) {
            $data = [
                'status' => false,
                'message' => 'Password grant client is not found!',
            ];

            return response()->json($data, 500);
        }

        return app()->handle(
            Request::create(
                env('APP_URL').'/api/v1/oauth/token', 'POST', [
                    'grant_type' => 'password',
                    'scope' => '*',
                    'username' => $request->username,
                    'password' => $request->password,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                ]
            )
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        $data = [
            'status' => true,
            'message' => 'You are successfully logged out.',
        ];

        return response()->json($data);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'f_name' => 'required|string|max:191',
            'm_name' => 'nullable|string|max:191',
            'l_name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users',
            'company_name' => 'nullable|string|max:191|unique:companies,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $attributes = ['password' => Hash::make($request->password)];

        if ($request->filled('company_name')) {
            $company = Company::create(['name' => $request->company_name]);
            $company->settings = Company::DEFAULT_SETTINGS;
            $company->save();

            $attributes['company_id'] = $company->id;
        }

        $user = User::create($request->only([
            'f_name',
            'm_name',
            'l_name',
            'email',
        ]) + $attributes);

        $user->settings = User::DEFAULT_SETTINGS;
        $user->save();

        $data = [
            'status' => true,
            'message' => 'You are successfully registered.',
        ];

        return response()->json($data);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|string',
        ]);

        $client = Client::where('password_client', true)->first();

        if (!$client) {
            $data = [
                'status' => false,
                'message' => 'Password grant client is not found!',
            ];

            return response()->json($data, 500);
        }

        return app()->handle(
            Request::create(
                env('APP_URL').'/api/v1/oauth/token', 'POST', [
                    'grant_type' => 'refresh_token',
                    'scope' => '*',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                ]
            )
        );
    }
}
