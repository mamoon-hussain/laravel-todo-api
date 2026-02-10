<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;
use App\Models\API\other\IdValueApiModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CreateTaskModel
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="CreateTaskModel model",
 *     description="CreateTaskModel model",
 * )
 */
class CreateTaskApiModel extends Model
{
    /**
     * @OA\Property(
     *     description="title",
     *     title="title",
     * )
     *
     * @var String
     */
    public $title;

    /**
     * @OA\Property(
     *     description="description",
     *     title="description",
     * )
     *
     * @var String
     */
    public $description;


    /**
     * @OA\Property(
     *     description="Status",
     *     title="status",
     * )
     *
     * @var integer
     */
    public $status;
}
