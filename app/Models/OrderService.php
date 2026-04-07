<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderService extends Model
{
    use HasFactory;

    protected $table = 'order_services';

    protected $fillable = [
        'order_number',
        'user_id',
        'technician_id',
        'vehicle_name',
        'license_plate',
        'customer_notes',
        'total_price',
        'status',
        'payment_status',
        'completion_proof',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'technician_id' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_service_id');
    }
}

