<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Medicine_2;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $item) {
            Medicine::create($item);
        }

        return response()->json(['message' => 'Data inserted successfully.']);
    }


    public function store_2(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $item) {
            Medicine_2::create($item);
        }

        return response()->json(['message' => 'Data inserted successfully.']);
    }


}


