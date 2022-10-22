<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Arr;

class UsersService
{
    /**
     * @throws Exception
     */
    public function create(array $data): User
    {
        $model = new User(Arr::only($data, ['name', 'role_id']));
        if (!$model->save()) {
            throw new Exception(__('messages.error_on_save_entity', ['entity' => 'user']));
        }

        return $model;
    }
}
