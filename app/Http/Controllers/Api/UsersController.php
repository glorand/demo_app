<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        return new UserCollection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }
}
