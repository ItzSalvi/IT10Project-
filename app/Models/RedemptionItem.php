<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'redemption_id', 
        'reward_id', 
        'quantity', 
        'points_spent'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'points_spent' => 'integer',
    ];

    public function redemption()
    {
        return $this->belongsTo(Redemption::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}

