<?php

namespace App\Services;

use Illuminate\Support\Arr;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;

class ProcessTestFileService
{
    private ClientsService $clientsService;
    private ProjectsService $projectsService;
    private RolesService $rolesService;
    private UsersService $usersService;
    private TasksService $tasksService;
    private array $clientMap = [];
    private array $projectMap = [];
    private array $roleMap = [];
    private array $userMap = [];

    public function __construct(
        ClientsService $clientsService,
        ProjectsService $projectsService,
        RolesService $rolesService,
        UsersService $usersService,
        TasksService $tasksService
    )
    {
        $this->clientsService = $clientsService;
        $this->projectsService = $projectsService;
        $this->rolesService = $rolesService;
        $this->usersService = $usersService;
        $this->tasksService = $tasksService;
    }

    /**
     * @throws Exception
     */
    public function process(string $path): void
    {
        $csv = Reader::createFromPath($path, 'r')->setHeaderOffset(0);
        $records = Statement::create()->process($csv);
        $headers = ['client', 'project', 'task', 'assigned', 'roles'];
        $data = $this->buildData($records->getRecords($headers));

        $this->saveClients($data['clients']);
        $this->saveProjects($data['projects']);
        $this->saveRoles($data['roles']);
        $this->saveUsers($data['users']);
        $this->saveTasks($data['tasks']);
    }

    /**
     * @param array $records
     * @return array{clients: array, projects: array, roles: array, users: array, tasks: array}
     */
    private function buildData(iterable $records): array
    {
        $data = [
            'clients' => [],
            'projects' => [],
            'roles' => [],
            'users' => [],
            'tasks' => [],
        ];

        foreach ($records as $record) {
            $data['clients'][md5($record['client'])] = $record['client'];
            $data['projects'][md5($record['project'])] = [
                'name' => $record['project'],
                'client' => md5($record['client']),
            ];
            $tmpRoles = explode(',', $record['roles']);
            foreach ($tmpRoles as $tmpRole) {
                $tmpRole = trim($tmpRole);
                $data['roles'][md5($tmpRole)] = $tmpRole;
            }

            $tmpUsers = explode(',', $record['assigned']);
            $taskData = [
                'name' => $record['task'],
                'project' => md5($record['project']),
            ];
            foreach ($tmpUsers as $tmpUserKey => $tmpUser) {
                $tmpUser = trim($tmpUser);
                $role = null;
                if (Arr::has($tmpRoles, $tmpUserKey)) {
                    $role = md5(trim($tmpRoles[$tmpUserKey]));
                }
                $userArrayKey = md5($tmpUser);
                if (!array_key_exists($userArrayKey, $data['users'])) {
                    $userData = [
                        'name' => $tmpUser,
                        'role' => $role
                    ];
                    $data['users'][md5($tmpUser)] = $userData;
                }
                $taskData['users'][md5($tmpUser)] = md5($tmpUser);
            }
            $data['tasks'][] = $taskData;
        }

        return $data;
    }

    private function saveClients(array $clients): void
    {
        foreach ($clients as $clientOldId => $clientData) {
            $client = $this->clientsService->create(['name' => $clientData]);
            $this->clientMap[$clientOldId] = $client->id;
        }
    }

    private function saveProjects(array $projects): void
    {
        foreach ($projects as $projectOldId => $projectData) {
            $clientId = Arr::get($this->clientMap, $projectData['client']);
            $project = $this->projectsService->create([
                'name' => $projectData['name'],
                'client_id' => $clientId,
            ]);
            $this->projectMap[$projectOldId] = $project->id;
        }
    }

    private function saveRoles(array $roles): void
    {
        foreach ($roles as $roleOldId => $roleData) {
            $role = $this->rolesService->create(['name' => $roleData]);
            $this->roleMap[$roleOldId] = $role->id;
        }
    }

    private function saveUsers(array $users): void
    {
        foreach ($users as $userOldId => $userData) {
            $roleId = Arr::get($this->roleMap, $userData['role']);
            $user = $this->usersService->create([
                'name' => $userData['name'],
                'role_id' => $roleId,
            ]);
            $this->userMap[$userOldId] = $user->id;
        }
    }

    private function saveTasks(array $tasks): void
    {
        foreach ($tasks as $taskOldId => $taskData) {
            $users = [];
            if (is_array($taskData['users'])) {
                foreach ($taskData['users'] as $oldUserId) {
                    $users[] = $this->userMap[$oldUserId];
                }
            }
            $this->tasksService->create([
                'name' => $taskData['name'],
                'project_id' => $this->projectMap[$taskData['project']],
                'users' => $users,
            ]);
        }
    }
}
