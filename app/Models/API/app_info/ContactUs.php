<?php

/**
 * @license Apache 2.0
 */

namespace App\Models\API\app_info;
use App\Models\API\auth_admin\ProfileAdminResult;
use App\Models\API\location\Location;
use App\Models\API\location\LocationAdmin;
use App\Models\API\other\IdValueApiModel;
use Facade\FlareClient\Time\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\Double;


/**
 * Class ContactUs
 *
 * @package Petstore30
 *
 * @OA\Schema(
 *     title="ContactUs model",
 *     description="ContactUs model",
 * )
 */

class ContactUs extends Model
{
    protected $fillable = [
        'location' , 'phone' , 'mobile'
    ];


    /**
     * @OA\Property(
     *     description="Location Model",
     *     title="Location",
     * )
     *
     * @var LocationAdmin
     */
    public $location;


     /**
      * @OA\Property(
      *     description="phone",
      *     title="phone",
      * )
      *
      * @var string
      */
    public $phone;

     /**
      * @OA\Property(
      *     description="mobile",
      *     title="mobile",
      * )
      *
      * @var string
      */
    public $mobile;

}

