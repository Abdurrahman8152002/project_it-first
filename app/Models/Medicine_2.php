<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine_2 extends Model
{
    protected $table = 'medicines_2';
    protected $fillable = ['scientific_name', 'name', 'category', 'brand', 'available_quantity', 'expiry_date', 'price', 'description'];
}
