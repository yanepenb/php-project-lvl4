<?php

namespace App\Http\Controllers;

use App\TaskFilter;
use App\Task;
use App\TaskStatus;
use App\User;
use Spatie\Tags\Tag;
use Auth;
use App\Http\Requests\StoreTaskPost;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $tasks = Task::with('creator', 'status', 'assignedTo');

        $statuses = TaskStatus::all();
        $users = User::all();
        $tags = Tag::all();

        $users = QueryBuilder::for(User::class)
            ->allowedIncludes(['tasks'])
            ->get();

        dd($users);
        $tasks = QueryBuilder::for(Task::class)
            ->allowedIncludes(['creator', 'status', 'assignedTo', 'tags'])
            ->allowedFilters(
                AllowedFilter::exact('myTasks', 'creator_id'),
                AllowedFilter::exact('status', 'status_id'),
                AllowedFilter::exact('assignedTo', 'assigned_to_id'),
                AllowedFilter::exact('tags', 'tags'))

            ->paginate();

        return view('task.index', compact('tasks', 'statuses', 'users', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();
        $statuses = TaskStatus::all();
        $users = User::all();
        $tags = Tag::all();

        return view('task.create', compact('task', 'statuses', 'users', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskPost $request)
    {
        $request->validated();
        $task = new Task();

        $status = $request->status_id;
        $assignedToUser = $request->assigned_to_id;
        $task->status()->associate($status);
        $task->creator()->associate(\Auth::user());
        $task->assignedTo()->associate($assignedToUser);
        $task->fill($request->except(['tags']));
        $task->save();

        $tags = $request->tags;
        $task->attachTags($tags);

        flash(__('tasks.created'))->success();
        return redirect()
            ->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $tags = Tag::all();
        $selectedTags = $task->tags->pluck('name', 'id')->all();

        return view('task.edit', compact('task', 'statuses', 'users', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTaskPost $request, Task $task)
    {
        $request->validated();
        $task->fill($request->except(['tags']));
        $task->save();

        $tags = $request->tags;
        $task->syncTags($tags);

        flash(__('tasks.updated'))->success();
        return redirect()
            ->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        Task::findOrFail($task->id)->delete();

        flash(__('tasks.deleted'))->success();
        return redirect()
            ->route('tasks.index');
    }
}
