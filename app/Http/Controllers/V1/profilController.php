<?php

namespace App\Http\Controllers\V1;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class profilController
{
    public function getAllProfil()
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

    public function createProfil() {}
}
