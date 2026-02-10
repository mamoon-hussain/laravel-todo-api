<?php


namespace App\Http\Controllers\API;
use App\services\AppInfoService;

class AppInfoController
{
    /**
     * @OA\Get(path="/app-info/about-us",
     *     tags={"About"},
     *     summary="About App & Contact info",
     *     @OA\Parameter(
     *         name="Language",
     *         in="header",
     *         description="(en or ar) If left empty it is English",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response = 200,
     *         description = "Success response",
     *         @OA\JsonContent(ref="#/components/schemas/AboutAppResult"),
     *     ),
     * )
     */
    public function about_us()
    {
        return AppInfoService::AboutUs();
    }
}

