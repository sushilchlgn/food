<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'description', 'price',
        'category', 'image_url', 'is_available',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}