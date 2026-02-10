<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;

use App\Models\API\other\IdValueApiModel;
use Illuminate\Database\Eloquent\Model;


/**
 * Class TaskApiModel
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="TaskApiModel model",
 *     description="TaskApiModel model",
 * )
 */
class TaskApiModel extends Model
{

    protected $fillable = [
        'id' , 'title', 'description', 'status',
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
     *     description="title",
     *     title="title",
     * )
     *
     * @var string
     */
    public $title;

    /**
     * @OA\Property(
     *     description="description",
     *     title="description",
     * )
     *
     * @var string
     */
    public $description;


    /**
     * @OA\Property(
     *     description="Status",
     *     title="status",
     * )
     *
     * @var IdValueApiModel
     */
    public $status;
}

