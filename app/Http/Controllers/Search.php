<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Search extends Controller
{
    public function search(Request $request)
    {
        $json_data = json_decode(file_get_contents(public_path('medicine_storge_1.json')), true);
        $json_data2 = json_decode(file_get_contents(public_path('medicine_storge_2.json')), true);
        $category = $request->input('category');
        $scientific_name = $request->input('name');

        $results = [];

        if ($scientific_name != null) {
            foreach ($json_data as $data) {
                if ($data['scientific_name'] == $scientific_name) {
                    $data['storage'] = 'medicine_storge_1.json';
                    $results[] = $data;
                }
            }

            foreach ($json_data2 as $data) {
                if ($data['scientific_name'] == $scientific_name) {
                    $data['storage'] = 'medicine_storge_2.json';
                    $results[] = $data;
                }
            }
        } else if ($category != null) {
            foreach ($json_data as $data) {
                if ($data['category'] == $category) {
                    $data['storage'] = 'medicine_storge_1.json';
                    $results[] = $data;
                }
            }

            foreach ($json_data2 as $data) {
                if ($data['category'] == $category) {
                    $data['storage'] = 'medicine_storge_2.json';
                    $results[] = $data;
                }
            }
        }

        if (count($results) > 0) {
            return response()->json(['msg' => 'Found it', 'data' => $results]);
        } else {
            return response()->json(['msg' => 'Not found']);
        }
    }
}
