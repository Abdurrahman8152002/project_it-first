<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class Sales_Controller extends Controller
{
    public function generateReport(Request $request)
{

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');


    $sales = Order::whereBetween('created_at', [$startDate, $endDate])->get();


    $totalOrders = $sales->count();
    $totalPrice = $sales->sum('total_price');


    $data = [
        'totalOrders' => $totalOrders,
        'totalPrice' => $totalPrice,
        'sales' => $sales->map(function ($sale) {
            return [
                'orderId' => $sale->id,
                'price' => $sale->total_price,
            ];
        }),
    ];


    return response()->json($data);
}
public function generateReportUser(Request $request)
{

    $token = $request->header('token');


    $user = JWTAuth::setToken($token)->authenticate();

    $userId = $user->id;


    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');


    $sales = Order::where('user_id', $userId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();


    $totalOrders = $sales->count();
    $totalPrice = $sales->sum('total_price');


    $data = [
        'totalOrders' => $totalOrders,
        'totalPrice' => $totalPrice,
        'sales' => $sales->map(function ($sale) {

            $status = '';
            switch ($sale->status) {
                case -1:
                    $status = 'Waiting to send';
                    break;
                case 0:
                    $status = 'Order is on the way';
                    break;
                case 1:
                    $status = 'Order is delivered';
                    break;
            }

            return [
                'orderId' => $sale->id,
                'price' => $sale->total_price,
                'status' => $status,
            ];
        }),
    ];


    return response()->json($data);
}

}
