<?php

namespace App\Policies;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicinePolicy
{
    public function view(User $user, Medicine $medicine)
    {
        return $user->role === 'admin' || $user->role === 'pharmacist';
    }

    // Check if the user can create a new medicine
    public function create(User $user, Medicine $medicine)
    {
        // return $user->role === $medicine->role;
        if($user->role === 'admin') {
            return true;
        }
        return false;
    }

    // Check if the user can update a medicine
    public function update(User $user, Medicine $medicine)
    {
        return $user->role === 'admin';
    }

    // Check if the user can delete a medicine
    public function delete(User $user, Medicine $medicine)
    {
        // return $user->role === 'admin';
        if ($user->role === 'admin') {
            return true;
        }
        return false;
    }
    
}