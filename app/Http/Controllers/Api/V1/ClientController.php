<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreClientRequest;
use App\Http\Requests\V1\UpdateClientRequest;
use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ClientResource;
use App\Http\Resources\V1\ClientCollection;
use App\Http\Resources\V1\NotificationCollection;

class ClientController extends Controller
{
    /**
     * Display a paginated listing of clients.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     tags={"Client"},
     *     path="/client",
     *     security={{"sanctum":{}}},
     *     description="Returns paginated list of clients.",
     *     @OA\Parameter(
     *          name="page",
     *          description="The page number",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="query",
     *          required=false
     *     ),
     *     @OA\Response(response="200", description="ok"),
     * )
     */

    public function index()
    {
        return new ClientCollection(Client::paginate());
    }

    /**
     * Store a newly created client in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     tags={"Client"},
     *     path="/client",
     *     @OA\RequestBody(
     *         description="Client object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *     )
     * )
     */
    public function store(StoreClientRequest $request)
    {
        return new ClientResource(Client::create($request->all()));
    }

    /**
     * Display the specified client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     tags={"Client"},
     *     path="/client/{id}",
     *     description="Returns single client",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this client",
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
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(response="404", description="Client not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     *
     * @OA\Put(
     *     tags={"Client"},
     *     path="/client/{id}",
     *     description="Overwrite the specified client in storage by providing all attributes",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this client",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          ),
     *          in="path",
     *          required=true
     *     ),
     *     @OA\RequestBody(
     *         description="Client object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(response="404", description="Client not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     *
     * @OA\Patch(
     *     tags={"Client"},
     *     path="/client/{id}",
     *     description="Patch the specified client in storage by providing one or more attributes",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this client",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          ),
     *          in="path",
     *          required=true
     *     ),
     *     @OA\RequestBody(
     *         description="Client object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(response="404", description="Client not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     */

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->all());
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     tags={"Client"},
     *     path="/client/{id}",
     *     description="Removes single client from storage",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this client",
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
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(response="404", description="Client not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     */
    public function destroy(Client $client)
    {
        $client->delete();
    }

    /**
     * Get client's notifications.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     tags={"Client"},
     *     path="/client/{id}/notifications",
     *     security={{"sanctum":{}}},
     *     description="Display paginated list of notifications for specified client",
     *     @OA\Parameter(
     *          name="id",
     *          description="The ID specific to this client",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          ),
     *          in="path",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          description="The page number",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="query",
     *          required=false
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(response="404", description="Client not found"),
     *     @OA\Response(response="500", description="Server error. Most likely id is not in propper UUID format. See error message for details.")
     * )
     */
    public function getNotifications(Client $client)
    {

        return new NotificationCollection($client->notifications()->paginate());
    }
}
