<?php

namespace App\Http\Controllers;

use App\Models\favorite;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class Medicine_Order extends Controller
{
    public function order(Request $request){

        $token = $request->header('token');
        $user = JWTAuth::setToken($token)->authenticate();


        if (!$user || $user->status != 1) {
            return response()->json(['error' => 'Invalid credentials or inactive user'], 401);
        }

        $data = $request->json()->all();

        $totalPrice = 0;
        $orderDetails = [];
        $remainingQuantities = [];
        $totalAvailableQuantities = [];
        $errors = [];

        foreach ($data as $item) {
            $medicineId = $item['id'];
            $quantityNeeded = $item['quantity'];


            $totalAvailableQuantities[$medicineId] = 0;


            $storages = [Medicine::class];

            foreach ($storages as $storage) {

                $storageInstance = resolve($storage);


                $medicineInDB = $storageInstance::where('id', $medicineId)->first();

                if ($medicineInDB && $medicineInDB->available_quantity > 0) {

                    $totalAvailableQuantities[$medicineId] += $medicineInDB->available_quantity;
                }
            }

            if ($quantityNeeded > $totalAvailableQuantities[$medicineId]) {

                $errors[] = [
                    'error' => 'Not enough quantity available for: ' . $medicineId,
                    'total_available_quantity' => $totalAvailableQuantities[$medicineId]
                ];
            }
        }

        if (!empty($errors)) {

            return response()->json($errors, 400);
        }

        foreach ($data as $item) {
            $medicineId = $item['id'];
            $quantityNeeded = $item['quantity'];

            foreach ($storages as $storage) {
                 $storageInstance = resolve($storage);


                $medicineInDB = $storageInstance::where('id', $medicineId)->first();

                if ($medicineInDB && $medicineInDB->available_quantity > 0) {
                    $quantityFromThisStorage = min($medicineInDB->available_quantity, $quantityNeeded);


                    $medicineInDB->available_quantity -= $quantityFromThisStorage;
                    $medicineInDB->save();


                    $totalPrice += $quantityFromThisStorage * $medicineInDB->price;


                    $orderDetails[] = [
                        'medicine' => $medicineId,
                        'quantity' => $quantityFromThisStorage,
                        'price' => $medicineInDB->price,
                        'total' => $quantityFromThisStorage * $medicineInDB->price,

                    ];


                    $quantityNeeded -= $quantityFromThisStorage;
                }


                if ($quantityNeeded == 0) {
                    break;
                }
            }
        }


        $order = Order::create([
            'user_id' => $user->id,

            'total_price' => $totalPrice,
            'status' => -1,
        ]);
        foreach ($orderDetails as $item) {
            $medicineId = $item['medicine'];
            $quantityNeeded = $item['quantity'];
            $price = $item['price'];

            $order->medicines()
                ->syncWithoutDetaching([$medicineId =>
                    ['quantity' => DB::raw($quantityNeeded),
                        'price' => DB::raw($price)]]);
        }

        $admin = User::with('role')->whereHas('role', function($q){
                $q->where('roles.name','=', 'Admin');
            })->first();
        echo $admin;


        Notification::create([
            'user_id'=>$admin->id,
            'message'=>'you have a new order ',
            'seen'=> false
        ]);

        return response()->json([
            'success' => 'Order processed successfully',
            'total_price' => $totalPrice,
            'order_details' => $orderDetails,
            'remaining_quantities' => $remainingQuantities
        ], 200);

    }

    public function changeStatus(Request $request)
    {

        $orderId = $request->input('order_id');
        $newStatus = $request->input('status');

        // Find the order by id
        $order = Order::find($orderId);


        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }


        $order->status = $newStatus;
        $order->save();


        $order->load('medicines');
        $user = User::with('role')->whereHas('role', function($q){
            $q->where('roles.name','=', 'User');
        })->first();


        $message = '';
        if ($newStatus == 0) {
            $message = 'Order is on the way';
        } elseif ($newStatus == 1) {
            $message = 'Order is being delivered';
        }
        Notification::create([
            'user_id'=>$user->id,
            'message'=>$message,
            'seen'=> false
        ]);

        $response = [
            'success' => 'Order status updated successfully',
            'order_details' => $order->toArray(),
        ];



        return response()->json($response, 200);
    }


    public function show(Request $request)
    {
return DB::table('medicine_order')
        ->join('orders', 'medicine_order.order_id', '=', 'orders.id')->get();

    }

    public function index(Request $request){

        $token = $request->header('token');
        $user = JWTAuth::setToken($token)->authenticate();
       return DB::table('medicine_order')
            ->join('orders', 'medicine_order.order_id', '=', 'orders.id')
           ->where('orders.user_id', '=', $user->id)
            ->get();



    }
    public function getNotification(Request $request)
    {
        // Get the token from the request
        $token = $request->header('token');

        // Authenticate the user
        $user = JWTAuth::setToken($token)->authenticate();


        if (!$user || $user->status != 1) {
            return response()->json(['error' => 'Invalid credentials or inactive user'], 401);
        }


        $notifications = Notification::where('user_id', $user->id)
            ->where('seen', 0)
            ->get();


        return response()->json($notifications);
    }
    public function putToFavorite (Request $request)
    {

        $token = $request->header('token');
        $medicine_id = $request->input('medicine_id');


        $user = JWTAuth::setToken($token)->authenticate();


        if (!$user || $user->status != 1) {
            return response()->json(['error' => 'Invalid credentials or inactive user'], 401);
        }

        $favorite = favorite::where('user_id', $user->id)
            ->where('medicine_id', $medicine_id)
            ->first();

        if ($favorite) {

            return response()->json(['message' => 'The medicine is already in the favorites']);
        } else {

            favorite::create([
                'user_id' => $user->id,
                'medicine_id' => $medicine_id
            ]);


            return response()->json(['message' => 'The medicine has been added to the favorites']);
        }
    }



}

