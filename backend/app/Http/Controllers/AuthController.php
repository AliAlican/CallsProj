<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Models\Client;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private $client;

    /**
     * DefaultController constructor.
     */
    public function __construct()
    {
        $this->client = Client::query()->where('id', 2)->first();
    }

    public function register(Request $request)
    {
        $data = $request->all('first_name', 'last_name', 'email', 'password');

        $valid = validator($data,
            [
                'first_name' => 'string|required',
                'last_name' => 'string|required',
                'password' => 'string|required',
                'email' => 'required|email:rfc,dns|unique:users,email'
            ]);
        if ($valid->fails()) {
            return response()->json($valid->errors()->all(), 412);
        }

        User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $request->request->add([
            'username' => $data['email'],
            'password' => $data['password'],
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'grant_type' => 'password',
            'scope' => null
        ]);
        // Fire off the internal request.
        $token = Request::create(
            'oauth/token',
            'POST'
        );
        return \Route::dispatch($token);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function authenticate(Request $request)
    {
        $data = $request->only('email', 'password');
        $valid = validator($data,
            [
                'password' => 'string|required',
                'email' => 'required|email|exists:users,email'
            ]);

        if ($valid->fails()) {
            return response()->json($valid->errors()->all(), 412);
        }

        $request->request->add([
            'grant_type' => 'password',
            'username' => $request->email,
            'password' => $request->password,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function refreshToken(Request $request)
    {
        $data = $request->only('refresh_token');
        $valid = validator($data,
            [
                'refresh_token' => 'string|required'
            ]);

        if ($valid->fails()) {
            return response()->json($valid->errors()->all(), 412);
        }

        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }
}
