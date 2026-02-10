<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\app_info;


use App\Models\API\other\ApiMessage;
use Illuminate\Database\Eloquent\Model;


/**
 * Class AboutUsResult
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="AboutUsResult model",
 *     description="AboutUsResult model",
 * )
 */
class ContactUsResult extends Model
{
    protected $fillable = [
        'result' , 'isOk' , 'message'
    ];

    /**
     * @OA\Property(
     *     description="AboutUs Model",
     *     title="result",
     * )
     *
     * @var ContactUs
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

