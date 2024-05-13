<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService {

    // login() service
    public function loginService(array $credentials, $tokenCookie = null): array{
        // this is the $reuqest->cookie('refresh_token) we check if there is a cookie theb we remove it.
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $accessToken = $this->newToken($user, 'access_token');
            $refreshToken = $this->newToken($user, 'refresh_token');

            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name'),
                ],
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ];
        }

        return [
            'error' => 'Invalid Credentials'
        ];
    }

    public function refreshService(?string $refreshToken, ?string $accessToken): array {


        $refresh_token = $this->getToken($refreshToken);
        $access_token = $this->getToken($accessToken);
        // check for refresh token from the cookie, and if user is authenticated. returns error if not.
        if(!$refresh_token || !$access_token){
            $this->deleteToken($access_token);
            $this->deleteAllToken($refresh_token);

            return [
                'error' => 'Invalid Access, Please sign in again'
            ];
        }

        $user = $this->getUserViaToken($access_token);

        // this code checks for token misuse. We compare the authenticated user to the user who ows the token.
        if($user->email !==  $refresh_token->tokenable->email){
            //we delete all the token of the user
            $this->deleteAllToken($refresh_token);

            return [
                'error' => 'Invalid Access, Please sign in again'
            ];
        }

        if ($refresh_token->expires_at && Carbon::parse($refresh_token->expires_at)->isPast()){

            $this->deleteAllToken($refresh_token);
            $this->deleteAllToken($access_token);

            return [
                'error' => 'Invalid token, please sign in again',
                ];
        }

        // delete current access token
        $access_token->delete();

        // creates a new token
        $accessToken = $this->newToken($user, 'access_token');

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name'),
            ],
            'access_token' => $accessToken,
        ];


    }

    public function logoutService(?string $refreshToken, $user){

    }









 /***************************Helper Functions*************************************/

    private function newToken(User $user, string $type):string {

        // assign token type and its expiry
        $expiryDate = $type == 'access_token' ? Carbon::now()->addMinutes(15) : Carbon::now()->addDay();

        $token = $user->createToken('auth', [$type], $expiryDate);

        return $token->plainTextToken;

    }

    private function getToken($token): ?PersonalAccessToken{
        return PersonalAccessToken::findToken($token);
    }

    private function deleteToken(?PersonalAccessToken $token):void {
        if($token){
            $token->delete();
        }
    }
    private function deleteAllToken(PersonalAccessToken $token): void{
        PersonalAccessToken::where('tokenable_id', $token->tokenable_id)->each( function ($query){
            $query->delete();
        });
    }

    private function getUserViaToken(?PersonalAccessToken $token): ?User  {
        if($token){
            $user = User::findOrFail($token->tokenable->id);
            return $user;
        }

    }
}
