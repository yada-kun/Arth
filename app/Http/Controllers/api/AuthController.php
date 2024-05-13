<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HttpResponses;
    //
    public $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function login(Request $request){

      $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
      ]);

      $result = $this->authService->loginService($credentials);

      if(isset($result['error'])){
        return $this->error($result['error'], Response::HTTP_FORBIDDEN);
      }

      return $this->success(AuthResource::make($result), "Login Successful", Response::HTTP_OK)->withCookie('refresh_token', $result['refresh_token'], 1440, '/', null, true, true);
    }

    public function refresh(Request $request){
        // get refresh token from the cookie
        $refreshToken = $request->cookie('refresh_token');
        // get access token from the bearer
        $accessToken = $request->bearerToken();

        $result = $this->authService->refreshService($refreshToken, $accessToken);

        if (isset($result['error'])) {
            return $this->error($result['error'], Response::HTTP_UNAUTHORIZED)->withCookie(cookie()->forget('refresh_token'));;
            }

    return $this->success(AuthResource::make($result), "Successful Request", Response::HTTP_OK)->withCookie($refreshToken);

    }

    public function logout(Request $request){
        // removes access and refreshtoken then proceed in logging out in vue js SPA not inertia
    }

    public function createTokens(){
        // this should accept a parameter to determine what kind of token will be created. surprise me in this. accessTokens = 15mins expiry. refreshToken = 1 day. then returns the token
    }


}
