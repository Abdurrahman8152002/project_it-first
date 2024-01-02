<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;





class Medicine extends Model
{
    protected $fillable = ['scientific_name', 'name', 'category', 'brand', 'available_quantity', 'expiry_date', 'price', 'description'];

    protected $casts = [
        'available_quantity' => 'integer',
    ];
    public function storages()
    {
        return $this->belongsToMany(Storage::class)->withPivot('quantity');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

public function favorite(){
        return $this->hasMany(favorite::class);
}
}

