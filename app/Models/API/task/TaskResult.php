<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;



use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="TaskResult model",
 *     description="TaskResult model",
 * )
 */
class TaskResult extends Model
{
    protected $fillable = [
         'items_count', 'pages_count', 'result' , 'isOk' , 'message'
    ];

/**
     * @OA\Property(
     *     description="items_count",
     *     title="items_count",
     * )
     *
     * @var integer
     */
    public $items_count;


    /**
     * @OA\Property(
     *     description="Pages count of list (by page size)",
     *     title="pagesCount",
     * )
     *
     * @var integer
     */
    public $pages_count;


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
