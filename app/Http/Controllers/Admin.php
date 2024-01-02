<?php

namespace App\Http\Controllers;

use http\Client\Response;
use Illuminate\Http\Request;

class Admin extends Controller
{
    public function add_medicine(Request $request)
    {
        $scientific_name = $request->input('scientific_name');

        $name = $request->input('name');
//اعملو ب enum
        $category = $request->input('category');

        $brand = $request->input('brand');

        $available_quantity = $request->input('available_quantity');

        $expiry_date = $request->input('expiry_date');

        $price = $request->input('price');

        $description = $request->input('description');
        if (!isset($scientific_name) || !isset($name) || !isset($category) || !isset($brand) || !isset($available_quantity) || !isset($expiry_date) || !isset($price) || !isset($description)) {
            return Response()->json(['msg' => 'some informations are missing']);
        }

        $json_data = file_get_contents('C:\wamp64\www\project\public\medicine_storge_1.json');
        $medicines = json_decode($json_data, true);

        if ($medicines !== null) {
            foreach ($medicines as $medicine) {
                if ($medicine['name'] == $name) {
                    return Response()->json(['msg' => 'this medicine already exists']);
                }
            }
        }


            $payload = [
                "scientific_name" => $scientific_name,
                "name" => $name,
                "category" => $category,
                "brand" => $brand,
                "available_quantity" => $available_quantity,
                "expiry_date" => $expiry_date,
                "price" => $price,
                "description"=>$description
            ];








$dataArray[] = $payload;


$jsonData = json_encode($dataArray);



        return Response()->json(['msg' => 'added successfully']);
        }


}
