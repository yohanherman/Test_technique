<?php

namespace App\Http\Controllers\V1;

use App\Models\Profil;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Schema(
 *     schema="Profile",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="lastname", type="string", example="Johane Doe"),
 *     @OA\Property(property="firstname", type="string", example="johane Doe2"),
 *     @OA\Property(property="image", type="file", example="johane.jpeg"),
 *     @OA\Property(property="statuses_id",
 *                  type="integer", 
 *                  example="2",
 *                  description="Foreign key referencing the status in the statuses table (1: active, 2: pending, 3: inactive)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-16T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-16T12:00:00Z")
 * )
 */
class profilController
{
    /**
     * @OA\Get(
     *      path="/api/profiles",
     *      operationId="getProfileList",
     *      tags={"Profiles"},
     *      summary="Get list of profiles where statuses_id = 1 (active)",
     *      description="Returns list of profiles",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=500, description="Internal Server Error"),
     *     )
     *
     * Returns list of projects
     */
    public function getAllProfile()
    {
        $datas = DB::table('profils')
            ->select('id', 'lastname', 'firstname', 'image', 'created_at', 'updated_at')
            ->where('statuses_id', 1)
            ->get();
        // dd($datas);

        if (isset($datas) && !empty($datas)) {
            $response = [
                'datas' => $datas,
                'success' => true,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
        $response = [
            'datas' => $datas,
            'success' => false,
            'status' => 500
        ];
        return response()->json($response, 500);
    }
}
