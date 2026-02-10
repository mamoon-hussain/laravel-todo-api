<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\other;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiMessage
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="ApiMessage model",
 *     description="ApiMessage model",
 * )
 */
class ApiMessage extends Model
{

    protected $fillable = ['type' , 'code' , 'content'];

    /**
     * @OA\Property(
     *     description="Message type",
     *     title="type",
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *     description="Message code",
     *     title="code",
     * )
     *
     * @var number
     */
    public $code;

    /**
     * @OA\Property(
     *     description="Message content",
     *     title="content",
     * )
     *
     * @var string
     */
    public $content;


}

