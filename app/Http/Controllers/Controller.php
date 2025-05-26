<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Dokumentasi API Berbagi Bites Jogja",
 *      description="Dokumentasi API Berbagi Bites Jogja",
 *      @OA\Contact(
 *          email="kamaluddin.arsyad17@gmail.com"
 *      ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Dokumentasi API Berbagi Bites Jogja"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="apiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 * )
 */
abstract class Controller
{
    //
}
