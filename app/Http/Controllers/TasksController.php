<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\TasksService;

class TasksController extends Controller
{
    public function create()
    {
        $projects = Project::all();
        $users = User::all();

        return view('create_task', [
            'projects' => $projects,
            'users' => $users,
        ]);
    }

    public function store(TasksService $tasksService)
    {
        $this->validate(request(), [
            'name' => ['required'],
            'project_id' => ['required', 'exists:projects,id'],
            'users' => ['array'],
            'users.*' => ['exists:users,id'],
        ]);

        $tasksService->create(request()->all());

        return redirect(route('home'))->with('success', __('messages.task was created', ['name' => request('name')]));
    }
}
