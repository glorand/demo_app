<?php

namespace App\Services;

use App\Models\Task;
use Exception;
use Illuminate\Support\Arr;

class TasksService
{
    /**
     * @throws Exception
     */
    public function create(array $data): Task
    {
        $model = new Task(Arr::only($data, ['name', 'project_id']));
        if (!$model->save()) {
            throw new Exception(__('messages.error_on_save_entity', ['entity' => 'task']));
        }

        if (Arr::has($data, 'users')) {
            $model->users()->attach($data['users']);
        }

        return $model;
    }
}
