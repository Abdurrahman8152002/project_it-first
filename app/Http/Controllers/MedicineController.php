<?php

namespace App\Http\Controllers;


use App\Models\Medicine;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);


        $categories = ['Stomach Medicines', 'Head Medicines', 'Pain Killers', 'Cancer Medicines', 'Respiratory Medicines', 'Skin Medicines', 'Heart Medicines', 'Diabetes Medicines', 'Mental Health Medicines', 'Eye Medicines'];

        foreach ($data as $item) {

            if (!isset($item['scientific_name'], $item['name'], $item['brand'], $item['available_quantity'], $item['expiry_date'], $item['price'], $item['description'], $item['category'])) {
                return response()->json(['message' => 'Missing field in data.'], 400);
            }


            if (!in_array($item['category'], $categories)) {
                return response()->json(['message' => 'Invalid category.'], 400);
            }


            $storage = Storage::find($item['storage_id']);
            if (!$storage) {
                return response()->json(['message' => 'Storage not found.'], 404);
            }

            $medicine = Medicine::where('name', $item['name'])
                ->whereHas('storages', function ($query) use ($item) {
                    $query->where('storages.id', $item['storage_id']);
                })->first();

            if ($medicine) {

                $medicine->available_quantity += $item['available_quantity'];
                $medicine->save();
            } else {

                $medicine = Medicine::create($item);
            }


            $storage->medicines()->syncWithoutDetaching([$medicine->id => ['quantity' => DB::raw('quantity + ' . $item['available_quantity'])]]);
        }

        return response()->json(['message' => 'Data inserted successfully.']);
    }



    public function getMedicinesOrStorages(Request $request)
    {
        $output = collect();
        $totalQuantity = 0;

        if ($request->has('medicine_id')) {
            // Get a specific medicine
            $medicine = Medicine::find($request->medicine_id);

            if (!$medicine) {
                return response()->json(['message' => 'Medicine not found.'], 404);
            }


            $medicine->load('storages');


            $output = $medicine->storages->map(function ($storage) use ($medicine) {
                $medicineDetails = $medicine->toArray();
                $medicineDetails['quantity_in_this_storage'] = $storage->pivot->quantity;
                return [
                    'storage_id' => $storage->id,
                    'storage_name' => $storage->name,
                    'medicine_details' => $medicineDetails
                ];
            });

            $totalQuantity = $medicine->storages->sum(function ($storage) {
                return $storage->pivot->quantity;
            });
        } elseif ($request->has('storage_id')) {
            // Get a specific storage
            $storage = Storage::find($request->storage_id);

            if (!$storage) {
                return response()->json(['message' => 'Storage not found.'], 404);
            }

            $storage->load('medicines');


            $output = $storage->medicines->map(function ($medicine) use ($storage) {
                $medicineDetails = $medicine->toArray();
                $medicineDetails['quantity_in_this_storage'] = $medicine->pivot->quantity;
                return [
                    'storage_id' => $storage->id,
                    'storage_name' => $storage->name,
                    'medicine_details' => $medicineDetails
                ];
            });


            $totalQuantity = $storage->medicines->sum(function ($medicine) {
                return $medicine->pivot->quantity;
            });
        } else {
            return response()->json(['message' => 'Please provide a medicine_id or storage_id.'], 400);
        }

        return response()->json([
            'total_quantity' => $totalQuantity,
            'medicines' => $output
        ]);
    }



}


