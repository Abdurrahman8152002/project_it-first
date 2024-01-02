@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sales Report</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->user_id }}</td>
            <td>{{ $sale->total_price }}</td>
            <td>{{ $sale->status }}</td>
            <td>{{ $sale->created_at }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
