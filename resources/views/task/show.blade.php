@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $task->name }}</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>id</th>
                            <td>{{ $task->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $task->name }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $task->description }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $task->status->name ?? null }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $task->creator }}</td>
                        </tr>
                        <tr>
                            <th>Assigned to</th>
                            <td>{{ $task->assignedTo }}</td>
                        </tr>
                        <tr>
                            <th>Tags</th>
                            <td>{{ $task->tags }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection