<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\other;


use Illuminate\Database\Eloquent\Model;

/**
 * Class IdValueApiModel
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="IdValueApiModel model",
 *     description="IdValueApiModel model",
 * )
 */
class IdValueApiModel extends Model
{
    protected $fillable = [
        'id' , 'value'
    ];

    /**
     * @OA\Property(
     *     description="ID",
     *     title="id",
     * )
     *
     * @var int
     */
    public $id;

    /**
     * @OA\Property(
     *     description="Value",
     *     title="value",
     * )
     *
     * @var string
     */
    public $value;

}

