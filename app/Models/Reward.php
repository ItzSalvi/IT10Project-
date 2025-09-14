<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'points_req', 
        'stock', 
        'status',
        'description'
    ];

    protected $casts = [
        'points_req' => 'integer',
        'stock' => 'integer',
        'status' => 'boolean',
    ];

    public function redemptionItems()
    {
        return $this->hasMany(RedemptionItem::class);
    }

    public function isAvailable()
    {
        return $this->status && $this->stock > 0;
    }
}

