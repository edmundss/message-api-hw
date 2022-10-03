<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreNotificationRequest;
use App\Http\Requests\V1\BulkStoreNotificationRequest;
use App\Http\Requests\V1\UpdateNotificationRequest;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use App\Http\Resources\V1\NotificationResource;
use App\Http\Resources\V1\NotificationCollection;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     tags={"Notification"},
     *     path="/notification",
     *     security={{"sanctum":{}}},
     *     description="Display list of notifications",
     *     @OA\Parameter(
     *          name="page",
     *          description="The page number",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="query",
     *          required=false
     *     ),
     *     @OA\Parameter(
     *          name="clientId",
     *          description="Provide a unique ID for specified client to filter messages",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          ),
     *          in="query",
     *          required=false
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Paginated list of notifications",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function index()
    {
        return new NotificationCollection(Notification::paginate());
    }

    /**
     * Store a newly created notification in storage.
     *
     * @param  \App\Http\Requests\StoreNotificationRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     tags={"Notification"},
     *     path="/notification",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="Notification object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Notification")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Notification")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *     )
     * )
     */
    public function store(StoreNotificationRequest $request)
    {
        return new NotificationResource(Notification::create($request->all()));
    }

    /**
     * Store a multiple newly created notifications in storage.
     *
     * @param  \App\Http\Requests\StoreNotificationRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *   tags={"Notification"},
     *   security={{"sanctum":{}}},
     *   path="/notification/bulk",
     *   summary="Notification bulk store",
     *   @OA\RequestBody(
     *     description="Messages to store",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Notification")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="OK",
     *     @OA\MediaType(mediaType="application/json")
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function bulkStore(BulkStoreNotificationRequest $request)
    {
        $input = collect($request->all())->map(function($item, $key) {
            return Arr::except($item, ['clientId']);
        });

        Notification::insert($input->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     tags={"Notification"},
     *     path="/notification/{id}",
     *     security={{"sanctum":{}}},
     *     description="Returns single notification",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this notification",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          ),
     *          in="path",
     *          required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Notification")
     *     ),
     *     @OA\Response(response="404", description="Notification not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     */
    public function show(Notification $notification)
    {
        return new NotificationResource($notification);
    }
}
