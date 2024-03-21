<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $location): bool
    {
//        dd($user->id, $location->user_id);
        return $user->id == $location->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $location): bool
    {
        return $user->id == $location->user_id;
    }

}
