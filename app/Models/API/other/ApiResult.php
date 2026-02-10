<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\other;


use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="ApiResult model",
 *     description="ApiResult model",
 * )
 */
class ApiResult extends Model
{
    protected $fillable = ['isOk' , 'message' , 'exception'];

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
     *     description="Exception Message",
     *     title="exception",
     * )
     *
     * @var string
     */
    public $exception;

}

