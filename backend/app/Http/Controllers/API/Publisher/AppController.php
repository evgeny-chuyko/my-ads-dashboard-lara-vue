<?php

namespace App\Http\Controllers\API\Publisher;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\CreateAppRequest;
use App\Http\Requests\App\UpdateAppRequest;
use App\Http\Resources\AppResource;
use App\Models\App;
use App\Services\App\AppService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct(
        private AppService $appService
    ) {}

    /**
     * @OA\Get(
     *     path="/apps",
     *     summary="Get user's apps",
     *     description="Retrieve all applications owned by the authenticated user",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user's applications",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="My App"),
     *                 @OA\Property(property="description", type="string", example="App description"),
     *                 @OA\Property(property="status", type="string", example="active", enum={"active", "paused", "archived"}),
     *                 @OA\Property(property="impressions", type="integer", example=1000),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User is banned",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Your account has been banned.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $apps = $this->appService->getUserApps($request->user());

        return AppResource::collection($apps);
    }

    /**
     * @OA\Post(
     *     path="/apps",
     *     summary="Create new app",
     *     description="Create a new application for the authenticated user",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="My New App", description="Application name"),
     *             @OA\Property(property="description", type="string", example="This is my new application", description="Application description (optional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Application created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="My New App"),
     *             @OA\Property(property="description", type="string", example="This is my new application"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="impressions", type="integer", example=0),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The name field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(CreateAppRequest $request)
    {
        $app = $this->appService->createApp(
            $request->user(),
            $request->validated()
        );

        return (new AppResource($app))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/apps/{id}",
     *     summary="Get app details",
     *     description="Retrieve details of a specific application (only if owned by user)",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Application ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Application details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="My App"),
     *             @OA\Property(property="description", type="string", example="App description"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="impressions", type="integer", example=1000),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the app owner",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Application not found"
     *     )
     * )
     */
    public function show(App $app)
    {
        $this->authorize('view', $app);

        return new AppResource($app);
    }

    /**
     * @OA\Put(
     *     path="/apps/{id}",
     *     summary="Update app",
     *     description="Update application details (only if owned by user)",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Application ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated App Name", description="Application name (optional)"),
     *             @OA\Property(property="description", type="string", example="Updated description", description="Application description (optional)"),
     *             @OA\Property(property="status", type="string", example="paused", enum={"active", "paused", "archived"}, description="Application status (optional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Application updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated App Name"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="status", type="string", example="paused"),
     *             @OA\Property(property="impressions", type="integer", example=1000),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the app owner"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Application not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdateAppRequest $request, App $app)
    {
        $this->authorize('update', $app);

        $app = $this->appService->updateApp($app, $request->validated());

        return new AppResource($app);
    }

    /**
     * @OA\Delete(
     *     path="/apps/{id}",
     *     summary="Delete app",
     *     description="Delete an application (only if owned by user)",
     *     tags={"Apps"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Application ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Application deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="App deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the app owner"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Application not found"
     *     )
     * )
     */
    public function destroy(App $app)
    {
        $this->authorize('delete', $app);

        $this->appService->deleteApp($app);

        return response()->json([
            'message' => 'App deleted successfully',
        ]);
    }
}
