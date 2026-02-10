<?php

namespace App\services;

use App\enums\ErrorCode;
use App\enums\LoginApiEnum;
use App\enums\UserConfirmationType;
use App\enums\UserStatus;
use App\Models\Admin;
use App\Models\AdminMobileEmail;
use App\Models\API\auth\LoginResult;
use App\Models\API\auth\LoginResultApi;
use App\Models\API\auth_admin\LoginAdminResult;
use App\Models\API\auth_admin\LoginAdminResultApi;
use App\Models\API\other\ApiMessage;
use App\Models\API\other\ApiResult;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\Region;
use App\Models\UserMobileEmail;
use App\User;
use common\enums\ActiveInactiveStatus;
use common\enums\Constents;
use common\enums\NotificationType;
use common\enums\UserTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;


class AdminService {

    const Msg_RegistrationSuccess = "Please Enter the Confirmation Code you will receive via SMS to confirm your mobile number.";
    const Msg_Exception = "Something is wrong !!";

    public static function apiProfileCreate($request) {

        list($result, $user, $msg , $ex) = self::profileCreate($request);

        $data = FillApiModelService::FillApiResultRegisterResultModel($user, UserService::Msg_RegistrationSuccess);

        return [$result, $data, $msg , $ex];
    }

    public static function profileCreate($post) {

        $msg = [];
        $result = false;
        $model = null;
        $ex = '';

        try {
            $model = new Admin([
                'phone' => $post->phone,
                'password' => Hash::make($post->password),
                'fname' => $post->firstname,
                'lname' => $post->lastname,
                'status' => UserStatus::STATUS_ACTIVE,
                'mobile_confirmed' => 1,
                'password' => bcrypt($post->password)
            ]);

            if (($result = $model->save())) {
                AdminService::addAdminMobileEmail($model->id,$model->phone, 1);
            }

        }catch (\Exception $ex){
            $msg = AdminService::Msg_Exception;
            $ex = $ex->getMessage();
        }

        return [$result, $model, $result ? __(AdminService::Msg_RegistrationSuccess) : $msg , $ex];
    }

