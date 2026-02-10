<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;

use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskStatusResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="TaskStatusResult model",
 *     description="TaskStatusResult model",
 * )
 */
class TaskStatusResult extends Model
{
    protected $fillable = [
         'items_count','result' , 'isOk' , 'message'
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
     *     description="TaskStatusApiModel Result Model",
     *     title="result",
     *     @OA\Items(ref="#/components/schemas/TaskStatusApiModel")
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

    /**
     * @OA\Property(
     *     description="Pages count of list (by page size)",
     *     title="pagesCount",
     * )
     *
     * @var integer
     */
    public $pagesCount;
}
