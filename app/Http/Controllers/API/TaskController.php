<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\TaskDetailsRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ListsController
 */
class TaskController
{

    /**
     * @OA\Get(path="/api/task/index",
     *     tags={"Tasks"},
     *     summary="tasks List",
     *     @OA\Parameter(
     *         name="pagesize",
     *         in="query",
     *         description="number of returned values",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="search by title or description",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="search by Status (1 => pending, 2 => in_progress, 3 => completed, 4 => canceled)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "Success response",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResult"),
     *     ),
     * )
     * @param Request $request
     * @return JsonResponse
     */

    public function index(Request $request)
    {
        list($res, $data, $msg , $ex) =  TaskService::tasksList($request);
        if($res){
            return response()->json($data);
        } else {
            return returnError($msg , $ex);
        }
    }

    /**
     * @OA\Get(path="/api/task/details",
     *     tags={"Tasks"},
     *     summary="Task Details",
     *     @OA\Parameter(
     *         name="task_id",
     *         in="query",
     *         required=true,
     *         description="ID of task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "Success response",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResult"),
     *     ),
     * )
     * @param TaskDetailsRequest $request
     * @return JsonResponse
     */
    public function details(Request $request)
    {
        list($res, $data, $msg , $ex) =  TaskService::ViewTaskDetails($request);
        if($res){
            return response()->json($data);
        } else {
            return returnError($msg , $ex);
        }
    }


    /**
     * @OA\Post(path="/api/task/create",
     *     tags={"Tasks"},
     *     summary="Create New Task",
     *     @OA\RequestBody(
     *         description="Create Task model",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTaskApiModel")
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "CreateTaskApiResult response",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResult"),
     *     ),
     * )
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */

    public function create(CreateTaskRequest $request)
    {
        list($res, $data, $msg , $ex) =  TaskService::CreateTask($request);
        if($res){
            return response()->json($data);
        } else {
            return returnError($msg , $ex);
        }
    }


    /**
     * @OA\Put(path="/api/task/update",
     *     tags={"Tasks"},
     *     summary="Update Task",
     *     @OA\RequestBody(
     *         description="Create Task model",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskApiModel")
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "UpdateTaskApiResult response",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResult"),
     *     ),
     * )
     * @param UpdateTaskRequest $request
     * @return JsonResponse
     */

    public function update(UpdateTaskRequest $request)
    {
        list($res, $data, $msg , $ex) =  TaskService::UpdateTask($request);
        if($res){
            return response()->json($data);
        } else {
            return returnError($msg , $ex);
        }
    }

    /**
     * @OA\Delete(path="/api/task/delete",
     *     tags={"Tasks"},
     *     summary="Delete Task",
     *     @OA\Parameter(
     *         name="task_id",
     *         in="query",
     *         required=true,
     *         description="ID of task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "Success response",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResult"),
     *     ),
     * )
     * @return JsonResponse
     */

    public function delete(Request $request)
    {
        list($res, $data, $msg , $ex) = TaskService::DeleteTask($request);
        if($res){
            return response()->json($data);
        } else {
            return returnError($msg , $ex);
        }
    }
}
