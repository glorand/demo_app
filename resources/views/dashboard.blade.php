<x-layout>
    <div class="row">
        <h2 class="mt-4">Dasboard</h2>
    </div>
    @if ($message = Session::get('success'))
        <div class="row">
            <div class="alert alert-success alert-block">
                <strong>{{ $message }}</strong>
            </div>
        </div>
    @endif
    <div class="row">
        @if(empty($data))
            <form action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>

                </div>
            </form>
        @else
        <div class="col-12">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
        </div>
        <div class="col-12">
            <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Client</th>
                <th scope="col">Project</th>
                <th scope="col">Task</th>
                <th scope="col">Assigned Users</th>
                <th scope="col">User roles involved</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <th scope="row">{{ $item->task_id }}</th>
                    <th scope="row">{{ $item->client_name }}</th>
                    <th scope="row">{{ $item->project_name }}</th>
                    <th scope="row">{{ $item->task_name }}</th>
                    <th scope="row">{{ $item->users }}</th>
                    <th scope="row">{{ $item->roles }}</th>
                </tr>
            @endforeach
            </tbody>

        </table>
        </div>
        @endif
    </div>
</x-layout>
