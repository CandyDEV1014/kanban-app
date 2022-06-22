<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Column;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\SyncTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Column::with('tasks')->get();
        return view('home', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        return Task::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function sync(SyncTaskRequest $request)
    {
        foreach ($request->columns as $column) {
            foreach ($column['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['column_id'] !== $column['id'] || $task['order'] !== $order) {
                    Task::find($task['id'])->update(['column_id' => $column['id'], 'order' => $order]);
                }
            }
        }

        return Column::with('tasks')->get();
    }

    public function ajaxGetTask(Request $request)
    {
        $id = $request->taskId;
        $task = Task::find($id)->toArray();
        
        return response()->json($task);
    }
}
