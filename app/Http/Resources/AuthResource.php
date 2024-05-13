<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this['user']['id'],
                'name' => $this['user']['name'],
                'email' => $this['user']['email'],
                'role' =>  $this['user']['role'],
            ],
            'access_token' => $this['access_token'],
        ];
    }
}
