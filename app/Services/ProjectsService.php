<?php

namespace App\Services;

use App\Models\Project;
use Exception;

class ProjectsService
{
    /**
     * @throws Exception
     */
    public function create(array $data): Project
    {
        $model = new Project($data);
        if (!$model->save()) {
            throw new Exception(__('messages.error_on_save_entity', ['entity' => 'project']));
        }

        return $model;
    }
}
