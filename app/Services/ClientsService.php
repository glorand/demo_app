<?php

namespace App\Services;

use App\Models\Client;
use Exception;

class ClientsService
{
    /**
     * @throws Exception
     */
    public function create(array $data): Client
    {
        $model = new Client($data);
        if (!$model->save()) {
            throw new Exception(__('messages.error_on_save_entity', ['entity' => 'client']));
        }

        return $model;
    }
}
