<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     title="MyAds Dashboard API",
 *     version="1.0.0",
 *     description="API for managing advertising applications with role-based access control (Admin/Publisher)",
 *     @OA\Contact(
 *         email="admin@myads.com",
 *         name="MyAds Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:5174/api",
 *     description="Frontend Proxy (Vite)"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum",
 *     description="Laravel Sanctum token authentication. Format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="User registration, login, and logout operations"
 * )
 * 
 * @OA\Tag(
 *     name="Apps",
 *     description="Application management endpoints (Publisher role)"
 * )
 * 
 * @OA\Tag(
 *     name="Admin - Users",
 *     description="User management endpoints (Admin only)"
 * )
 * 
 * @OA\Tag(
 *     name="Admin - Apps",
 *     description="View all applications (Admin only)"
 * )
 * 
 * @OA\Tag(
 *     name="Admin - Stats",
 *     description="Global platform statistics (Admin only)"
 * )
 * 
 * @OA\Tag(
 *     name="Admin - Audit",
 *     description="Audit logs (Admin only)"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests;
}
