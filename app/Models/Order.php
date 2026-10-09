<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'user_id', 'customer_name', 'customer_email', 
        'customer_phone', 'shipping_address', 'total_price', 
        'payment_status', 'order_status','snap_token','tracking_number',
        'total_weight', 'shipping_cost', 'shipping_courier', 'shipping_service',
        'payment_method',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}