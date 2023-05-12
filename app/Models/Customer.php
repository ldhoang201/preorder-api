<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'user_id'
    ];

    public function preorder(): BelongsTo
    {
        return $this->belongsTo(Preorder::class);
    }
}
