<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int             $id
 * @property string          $name
 * @property string          $email
 * @property \App\Models\User $roles
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->whenLoaded('roles', function () {
                return [
                    'role_id' => $this->roles->first()->pivot->role_id,
                    'role_name' => $this->roles->first()->name,
                ];
            })
        ];
    }
}
