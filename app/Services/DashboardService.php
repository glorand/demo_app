<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(): array
    {
        $query = "
        SELECT
            tasks.id as task_id,
            tasks.name as task_name,
            projects.name as project_name,
            clients.name as client_name,
            tu.users as users,
            tu.roles as roles
        FROM tasks
        LEFT JOIN projects ON projects.id = tasks.project_id
        LEFT JOIN clients ON clients.id = projects.client_id
        LEFT JOIN (
            SELECT group_concat(DISTINCT users.name SEPARATOR ', ') as users, group_concat(DISTINCT roles.name SEPARATOR ', ') as roles, task_user.task_id from task_user
            left join users on task_user.user_id = users.id
            left join roles on users.role_id = roles.id
            group by task_user.task_id
        ) as tu on tu.task_id = tasks.id;
        ";
        return DB::select($query);
    }
}
