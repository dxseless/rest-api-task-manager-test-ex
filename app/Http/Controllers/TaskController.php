<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Task Manager API",
 *     version="1.0.0",
 *     description="API для управления списком задач"
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Основной сервер"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Задача 1"),
 *     @OA\Property(property="description", type="string", example="Описание задачи"),
 *     @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-20T15:00:00"),
 *     @OA\Property(property="create_date", type="string", format="date-time", example="2025-01-20T15:00:00"),
 *     @OA\Property(property="status", type="string", enum={"не выполнена", "выполнена"}, example="не выполнена"),
 *     @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="высокий"),
 *     @OA\Property(property="category", type="string", example="Работа"),
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Получить список задач",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Поиск по названию задачи",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Сортировка по полю (due_date или created_at)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список задач",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            $query->orderBy($request->sort);
        }

        return $query->paginate(10);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Создать новую задачу",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "due_date", "priority", "category"},
     *             @OA\Property(property="title", type="string", example="Задача 1"),
     *             @OA\Property(property="description", type="string", example="Описание задачи"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-20T15:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="высокий"),
     *             @OA\Property(property="category", type="string", example="Работа"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Задача успешно создана",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Task created successfully")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'required|date',
            'priority' => 'required|in:низкий,средний,высокий',
            'category' => 'required',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'create_date' => now(),
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'не выполнена',
        ]);

        return response()->json(['id' => $task->id, 'message' => 'Task created successfully'], 201);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Получить задачу по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача найдена",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Обновить задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Обновленная задача"),
     *             @OA\Property(property="description", type="string", example="Обновленное описание"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-25T18:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="средний"),
     *             @OA\Property(property="category", type="string", example="Дом"),
     *             @OA\Property(property="status", type="string", enum={"не выполнена", "выполнена"}, example="выполнена"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача успешно обновлена",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|max:255',
            'description' => 'nullable',
            'due_date' => 'sometimes|date',
            'priority' => 'sometimes|in:низкий,средний,высокий',
            'category' => 'sometimes',
            'status' => 'sometimes|in:не выполнена,выполнена',
        ]);

        $task->update($request->all());

        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Удалить задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача успешно удалена",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}