<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController
{

    /**
     * @OA\Get(
     *      path="/api/admin/profiles",
     *      operationId="getProfileListByadmin",
     *      tags={"Profiles"},
     *      summary="Get list of profiles no matter what the status is.  ",
     *      description="Returns list profiles in condition to be authenticated and have an admin role.",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=500, description="internal servor error"),
     *     )
     *
     * Returns list of projects
     */
    public function getAllProfile()
    {
        $datas = DB::table('profils')
            ->join('statuses', 'statuses.id', '=', 'profils.statuses_id')
            ->get();
        // dd($datas);
        if ($datas && !empty($datas)) {
            return response()->json([
                '$datas' => $datas,
                'success' => true,
                'status' => 200
            ], 200);
        }
        return response()->json([
            '$datas' => $datas,
            'success' => false,
            'status' => 501
        ], 500);
    }


    /**
     * @OA\Get(
     *      path="/api/admin/profiles/{id}",
     *      operationId="getProfileById",
     *      tags={"Profiles"},
     *      summary="Get profile information",
     *      description="Returns specific profile datas",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="Profile id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function getSingleProfile(int $id)
    {
        $profile = Profil::find($id);
        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }
        return response()->json([
            'profile' => $profile,
            'success' => true,
            'status' => 200
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/admin/profiles",
     *     operationId="createProfile",
     *     tags={"Profiles"},
     *     summary="Create a new profile",
     *     description="creates a new profile with required fields(lastname, firstname), an optional field(image), and a statuses_id that is 1 (active) by default.",
     *     security={
     *        {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lastname", "firstname"},
     *             @OA\Property(property="lastname", type="string", example="john"),
     *             @OA\Property(property="firstname", type="string", example="doe"),
     *             @OA\Property(property="image", type="string", nullable=true, example="profile.jpg"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="profile successfully created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable content"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal servor error"
     *     ),
     * )
     */
    function createProfile(CreateProfileRequest $request)
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            // je genere un nom unique pour le fichier
            $fileName = time() . '-' . uniqid() . '.' . $extension;
            $path = $file->storeAs('profiles', $fileName, 'public');

            // Création du profil avec l'image
            $profile = Profil::create([
                'lastname' => $request->lastname,
                'firstname' => $request->firstname,
                'image' => 'profiles/' . $fileName, // Stocker le chemin relatif à 'storage/app/public'
            ]);

            return response()->json([
                'profile' => $profile,
                'success' => true,
                'status' => 201
            ], 201);
        }
        // Si aucune image n'est envoyée, on crée le profil sans image
        $profile = Profil::create([
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
        ]);

        return response()->json([
            'profile' => $profile,
            'success' => true,
            'status' => 201
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/profiles/{id}",
     *     operationId="updateProfile",
     *     tags={"Profiles"},
     *     summary="Update an existing profile",
     *     description="Updates an existing profile with required fields (lastname, firstname), an optional field (image), and a required statuses_id that must be one of the following values: 1 (active), 2 (pending), or 3 (inactive).",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the profile to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lastname", "firstname", "statuses_id"},
     *             @OA\Property(property="lastname", type="string", example="john"),
     *             @OA\Property(property="firstname", type="string", example="doe"),
     *             @OA\Property(property="image", type="string", nullable=true, example="profile.jpg"),
     *             @OA\Property(
     *                 property="statuses_id",
     *                 type="integer",
     *                 enum={1, 2, 3},
     *                 description="Required status, must be 1 (active), 2 (pending), or 3 (inactive)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile successfully updated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable content"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     *   
     * )
     */
    public function updateProfile(int $id, Request $request)
    {
        $profile = Profil::find($id);

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }
        $profile->update($request->except('image'));
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($profile->image && Storage::disk('public')->exists($profile->image)) {
                Storage::disk('public')->delete($profile->image);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            //je génére un nom unique pour le fichier
            $fileName = time() . '-' . uniqid() . '.' . $extension;
            $path = $file->storeAs('profiles', $fileName, 'public');
            $profile->image = 'profiles/' . $fileName; // Stocke le chemin relatif à 'storage/app/public'
        }
        $profile->save();

        return response()->json([
            'profile' => $profile,
            'success' => true,
            'status' => 200
        ], 200);
    }



    /**
     * @OA\Delete(
     *     path="/api/admin/profiles/{id}",
     *     operationId="deleteProfile",
     *     tags={"Profiles"},
     *     summary="Delete a profile",
     *     description="Deletes a profile by its ID.",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the profile to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Profile successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profile not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */
    public function deleteProfile(int $id)
    {
        $profile = Profil::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Profile not found',
            ], 404);
        }
        $profile->delete();
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => "profil successfully deleted"
        ], 204);
    }
}
