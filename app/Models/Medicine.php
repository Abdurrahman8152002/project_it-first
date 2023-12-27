<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['scientific_name', 'name', 'category', 'brand', 'available_quantity', 'expiry_date', 'price', 'description'];
}
