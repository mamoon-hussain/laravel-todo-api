<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;

use App\Models\API\other\IdValueApiModel;
use Illuminate\Database\Eloquent\Model;


/**
 * Class TaskStatusApiModel
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="TaskStatusApiModel model",
 *     description="TaskStatusApiModel model",
 * )
 */
class TaskStatusApiModel extends Model
{

    protected $fillable = [
        'id' , 'name'
    ];


    /**
     * @OA\Property(
     *     description="ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *     description="Name",
     *     title="name",
     * )
     *
     * @var string
     */
    public $name;

}

