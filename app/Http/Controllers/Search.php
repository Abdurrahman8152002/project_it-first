<?php

namespace App\Http\Controllers;

use App\Models\Medicine;

use Illuminate\Http\Request;

class Search extends Controller

{
    public function search(Request $request)
    {

        $category = $request->input('category');
        $scientific_name = $request->input('name');


        $searchField = $scientific_name ? 'scientific_name' : 'category';
        $searchValue = $scientific_name ? $scientific_name : $category;


        $medicines = Medicine::where($searchField, $searchValue)->with('storages')->get();


        $data = [
            'medicines' => $medicines->map(function ($medicine) {
                return [
                    'medicineId' => $medicine->id,
                    'price' => $medicine->price,
                    'storages' => $medicine->storages->pluck('name'),
                ];
            }),
        ];


        return response()->json($data);
    }

}
