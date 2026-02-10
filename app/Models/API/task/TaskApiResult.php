<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;



use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskApiResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="TaskApiResult model",
 *     description="TaskApiResult model",
 * )
 */
class TaskApiResult extends Model
{
    protected $fillable = [
        'result' , 'isOk' , 'message'
    ];

    /**
     * @OA\Property(
     *     description="TaskApiModel Result Model",
     *     title="result",
     *     @OA\Items(ref="#/components/schemas/TaskApiModel")
     * )
     *
     * @var array
     */
    public $result;

    /**
     * @OA\Property(
     *     description="Indicates if the response is ok or not",
     *     title="isOk",
     * )
     *
     * @var boolean
     */
    public $isOk;

    /**
     * @OA\Property(
     *     description="Api message",
     *     title="message",
     * )
     *
     * @var ApiMessage
     */
    public $message;



}
