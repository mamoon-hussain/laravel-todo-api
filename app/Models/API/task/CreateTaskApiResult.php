<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;
use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CreateTaskApiResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="CreateTaskApiResult model",
 *     description="CreateTaskApiResult model",
 * )
 */
class CreateTaskApiResult extends Model
{
    protected $fillable = [
        'result' , 'isOk' , 'message'
    ];

    /**
     * @OA\Property(
     *     description="CreateTaskApiModel Result Model",
     *     title="result",
     *     ref="#/components/schemas/CreateTaskApiModel"
     * )
     *
     * @var CreateTaskApiModel
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
