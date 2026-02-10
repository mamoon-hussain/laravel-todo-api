<?php

namespace App\services;


use App\enums\ActiveInactiveStatus;
use App\enums\Constents;
use App\enums\ErrorCode;
use App\enums\GenderEnum;
use App\enums\JoinRideRequestStatus;
use App\enums\NotificationType;
use App\enums\PositionIsDefault;
use App\enums\RideTypeEnums;
use App\enums\TaskStatus;
use App\enums\UserStatus;
use App\Models\Admin;
use App\Models\API\app_info\AboutApp;
use App\Models\API\app_info\ContactUs;
use App\Models\API\auth\ApiResultProfileResult;
use App\Models\API\auth\ApiResultRegisterResult;
use App\Models\API\auth\ProfileResult;
use App\Models\API\auth\RegisterResult;
use App\Models\API\auth\UserImageApiModel;
use App\Models\API\lists\BadgeApiModel;
use App\Models\API\lists\BikeForSaleApiModel;
use App\Models\API\lists\TaskStatusApiModel;
use App\Models\API\lists\BrakesApiModel;
use App\Models\API\lists\CarTypeModel;
use App\Models\API\lists\CategoryApiModel;
use App\Models\API\lists\CityApiModel;
use App\Models\API\lists\DosesApiModel;
use App\Models\API\lists\JobApiModel;
use App\Models\API\lists\LeaderBoardApiModel;
use App\Models\API\lists\PositionApiModel;
use App\Models\API\lists\PostApiModel;
use App\Models\API\lists\PostImageApiModel;
use App\Models\API\lists\RideApiModel;
use App\Models\API\lists\RideLevelApiModel;
use App\Models\API\lists\SourceApiModel;
use App\Models\API\lists\StudyApiModel;
use App\Models\API\lists\SubscriptionApiModel;
use App\Models\API\lists\SubscriptionFeatureApiModel;
use App\Models\API\lists\TimeToTakeMedApiModel;
use App\Models\API\notification\ListNotifications;
use App\Models\API\other\ApiMessage;
use App\Models\API\other\IdValueApiModel;
use App\Models\API\rides\MyRequestApiModel;
use App\Models\API\rides\MyRideApiModel;
use App\Models\API\task\TaskApiModel;
use App\Models\Bike;
use App\Models\BikeType;
use App\Models\Brake;
use App\Models\City;
use App\Models\Job;
use App\Models\Level;
use App\Models\Position;
use App\Models\Source;
use App\Models\Study;
use App\Models\UserImage;
use App\User;
use Carbon\Carbon;
use App\enums\UserHaveBikeEnum;
use Illuminate\Support\Facades\Lang;
use stdClass;


class FillApiModelService
{

    public static function FillLeaderBoardItems($item, $key)
    {
        $model = new LeaderBoardApiModel([
            'id' => $item->user_id,
            'full_name' => self::getUserFullName($item->user_id),
            'position' => self::getUserPosition($item->user_id),
            'distance' => $item->total_user_distance,
            'profile_image' => self::getProfileImage($item->user_id),
            'ranking' => $key + 1,
        ]);
        return $model;
    }


    public static function getUserFullName($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return $user->full_name;
    }

    public static function getUserPosition($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return self::FillPositionApiModel($user->position_id);
    }

    public static function getProfileImage($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return $user->avatar? getImage($user->avatar): '';
    }

    public static function FillBikeForSale($item)
    {
        $model = new BikeForSaleApiModel([
            'id' => $item->id,
            'image' => isset($item->image1)? getImage($item->image1): '',
            'name' => $item->name,
            'type' => self::FillBikeForSaleTypeApiModel($item->type_id),
            'price' => $item->price,
        ]);
        return $model;
    }

