<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\task;

 /**
 * Class UpdateTaskApiModel
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="UpdateTaskApiModel model",
 *     description="UpdateTaskApiModel model",
 * )
 */


class UpdateTaskApiModel
{
    /**
     * @OA\Property(
     *     description="Task ID",
     *     title="id",
     * )
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *     description="Task title",
     *     title="title",
     *     nullable=true,
     *     default="",
     *     example=""
     * )
     * @var string
     */
    public $title;


    /**
     * @OA\Property(
     *     description="Task description",
     *     title="description",
     *     nullable=true,
     *     default="",
     *     example=""
     * )
     * @var string
     */
    public $description;


    /**
     * @OA\Property(
     *     description="Task Status: 1 => pending, 2 => in_progress, 3 => completed, 4 => canceled",
     *     title="status",
     *     nullable=true,
     *     default="",
     *     example=""
     * )
     * @var integer
     */
    public $status;
}
