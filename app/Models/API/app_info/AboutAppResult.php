<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\app_info;


use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;


/**
 * Class AboutAppResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="AboutAppResult model",
 *     description="AboutAppResult model",
 * )
 */
class AboutAppResult extends Model
{
    protected $fillable = [
        'result' , 'isOk' , 'message'
    ];

    /**
     * @OA\Property(
     *     description="AboutApp Model",
     *     title="result",
     * )
     *
     * @var AboutApp
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

