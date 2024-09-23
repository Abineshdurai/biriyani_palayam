<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fixed_order_details_model extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'fixed_order_details';

    protected $fillable = [
        'order_id',
        'franchise_id', 
        'franchise', 
        'user_id', 
        'menu_category_id', 
        'menu_category_name',
        'menu_quantity', 
        'total_menu_price', 
        'wallet',
        'time_slot',
        'name', 
        'mobile', 
        'pickup_point',
        'pickup_date', 
        'pickup_time', 
        'transaction_id', 
        'payment_status', 
        'merchant_transaction_id', 
        'transaction_amount', 
        'transaction_status', 
        'order_status', 
        'status', 
        'date', 
        'time', 
        'created_at', 
        'updated_at'];
}

