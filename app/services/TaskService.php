<?php

namespace App\services;

use App\enums\ActiveInactiveStatus;
use App\enums\BikeForSale;
use App\enums\ErrorCode;
use App\enums\RideTypeEnums;
use App\enums\TaskStatus;
use App\enums\UserTypeEnum;
use App\Models\API\lists\BadgeResult;
use App\Models\API\lists\BikeForSaleResult;
use App\Models\API\lists\TaskStatusResult;
use App\Models\API\lists\BrakesResult;
use App\Models\API\lists\CategoryResult;
use App\Models\API\lists\CityResult;
use App\Models\API\lists\JobResult;
use App\Models\API\lists\PostResult;
use App\Models\API\lists\SourceResult;
use App\Models\API\lists\StudyResult;
use App\Models\API\lists\SubscriptionResult;
use App\Models\API\other\ApiMessage;
use App\Models\API\lists\RideResult;
use App\Models\API\other\ApiResult;
use App\Models\API\task\CreateTaskApiResult;
use App\Models\API\task\TaskResult;
use App\Models\API\team_member\TeamMemberBike2Result;
use App\Models\Badge;
use App\Models\Bike;
use App\Models\BikeType;
use App\Models\Brake;
use App\Models\City;
use App\Models\Job;
use App\Models\Level;
use App\Models\Metal;
use App\Models\Post;
use App\Models\Ride;
use App\Models\Source;
use App\Models\Study;
use App\Models\Subscription;
use App\Models\Task;
use Illuminate\Support\Facades\DB;


class TaskService
{

    public static function tasksList($request)
    {
        try {
            $page_size = $request->pagesize ?: 10;
            $query = Task::query();

            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'LIKE', "%{$request->search}%")
                        ->orWhere('description', 'LIKE', "%{$request->search}%");
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            // Paginate the filtered query
            $paginatedTasks = $query->paginate($page_size);

            $data = [];
            foreach ($query->paginate($page_size) as $one) {
                $item = FillApiModelService::FillTaskApiModel($one);
                $data[] = $item;
            }
            $res = new TaskResult([
                'items_count' => $paginatedTasks->total(),
                'pages_count' => $paginatedTasks->lastPage(),
                'result' => $data,
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => '',
                ]),
            ]);

            return [true, $res, '', ''];
        } catch (\Exception $ex) {
            return [false, null, AdminService::Msg_Exception, $ex->getMessage()];
        }
    }


    public static function CreateTask($request)
    {
        try {

            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->save();

            //
            $createdTaskModel = FillApiModelService::FillTaskApiModel($task);

            // Build the result
            $res = new CreateTaskApiResult([
                'result' => $createdTaskModel,
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => 'Task created successfully.',
                ]),
            ]);

            return [true, $res, '', ''];

        } catch (\Exception $ex) {
            return [false, null, AdminService::Msg_Exception, $ex->getMessage()];
        }
    }


    public static function UpdateTask($request)
    {
        try {

            $task = Task::findOrFail($request->id);

            $task->title = (isset($request->title) && $request->title) ? $request->title : $task->title;
            $task->description = (isset($request->description) && $request->description) ? $request->description : $task->description;
            $task->status = (isset($request->status) && $request->status) ? $request->status : $task->status;

            $task->save();

            $updatedTaskModel = FillApiModelService::FillTaskApiModel($task);
            $res = new CreateTaskApiResult([
                'result' => $updatedTaskModel,
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => 'Task updated successfully.',
                ]),
            ]);

            return [true, $res, '', ''];
        } catch (\Exception $ex) {
            return [false, null, AdminService::Msg_Exception, $ex->getMessage()];
        }
    }


    public static function ViewTaskDetails($request) {
        try {
            $task = Task::where('id', $request->task_id)->first();
            if(!$task){
                return [false, '', 'Task Not Found', ''];
            }

            $data = FillApiModelService::FillTaskApiModel($task);

            $res = new TaskResult([
                'result' => $data,
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => '',
                ]),
            ]);
            return [true, $res, '', ''];

        } catch (\Exception $ex) {
            return [false, '', $ex->getMessage(), ''];
        }
    }


    public static function DeleteTask($request) {
        try {

            $task = Task::where('id', $request->task_id)->first();
            if(!$task){
                return [false, '', 'Task Not Found', ''];
            }

            $task->delete();

            $res = new ApiResult([
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => 'Task Deleted Successfully',
                ]),
            ]);
            return [true, $res, '', ''];

        } catch (\Exception $ex) {
            return [false, '', $ex->getMessage(), ''];
        }
    }


}
