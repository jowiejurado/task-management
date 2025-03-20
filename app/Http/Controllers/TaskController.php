<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$validated = $request->validate([
			'itemsPerPage'   	=> ['integer', 'min:1', 'max:200'],
			'page'    				=> ['integer', 'min:1'],
			'search'         	=> ['nullable', 'string', 'max:255'],
			'orderBy'        	=> ['nullable', 'in:title,description,priority,status,created_at'],
			'orderDirection' 	=> ['nullable', 'in:asc,desc'],
		]);

		$itemsPerPage   = $validated['itemsPerPage'] ?? 10;
		$page    				= $validated['page'] ?? 1;
		$search         = $validated['search'] ?? null;
		$orderBy        = $validated['orderBy'] ?? 'created_at';
		$orderDirection = $validated['orderDirection'] ?? 'desc';

		$query = Task::where('user_id', Auth::id());

		if ($search) {
			$query->where(function ($innerQuery) use ($search) {
				$innerQuery->where('title', 'like', "%{$search}%")
					->orWhere('description', 'like', "%{$search}%");
			});
		}

		$query->orderBy($orderBy, $orderDirection);

		$tasks = $query->paginate($itemsPerPage, ['*'], 'page', $page);

		return TaskResource::collection($tasks);
	}


	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreTaskRequest $request)
	{
		$validated = $request->validated();
		$validated['user_id'] = Auth::id();

		$task = Task::create($validated);

		return new TaskResource($task);
	}


	/**
	 * Display the specified resource.
	 */
	public function show(Task $task)
	{
		if ($task->user_id !== Auth::id()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		return new TaskResource($task);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTaskRequest $request, Task $task)
	{
		if ($task->user_id !== Auth::id()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		$task->update($request->validated());

		return new TaskResource($task);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Task $task)
	{
		if ($task->user_id !== Auth::id()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}

		$task->delete();

		return response()->json([], 204);
	}
}
