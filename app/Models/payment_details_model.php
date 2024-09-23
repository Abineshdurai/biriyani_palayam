<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_details_model extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'payment_details';

    protected $fillable = [
        'payment_id', 
        'franchise_id', 
        'timer_id', 
        'order_id', 
        'user_id', 
        'name', 
        'mobile', 
        'time_slot',
        'pickup_point', 
        'pickup_time', 
        'transaction_id', 
        'payment_status', 
        'merchant_transaction_id', 
        'transaction_status', 
        'transaction_amount', 
        'status', 
        'date', 
        'time', 
        'created_at', 
        'updated_at'
    ];
}