    public static function adminProfileUpdate($request) {
        try {
            $model = admin();

            if ($model) {
                $model->fname = (isset($request->first_name) && $request->first_name) ? $request->first_name :$model->fname;

                $model->lname = (isset($request->last_name) && $request->last_name) ?$request->last_name : $model->lname;
                $model->phone = (isset($request->phone) && $request->phone) ?$request->phone : $model->phone;
                $model->email = (isset($request->email) && $request->email) ?$request->email : $model->email;
                $model->region_id = (isset($request->region_id)) ? $request->region_id : $model->region_id;
                $model->car_type_id = (isset($request->car_type_id)) ? $request->car_type_id : $model->car_type_id;
                $model->lat = (isset($request->lat)) ? $request->lat : $model->lat;
                $model->lng = (isset($request->lng)) ? $request->lng : $model->lng;


                if ($request->image) {
                    $model->avatar = uploadImage($request->image, Admin::image_directory);
                }

                return (!$model->save()) ?
                    returnError("Error saving admin") :
                    returnSuccess("Admin has been updated");

            } else return returnError("Admin not found");

        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage() , $ex->getCode());
        }
    }


    public static function addAdminMobileEmail($admin_id,$phone, $is_primary = 0) {

        try {
            $model = new AdminMobileEmail([
                'confirm_code' => rand(10000, 99999),
                'is_confirmed' => 0,
                'is_primary' => $is_primary,
                'type' => UserConfirmationType::account_confirm,
                'admin_id' => $admin_id,
                'mobile' => $phone,
            ]);

            $model->save();
            $model->sendConfirmationCodePhone(UserConfirmationType::account_confirm);
            return [true , ''];

        }catch (\Exception $ex){
            return [false , $ex->getMessage()];
        }
    }


    public static function loginPhoneEmailValidation($request) {
        try {
            $login_model = new LoginAdminResult();

            $token = Auth::guard('admin-api')->attempt(['phone' => $request->phone , 'password' => $request->password]);
            $user = Auth::guard('admin-api')->user();

            if (!$user) {
                return [false , null , LoginApiEnum::LabelOf(LoginApiEnum::not_found)
                    . " or " . LoginApiEnum::LabelOf(LoginApiEnum::invalid_password) , ''];
            }  elseif (!$user->mobile_confirmed) {
                return [false , null , LoginApiEnum::LabelOf(LoginApiEnum::not_confirmed) , ''];
            } elseif ($user->status == UserStatus::STATUS_BANNED) {
                return [false , null , LoginApiEnum::LabelOf(LoginApiEnum::banned) , ''];
            }  elseif ($user->status == UserStatus::STATUS_INACTIVE) {
                return [false , null , LoginApiEnum::LabelOf(LoginApiEnum::not_active) , ''];
            } else {
                $login_model->resultCode = LoginApiEnum::accepted;
                $login_model->resultText = LoginApiEnum::LabelOf($login_model->resultCode);
                $login_model->token = $token;
                $login_model->profile = FillApiModelService::FillProfileAdminResultModel($user);
            }

            $result =  new LoginAdminResultApi([
                'result' => $login_model,
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => '',
                ])
            ]);

            return [true , $result , '' , ''];

        }catch (\Exception $ex){
            return [false , null , AdminService::Msg_Exception , $ex->getMessage()];
        }

    }

    public static function checkToken() {
        if (!\admin()) {
            return returnError(trans('Token expired'));
        }

        return returnSuccess(trans('Token Valid'));
    }

    public static function adminChangePhone($request) {
        $model = admin();
        try {
            $userMobile = AdminMobileEmail::where(['admin_id' => $model->id])
                                        ->where(['type' => UserConfirmationType::account_confirm])
                                        ->first();
            if(!$userMobile){
                $userMobile = new AdminMobileEmail([
                    'admin_id' => $model->id,
                    'type' => UserConfirmationType::account_confirm,
                    'is_primary' => 0,
                ]);
            }

            $userMobile->mobile = $request->newPhoneNumber;
            $userMobile->is_confirmed = 0;
            $userMobile->confirm_code = rand(10000, 99999);
            if(!$userMobile->save()) {
                return [false , null , "Error saving phone" , ''];
            }

            $model->status = UserStatus::STATUS_INACTIVE;
            $model->mobile_confirmed = 0;
            $model->phone = $request->newPhoneNumber;
            $model->save();
            $data = new ApiResult([
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => trans('Phone number is changed'),
                ]),
            ]);
            return [true , $data , '' , ''];

        }catch (\Exception $ex){
            return [false , null , AdminService::Msg_Exception , $ex->getMessage()];
        }
    }


    public static function apiLogOut(Request $request) {
        try{
            $token = Str::after($request->header('Authorization') , 'Bearer ');
            JWTAuth::setToken($token)->invalidate();
            return returnSuccess("Logged out successfully");
        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage());
        }
    }

    public static function confirmAdminPhone($request, $type = UserConfirmationType::account_confirm) {

        try {
            $model = AdminMobileEmail::where(['mobile' => $request->phone])
                ->where(['type' => $type])
                ->first();

            if ($model->is_confirmed) {
                return [false, null, "Mobile already confirmed" , ''];
            }
            if ($model->confirm_code != $request->code) {
                return [false, null, "Wrong confirmation code" , ''];
            } else {
                $user = Admin::where('id', $model->admin_id)->first();

                if (!$user) {
                    return [false, null, "Admin not found" , ''];
                }
                $model->is_confirmed = 1;
                if (!$model->save()) {
                    return [false, null, "Error saving email" , ''];
                }
                $user->mobile_confirmed = 1;
                $user->status = UserStatus::STATUS_ACTIVE;
                if (!$user->save()) {
                    return [false, null, "Error saving user" , ''];
                }

                //delete other phones
                $models = AdminMobileEmaiL::where(['admin_id' => $model->admin_id])
                    ->where('mobile', 'not' , $request->phone)
                    ->where(['type' => $type])
                    ->get();

                foreach ($models as $one) {
                    $one->delete();
                }

                $login_model = new LoginAdminResult();
                $login_model->resultCode = LoginApiEnum::accepted;
                $login_model->resultText = LoginApiEnum::LabelOf($login_model->resultCode);
                $login_model->profile = FillApiModelService::FillProfileAdminResultModel($user);
                $res = new LoginAdminResultApi([
                    'result' => $login_model,
                    'isOk' => true,
                    'message' => new ApiMessage([
                        'type' => 'Success',
                        'code' => ErrorCode::success,
                        'content' => '',
                    ])
                ]);
            }
            return [true , $res , '' , ''];
        }catch (\Exception $ex){
            return [false , null , AdminService::Msg_Exception ,$ex->getMessage()];
        }
    }

    public static function adminChangePassword($request) {

        $model = admin();

        try {


            if ((Hash::check(request('oldPassword'),$model->password)) ) {


                  $model->password =Hash::make ($request->newPassword);

                }
                elseif(!(Hash::check(request('oldPassword'),$model->password))){

                    return [false , null , "password is wrong " , ''];
                }


            if(!$model->save()) {
                return [false , null ," Error saving new password", ''];
            }


            $data = new ApiResult([
                'isOk' => true,
                'message' => new ApiMessage([
                    'type' => 'Success',
                    'code' => ErrorCode::success,
                    'content' => "Password is changed",
                ]),
            ]);
            return [true , $data , '' , ''];

        }catch (\Exception $ex){
            return [false , null , AdminService::Msg_Exception , $ex->getMessage()];
        }
    }


    public static function resendCode($request) {

        try {
            $model = AdminMobileEmail::where(['mobile' => $request->phone])
                ->where(['type' => UserConfirmationType::account_confirm])
                ->where(['is_confirmed' => 0])
                ->first();

            if ($model) {
                $model->confirm_code = rand(10000, 99999);
                if (!$model->save()) {
                    return returnError("Error saving code");

                }
            } else {
                return returnError("User is already activated");
            }

            return returnSuccess('Code Resent');
        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage());
        }
    }

    public static function deleteAccount($request) {

        try{
            $model = admin();
            if ($request->phone != $model->phone) {
                returnError("Wrong phone");
            }

            if($model){
                $token = Str::after($request->header('Authorization') , 'Bearer ');
                JWTAuth::setToken($token)->invalidate();
                $model->delete();
            } else {
                returnError("Admin not found");
            }
            return returnSuccess("Deleted");

        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage());
        }
    }


    public static function AdminForgetPassword($request) {

        try {
            $admin = Admin::where(['phone' => $request->phone])->first();


            if (!$admin) {
                return returnError("Admin not found");
            }

            $model = AdminMobileEmail::where(['mobile' => $request->phone])
                ->where(['type' => UserConfirmationType::forgot_password])
                ->where(['is_confirmed' => 0])
                ->first();


            if (!$model) {
                $model = new AdminMobileEmail([
                    'confirm_code' => rand(10000, 99999),
                    'is_confirmed' => 0,
                    'is_primary' => 0,
                    'admin_id' => $admin->id,
                    'mobile' => $request->phone,
                    'type' => UserConfirmationType::forgot_password
                ]);
                $model->save();
            }

            //send code
            $msg = UserService::Msg_RegistrationSuccessForgetPasswordPhone;
            $model->sendConfirmationCodePhone(UserConfirmationType::forgot_password);

            return returnSuccess($msg);
        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage());
        }
    }


    public static function addCarImage($request){
        try {
            $model = new CarImage();
            if ($model) {
                $model->image = uploadImage($request->image, CarImage::image_directory);
                $model->admin_id = admin()->id;
                if (!$model->save()) {
                    return returnError("Error saving user");
                }
                return returnSuccess("Your Car Image Added");
            } else {
                return returnError("Admin not found");
            }

        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage());
        }
    }

    public static function deleteCarImage($request) {

        try{

            $carImage = CarImage::where('id' , $request->car_image_id)
                ->where('admin_id' , admin()->id)->first();


            if(!$carImage) {
                return returnError("This id of image is not found");
            }

            $carImage->delete();

            return returnSuccess("Deleted");

        }catch (\Exception $ex){
            return returnError(AdminService::Msg_Exception , $ex->getMessage() , $ex->getCode());
        }
    }


}
