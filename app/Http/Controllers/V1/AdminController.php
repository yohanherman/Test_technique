<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CreateProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;

class AdminController
{
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
    }

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
        ], 201);
    }

    public function createProfile(CreateProfileRequest $request)
    {
        $profile = Profil::create([
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'image' => $request->image,
            'statuses_id' => $request->statuses_id
        ]);
        if ($profile) {
            return response()->json([
                'profile' => $profile,
                'success' => true,
                'status' => 200
            ], 201);
        }
        return response()->json([
            'profile' => $profile,
            'success' => false,
            'status' => 501
        ], 500);
    }

    public function updateProfile(int $id, UpdateProfileRequest $request)
    {
        $profile = Profil::find($id);
        // dd($profile);

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }
        $profile->lastname = $request->lastname;
        $profile->firstname = $request->firstname;
        $profile->image = $request->image;
        $profile->statuses_id =  $request->statuses_id;
        $profile->save();

        return response()->json([
            'profile' => $profile,
            'success' => true,
            'status' => 200
        ], 200);
    }

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
