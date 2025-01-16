<?php

namespace App\Http\Controllers\V1;

use App\Models\Profil;
use Illuminate\Support\Facades\DB;


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
