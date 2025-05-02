<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Tasks\NewTaskRequest;
use App\Http\Requests\API\Tasks\UpdateTaskRequest;
use App\Http\Requests\TasksListRequest;
use App\Http\Responses\CustomResponse;
use App\Models\Task;
use App\Models\User;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Tasks", "All tasks actions : show,create,update and delete.")]
class TaskController extends Controller
{
    /**
     * Get user tasks
     *
     * @param \App\Models\User $user
     * @param \App\Http\Requests\TasksListRequest $request
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function index($user_id, TasksListRequest $request)
    {
        $user = User::findOrFail($user_id);
        $tasks = $user->tasks()
            ->when($request->priority, function ($query) use ($request) {
                $query->where('priority', '=', $request->priority);
            })
            ->when($request->priorityFrom, function ($query) use ($request) {
                $query->where('priority', '>=', $request->priorityFrom);
            })
            ->when($request->priorityTo, function ($query) use ($request) {
                $query->where('priority', '<=', $request->priorityTo);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('created_at', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('created_at', '<=', $request->dateTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->when(!$request->sortBy, function ($query) use ($request) {
                $query->orderBy('created_at');
            })
            ->paginate()
            ->withQueryString();

        //
        return CustomResponse::ok(["tasks" => $tasks]);
    }

    /**
     * Create task
     * 
     * @param \App\Http\Requests\API\Tasks\NewTaskRequest $request
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function store(NewTaskRequest $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $data = $request->validated();

        $task = $user->tasks()->create($data);

        $response = [
            'msg' => 'Task created successfully',
            'task' => $task
        ];

        return CustomResponse::created($response);
    }

    /**
     * Show task
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "uuid", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    #[UrlParam('task_id', "integer", "Task id", example: "10")]
    public function show($user_id, $task_id)
    {
        $task = Task::findOrFail($task_id);
        return CustomResponse::ok(['task' => $task]);
    }

    /**
     * Update task
     * 
     * @param \App\Http\Requests\API\Tasks\UpdateTaskRequest $request
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    #[UrlParam('task_id', "integer", "Task id", example: "10")]
    public function update(UpdateTaskRequest $request, $user_id, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $data = $request->validated();

        if (isset($data['title'])) {
            $task->title = $data['title'];
        }
        if (isset($data['content'])) {
            $task->content = $data['content'];
        }
        if (isset($data['priority'])) {
            $task->priority = (int) $data['priority'];
        }

        $task->save();

        $response = [
            'msg' => 'Task updated successfully',
            'task' => $task
        ];

        return CustomResponse::ok($response);
    }

    /**
     * Delete task
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    #[UrlParam('task_id', "integer", "Task id", example: "10")]
    public function destroy($user_id, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $task->delete();

        return CustomResponse::deleted();
    }
}
