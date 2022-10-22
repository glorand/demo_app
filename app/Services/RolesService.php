<?php

namespace App\Services;

use App\Models\Role;
use Exception;

class RolesService
{
    /**
     * @throws Exception
     */
    public function create(array $data): Role
    {
        $model = new Role($data);
        if (!$model->save()) {
            throw new Exception(__('messages.error_on_save_entity', ['entity' => 'role']));
        }

        return $model;
    }
}
