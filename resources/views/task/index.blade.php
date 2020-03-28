@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
                @if(Auth::check())
                    <a class="btn btn-lg mb-2 btn-primary" href="{{ route('tasks.create') }}" role="button"><i class="fas fa-plus-circle"></i>  Create task</a>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Creator</th>
                            <th scope="col">Assigned to</th>
                            <th scope="col">Tags</th>
                            <th scope="col">Actions</th>
                     </tr>
                    </thead>
                    @foreach ($tasks as $task)
                        <tbody>
                            <tr>
                                <th scope="row">{{ $task->id }}</th>
                                <td>{{ $task->name }}</td>
                                <td><a href="{{ route('tasks.show', $task->id) }}">{{ Str::limit($task->description, 10) }}</a></td>
                                <td>{{ $task->status }}</td>
                                <td>{{ $task->creator }}</td>
                                <td>{{ $task->assignedTo }}</td>
                                <td>{{ $task->tags }}</td>
                                <td>
                                    <a class="btn btn-success" href="{{ route('tasks.edit', $task->id) }}" role="button">Edit</a>
                                    <a class="btn btn-danger" role="submit" href="{{ route('tasks.destroy', $task->id) }}" data-method="delete" data-confirm="Are you sure?" rel="nofollow">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>

                {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection