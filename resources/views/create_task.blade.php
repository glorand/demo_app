<x-layout>
    <div class="row">
        <h2 class="mt-4">Create Task</h2>
    </div>
    <div class="row"></div>
    <form method="post" action="{{ route('tasks.store') }}">
        @csrf
        <input name="_METHOD" value="post" type="hidden">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Task Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="name" name="name">
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="project_id" class="col-sm-2 col-form-label">Project</label>
            <div class="col-sm-4">
                <select id="project_id" name="project_id" class="form-select">
                    <option value="" selected>Choose...</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Users</legend>
            <div class="col-sm-10">
                @foreach($users as $user)
                    <div class="form-check">
                        <input name="users[]" value="{{ $user->id }}" class="form-check-input" type="checkbox" id="gridCheck1">
                        <label class="form-check-label" for="gridCheck1">
                            {{ $user->name }} ({{ $user->role->name }})
                        </label>
                    </div>
                @endforeach
                @error('users')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('home') }}" class="btn btn-danger">Cancel</a>
    </form>
</x-layout>