    public static function FillSubscriptionApiModel($item)
    {
        $model = new SubscriptionApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'duration' => $item->duration,
            'price' => $item->price,
            'description' => $item->description,
            'features' => self::FillSubscriptionFeaturesApiModel($item->features),
        ]);
        return $model;
    }

    public static function FillSubscriptionFeaturesApiModel($items)
    {
        $data = [];
        foreach ($items as $item){
            $model = new SubscriptionFeatureApiModel([
                'id' => $item->id,
                'feature' => $item->feature,
            ]);
            $data [] = $model;
        }
        return $data;
    }

    public static function FillBikeForSaleTypeApiModel($item_id)
    {
        $item = BikeType::where('id', $item_id)->first();
        $model = new TaskStatusApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);
        return $model;
    }

    public static function FillBikeForSaleBrakeApiModel($item_id)
    {
        $item = Brake::where('id', $item_id)->first();
        $model = new BrakesApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);
        return $model;
    }



    public static function FillPostApiModel($item)
    {
        $model = new PostApiModel([
            'id' => $item->id,
            'description' => $item->description,
            'images' => self::FillPostImagesApiModel($item->images),
            'date' => Carbon::createFromTimestamp(strtotime($item->created_at))->diffForHumans(),
            'views_count' => $item->views_count,
        ]);
        return $model;
    }

    public static function FillPostImagesApiModel($items)
    {
        $data = [];
        foreach ($items as $item){
            $data [] = new  PostImageApiModel([
                'id' => $item->id,
                'image' => $item->image ? getImage($item->image) : '',
            ]);
        }
        return $data;
    }


    public static function FillPositionApiModel($item_id)
    {
        $item = Position::where('id', $item_id)->first();
        $model = new PositionApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'default_position' => $item->default_position,
        ]);
        return $model;
    }


    public static function FillSourceApiModel($item)
    {
        $model = new SourceApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'image' => $item->image? getImage($item->image): '',
        ]);
        return $model;
    }

    public static function FillBikeSourceApiModel($item_id)
    {
        $item = Source::where('id', $item_id)->first();
        $model = new SourceApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'image' => $item->image? getImage($item->image): '',
        ]);
        return $model;
    }

    public static function FillMyRequestsApiModel($item)
    {
        $model = new MyRequestApiModel([
            'id' => $item->id,
//            'created_at' => date(Constents::date_format_hours_minutes_seconds, strtotime($item->created_at)),
//            'updated_at' => date(Constents::full_date_format, strtotime($item->updated_at)),
            'status' => self::FillIdValueApiModel($item->status, JoinRideRequestStatus::LabelOf($item->status)),
            'ride' => self::FillMyRidesApiModel($item->ride)
//            'date' =>  Carbon::createFromTimestamp(strtotime($item->date))->diffForHumans(),


        ]);
        return $model;
    }

    public static function FillBrakeApiModel($item)
    {
        $model = new BrakesApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'status' => self::FillIdValueApiModel($item->status, ActiveInactiveStatus::LabelOf($item->status)),
            'created_at' =>  $item->created_at,
            'updated_at' => $item->updated_at,
        ]);
        return $model;
    }

    public static function FillBikeTypeApiModel($item)
    {
        $model = new TaskStatusApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'status' => self::FillIdValueApiModel($item->status, ActiveInactiveStatus::LabelOf($item->status)),
            'created_at' =>  $item->created_at,
            'updated_at' => $item->updated_at,
        ]);
        return $model;
    }

    public static function FillRideLevelApiModel($item)
    {
        $model = new RideLevelApiModel([
            'id' => $item->id,
            'name' => $item->name,
//            'status' => self::FillIdValueApiModel($item->status, ActiveInactiveStatus::LabelOf($item->status)),
//            'created_at' =>  $item->created_at,
//            'updated_at' => $item->updated_at,
        ]);
        return $model;
    }


    public static function FillMyRideLevelApiModel($item_id)
    {
        $item = Level::where('id', $item_id)->first();
        $model = new RideLevelApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
        ]);
        return $model;
    }

    public static function FillDetailsRideLevelApiModel($item_id)
    {
        $item = Level::where('id', $item_id)->first();
        $model = new RideLevelApiModel([
            'id' => $item->id,
            'name' => $item->name
        ]);
        return $model;
    }


    public static function UpcomingRideApiModel($item)
    {
        $hour = date(Constents::date_format_hours, strtotime($item->time));
        $duration = '';
        if(((4 < $hour) || (4 == $hour)) && ($hour < 12)){
            $duration = 'Morning';
        } elseif (((12 < $hour) || (12 == $hour)) && ($hour < 17)){
            $duration = 'Noon';
        } else{
            $duration = 'Night';
        }

        $model = new RideApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'level' => isset($item->level_id)? self::FillUpcomingRideLevelApiModel($item->level_id): null,
            'date' => Carbon::parse(strtotime($item->date))->format('l'),
            'time' => $duration,
            'image' => $item->image ? getImage($item->image ) : '',
            'status' => self::FillIdValueApiModel($item->status, RideTypeEnums::LabelOf($item->status)),
        ]);
        return $model;
    }

    public static function FillUpcomingRideLevelApiModel($item_id){
        $level = Level::where('id', $item_id)->first();
        $model = new RideLevelApiModel([
            'id' => $level->id,
            'name' => $level->name,
        ]);
        return $model;
    }


    public static function FillCategoryApiModel($item){
        $model = new CategoryApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'sub_categories' => self::FillSubCategoriesApiModel($item),
        ]);
        return $model;
    }

    public static function FillListDosesApiModel($item)
    {

        $model = new DosesApiModel([
            'id' => $item->id,
            'content' => $item->content,
            'title' => $item->title,
            'shape' => self::FillShapesApiModel($item),
        ]);
        return $model;
    }


    public static function FillListShapesApiModel($item)
    {

        $model = new ShapesApiModel([
            'id' => $item->id,
            'content' => $item->content,
            'title' => $item->title,
        ]);
        return $model;
    }


    public static function FillListContraindicationsApiModel($item)
    {

        $model = new ContraindicationsApiModel([
            'id' => $item->id,
            'content' => $item->content,
            'title' => $item->title,
            'medication'=>self::FillMedicationApiModel($item->medication)
        ]);
        return $model;
    }

    public static function FillListCompanyApiModel($item)
    {

        $model = new CompanyApiModel([
            'id' => $item->id,
            'company_name' => $item->company_name,
        ]);
        return $model;
    }

    public static function FillListTimesTakeMedApiModel($item)
    {

        $model = new TimeToTakeMedApiModel([
            'id' => $item->id,
            'pattern' => $item->pattern,
        ]);
        return $model;
    }


    public static function FillTaskApiModel($item)
    {
        $model = new TaskApiModel([
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'status' => FillApiModelService::FillIdValueApiModel($item->status, TaskStatus::LabelOf($item->status)),
        ]);
        return $model;
    }

    public static function FillListBadgeApiModel($item)
    {
        $model = new BadgeApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'image' => $item->image? getImage($item->image): '',
            'description' => $item->description,
//            'awarding_time' => Carbon::createFromTimestamp(strtotime($item->awarding_time))->diffForHumans(),
//            'created_at' =>Carbon::parse($item->created_at),
//            'updated_at' => date(Constents::date_format_view_2, strtotime($item->updated_at)),
//            'status' => self::FillIdValueApiModel($item->status, ActiveInactiveStatus::LabelOf($item->status)),
        ]);
        return $model;
    }


    public static function FillMyRidesApiModel($item)
    {
        $model = new MyRideApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'level' => isset($item->level_id)? self::FillMyRideLevelApiModel($item->level_id): null,
            'date' => date(Constents::full_date_format, strtotime($item->date)),
            'distance' => $item->distance,
            'calories' => $item->calories,
            'average_speed' => $item->average_speed,
            'max_speed' => $item->max_speed,
            'cycling_duration' => date(Constents::full_date_format, strtotime($item->cycling_duration)),
            'trip_duration' => $item->trip_duration,
            'image' => $item->image ? getImage($item->image ) : '',
//            'date' =>  Carbon::createFromTimestamp(strtotime($item->date))->diffForHumans(),

        ]);
        return $model;
    }

    public static function FillProfileAdminResultModel($item)
    {
        return new ProfileAdminResult([
            'id' => $item->id,
            'first_name' => $item->fname,
            'last_name' => $item->lname,
            'phone' => $item->phone,
            'image' => $item->avatar ? getImage($item->avatar ) : '',

        ]);
    }

    public static function FillCarDetails($item)
    {
        return new CarDetails([
            'carImages' => self::FillCarImagesApiModel($item->id),
            'carType' => isset($item->car_type)
                ? self::FillIdValueApiModel($item->car_type->id, $item->car_type->car_type)
                : $item->car_type,
        ]);

    }


    public static function FillLocationApiModel($user_id)
    {
        $items = Location::where(['user_id' => $user_id])->get();

        $models = [];
        foreach ($items as $one){
            $models[] = self::FillOneLocation($one);
        }
        return $models;
    }

    public static function FillLocationAdmin($one)
    {
        return new \App\Models\API\location\LocationAdmin([
            'address' => $one->address,
            'country' => ($one->region) ? self::FillCountryApiModel($one->region) : null,
            'lat' => $one->lat,
            'lng' => $one->lng,
        ]);
    }


    public static function FillOneLocation($one)
    {
        return new \App\Models\API\location\Location([
            'id' => $one->id,
            'address' => $one->address,
            'country' => ($one->region) ? self::FillCountryApiModel($one->region) : null,
            'lat' => $one->lat,
            'lng' => $one->lng,
            'status' => isset($one->status)
                ? self::FillIdValueApiModel($one->status, UserStatus::LabelOf($one->status))
                : $one->status,
        ]);
    }

    public static function FillLocationCountryApiModel($item)
    {

        $cities = [];
        foreach ($item->cities as $one) {
            if ($one->status == ActiveInactiveStatus::active) {
                $cities[] = FillApiModelService::FillLocationCityApiModel($one);
            }
        }

        $model = new LocationApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'cities' => $cities,
        ]);

        return $model;
    }

    public static function FillOrderApiModel($item , $state = 0)
    {
        $obj_from = new stdClass();
        $obj_from->id = $item->from;
        $obj_from->region = Region::find($item->region_id_from);
        $obj_from->address = $item->address_from;
        $obj_from->lat = $item->lat_from;
        $obj_from->lng = $item->lng_from;
        $obj_from->status = UserStatus::STATUS_ACTIVE;

        $obj_to = new stdClass();
        $obj_to->id = $item->to;
        $obj_to->region = Region::find($item->region_id_to);
        $obj_to->address = $item->address_to;
        $obj_to->lat = $item->lat_to;
        $obj_to->lng = $item->lng_to;
        $obj_to->status = UserStatus::STATUS_ACTIVE;

        $user = User::find($item->user_id);

        if($state == 1) $offer = self::FillOfferApiModel($item->offers);
        else if($state == 2) $offer = self::FillOfferApiModel($item , 1);
        else if($state == 3)$offer = self::FillOfferApiModel($item , 2);
        else $offer = [];

        return new Order([
            'id' => $item->id,
            'user_profile' => self::FillProfileResultModel($user , true),
            'from' => self::FillOneLocation($obj_from),
            'to' => self::FillOneLocation($obj_to),
            'dateTime' => $item->dateTime,
            'price' => $item->price,
            'description' => $item->description,
            'isWantRide' => isset($item->isWantRide)
                ? self::FillIdValueApiModel($item->isWantRide, ($item->isWantRide == 0 ? 'No' : 'Yes'))
                : $item->isWantRide,
            'car_type' => isset($item->car_type_id)
                ? self::FillCarTypeApiModel(CarType::find($item->car_type_id))
                : $item->car_type_id,
            'status' => isset($item->status)
                ? self::FillIdValueApiModel($item->status, RequestStatus::LabelOf($item->status))
                : $item->status,
            'offers' => $offer,
            'min_offer_price' => ($item->offers->count() > 0) ? (int)$item->offers->min('price') : 0,
            'max_offer_price' => ($item->offers->count() > 0) ? (int)$item->offers->max('price') : 0,
            'is_paid' => isset($item->isPaid)
                ? self::FillIdValueApiModel($item->isPaid, ($item->isPaid == 0 ? 'No' : 'Yes'))
                : $item->isPaid,
        ]);

    }

    public static function FillOfferApiModel($items , $isOne = 0)
    {
        $offers = [];

        if($isOne == 1) {
            $items = $items->offers
                ->where('admin_id', admin()->id)
                ->where('status', OfferStatus::accepted);
        }

        if($isOne == 2) {
            $items = $items->offers
                ->where('admin_id', admin()->id);
        }

        foreach ($items as $offer) {
            $admin = Admin::find($offer->admin_id);
            $offers[] =  new Offer([
                'id' => $offer->id,
                'dg_profile' => self::FillProfileAdminResultModel($admin),
                'price' => $offer->price,
                'description' => $offer->description,
                'create_date' => $offer->created_at,
                'ET' => $offer->ُET,
                'status' => isset($offer->status)
                    ? self::FillIdValueApiModel($offer->status, OfferStatus::LabelOf($offer->status))
                    : $offer->status,
            ]);
        }

        return $offers;
    }

    public static function FillOrderOfferApiModel($items)
    {
        $offers = [];
        foreach ($items as $offer) {
            $admin = Admin::find($offer->admin_id);
            $offers[] =  new OrderOffer([
                'id' => $offer->id,
                'dg_profile' => self::FillProfileAdminResultModel($admin),
                'price' => $offer->price,
                'description' => $offer->description,
                'create_date' => $offer->created_at,
                'ET' => $offer->ُET,
                'status' => isset($offer->status)
                    ? self::FillIdValueApiModel($offer->status, OfferStatus::LabelOf($offer->status))
                    : $offer->status,
                'order' => self::FillOrderApiModel($offer->order),
            ]);
        }

        return $offers;
    }

    public static function FillCarImagesApiModel($admin_id)
    {
        $items = CarImage::where(['admin_id' => $admin_id])->get();

        $models = [];
        foreach ($items as $one){
            $models[] = new \App\Models\API\auth_admin\CarImage([
                'id' => $one->id,
                'image' => $one->image ? getImage($one->image) : '',
            ]);
        }
        return $models;
    }

    public static function FillApiResultRegisterResultModel($item, $msg = '')
    {
        return new ApiResultRegisterResult([
            'result' => new RegisterResult([
                'phone' => $item->phone,
                'fname' => $item->firstname,
                'lname' => $item->lastname,
                'message' => __($msg),
            ]),
            'isOk' => true,
            'message' => null,
        ]);
    }

    public static function FillApiResultProfileResult($item)
    {
        $model = new ApiResultProfileResult([
            'result' => $item,
            'isOk' => true,
            'message' => new ApiMessage([
                'type' => 'Success',
                'code' => ErrorCode::success,
                'content' => '',
            ])
        ]);;

        return $model;
    }

    public static function FillApiResultProfileAdminResult($item)
    {

        $model = new ApiResultProfileAdminResult([
            'result' => $item,
            'isOk' => true,
            'message' => new ApiMessage([
                'type' => 'Success',
                'code' => ErrorCode::success,
                'content' => '',
            ])
        ]);;

        return $model;
    }


    public static function FillImagesApiModel($item)
    {
        $model = new ImagesApiModel([
            'id' => $item->id,
            'url' => $item->imageUrl,
        ]);

        return $model;
    }


    public static function FillContactUsApiModel($item)
    {
        $model = new ContactUs([
            'location' => self::FillLocationAdmin($item),
            'phone' => $item->phone,
            'mobile' => $item->mobile,
        ]);
        return $model;
    }

    public static function FillAboutAppApiModel($item)
    {
        $model = new AboutApp([
            'description' => $item->description,
            'location' => $item->location,
            'phone' => $item->phone,
            'facebook' => $item->facebook_url,
            'instagram' => $item->instagram_url,
            'telegram' => $item->telegram_url,
        ]);

        return $model;
    }


    public static function FillLocationCityApiModel($item)
    {

        $regions = [];
        foreach ($item->regions as $one) {
            if ($one->status == ActiveInactiveStatus::active) {
                $regions[] = FillApiModelService::FillLocationRegionApiModel($one);
            }
        }

        $model = new CityApiModel([
            'id' => $item->id,
            'name' => $item->name,
            'regions' => $regions,
        ]);

        return $model;
    }


    public static function FillCountryApiModel($item)
    {
        $city = [];
        if ($item->city->status == ActiveInactiveStatus::active)
            $city[] = FillApiModelService::FillCityApiModel($item);



        $model = new LocationApiModel([
            'id' => $item->city->country->id,
            'name' => $item->city->country->name,
            'cities' => $city,
        ]);

        return $model;
    }


    public static function FillLocationRegionApiModel($item)
    {

        $model = new RegionApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);

        return $model;
    }

    public static function FillImages($items)
    {
        $data = [];
        foreach ($items as $one){
            $data[] = new ImageApiModel([
                'id' => $one->id,
                'image' => $one->imageUrl,
            ]);
        }

        return $data;
    }

    public static function FillIdValueApiModel($id, $value)
    {
        $model = new IdValueApiModel([
            'id' => $id,
            'value' => $value ? $value : '',
        ]);

        return $model;
    }

    public static function FillCarTypeApiModel($item)
    {
        return new CarTypeModel([
            'id' => $item->id,
            'car_type' => $item->car_type,
            'icon' => $item->icon ? getImage($item->icon) : '',
        ]);
    }

    public static function FillOrderStatusApiModel($id , $status)
    {
        return new OrderStatusListModel([
            'id' => $id,
            'status' => $status,
        ]);
    }

    public static function FillReviewApiModel($item)
    {

        $model = new ReviewApiModel([
            'id' => $item->id,
            'rate' => $item->rate,
            'content' => $item->content,
            'create_date' => date(Constents::date_format_view_3, strtotime($item->create_date)),
            'user' => self::FillReviewUserApiModel($item->user),
        ]);

        return $model;
    }

    public static function FillReviewUserApiModel($item)
    {

        $model = new ReviewUserApiModel([
            'id' => $item->id,
            'first_name' => $item->fname,
            'last_name' => $item->lname,
            'image' => $item->avatar ? getImage($item->avatar) : '',
        ]);

        return $model;
    }

    public static function FillReportReasonApiModel($item)
    {
        $model = new ReportReasonApiModel([
            'id' => $item->id,
            'title' => $item->title,
            'name' => $item->name,
        ]);

        return $model;
    }

    public static function FillListNotificationsApiModel($item)
    {

        $data = null;
        switch ($item->type) {
            case NotificationType::NEW_REQUEST:
                $model = Bike::find($item->data_id);

                if (!$model) {
                    return false;
                }
                $notify = new NewRequestNotification($model);
                $data = $notify->getCustomData();
                break;

//            case NotificationType::NEW_OFFER:
//                $model = Offer::find($item->data_id);
//
//                if (!$model) {
//                    return false;
//                }
//                $notify = new NewRequestNotification($model);
//                $data = $notify->getCustomData();
//                break;
        }

        return new ListNotifications([
            'id' => $item->id,
            'image' => $item->imageUrl,
            'title' => Lang::get('all.' .$item->title,[],user()->language),
            'body' => Lang::get('all.' .$item->content,[],user()->language),
            'data' => $data,
            'is_read' => $item->is_read ? true : false,
            'date' => $item->date,
            'type' => $item->type,
        ]);
    }

    public static function FillRequestDetailsApiModel($item)
    {
        $model = new RequestDetailsApiModel([
            'id' => $item->id,
            'request_type' => self::FillIdValueApiModel($item->request_type, RequestType::LabelOf($item->request_type)),
            'bundle' => $item->bundle ? self::FillBundleApiModel($item->bundle) : $item->bundle,
            'status' => self::FillIdValueApiModel($item->status, RequestStatus::LabelOf($item->status)),
            'create_date' => date(Constents::date_format_view_3, strtotime($item->create_date)),
            'types' => self::FillRequestAttachemnts($item->requestTypes),
            'files' => self::FillRequestAttachemnts($item->requestFiles),
        ]);
        return $model;
    }


    public static function FillDoctorPrescriptionApiModel($prescription)
    {

        $Doctor=Admin::where('id',$prescription->doctor_id)->first();


        return new ProfileResult([
            'id' => $Doctor->id,
            'firstname' => $Doctor->fname,
            'lastname' =>$Doctor->lname,
            'phone' => $Doctor->phone,
        ]);
    }
   public static function FillCostomerPrescriptionApiModel($prescription){
    $Costomer=User::where('id',$prescription->costomer_id)->first();


    return new ProfileResult([
        'id' => $Costomer->id,
        'firstname' => $Costomer->fname,
        'lastname' =>$Costomer->lname,
        'phone' => $Costomer->phone,
    ]);
}


    public static function FillProfileResultModel($item)
    {
//        stopv($item->full_name);
        return new ProfileResult([
            'id' => $item->id,
            'full_name' => $item->full_name,
            'phone' => $item->phone,
            'image' => $item->avatar ? getImage($item->avatar) : '',
            'gallery' => self::FillUserGallery($item->images),
        ]);
    }


    public static function FillMyEditProfileResultModel($item)
    {
        $user_images = UserImage::where('user_id', $item->id)->get();
        return new ProfileResult([
            'id' => $item->id,
            'full_name' => $item->full_name,
            'birthday' => $item->birthdate,
            'phone' => $item->phone,
            'weight' => $item->weight,
            'status' => self::FillIdValueApiModel($item->status, UserStatus::LabelOf($item->status)),
            'image' => $item->avatar ? getImage($item->avatar) : '',
            'gender' => self::FillIdValueApiModel($item->gender, GenderEnum::LabelOf($item->gender)),
            'study' => isset($request->study_id)? (self::FillStudyApiModel($request->study_id)): (isset($item->study_id)? self::FillStudyApiModel($item->study_id): null),
            'job' => isset($request->job_id)? (self::FillJobApiModel($request->job_id)): (isset($item->job_id)? self::FillJobApiModel($item->job_id): null),
            'city' => isset($request->city_id)? (self::FillCityApiModel($request->city_id)): (isset($item->city_id)? self::FillCityApiModel($item->city_id): null),
            'gallery' => self::FillUserGallery($user_images),
            'bio' => $item->bio,

        ]);
    }



    public static function FillCityApiModel($item_id)
    {
        $item = City::where('id', $item_id)->first();
        $model = new CityApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);
        return $model;
    }

    public static function FillStudyApiModel($item_id)
    {
        $item = Study::where('id', $item_id)->first();
        $model = new StudyApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);
        return $model;
    }

    public static function FillJobApiModel($item_id)
    {
        $item = Job::where('id', $item_id)->first();
        $model = new JobApiModel([
            'id' => $item->id,
            'name' => $item->name,
        ]);
        return $model;
    }

    public static function FillUserGallery($items)
    {
        $data = [];
        foreach ($items as $item){
            $data [] = new  UserImageApiModel([
                'id' => $item->id,
                'image' => $item->image? getImage($item->image): '',
            ]);
        }
        return $data;
    }

    public static function FillMyProfileResultModel($item)
    {
        return new ProfileResult([
            'id' => $item->id,
            'image' => $item->avatar? getImage($item->avatar): '',
            'gallery' => self::FillUserGallery($item->images),
            'full_name' => $item->full_name,
            'position' => $item->position_id? self::FillPositionApiModel($item->position_id): null,
//            'email' => $item->email,
//            'phone' => $item->phone,
//            'birthday' => $item->birthdate,
//            'gender' => self::FillIdValueApiModel($item->gender, GenderEnum::LabelOf($item->gender)),
//            'study' => $item->study_id? self::FillStudyApiModel($item->study_id): null,
//            'job' => $item->job_id? self::FillJobApiModel($item->job_id): null,
//            'city' => $item->city_id? self::FillCityApiModel($item->city_id): null,
            'bio' => $item->bio,
            'my_bike' => $item->have_bike == UserHaveBikeEnum::true? true:false,
            'my_subscription' => $item->my_subscription,
            'subscription_days_left' => $item->subscription_days_left,
            'status' => self::FillIdValueApiModel($item->status, UserStatus::LabelOf($item->status)),
        ]);
    }

}
