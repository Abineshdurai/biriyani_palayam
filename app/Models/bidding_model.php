<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bidding_model extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $table = 'bidding';
    
    protected $fillable = [
    'timer_id',
    'franchise_id', 
    'time_slot',
    'name',
    'user_id', 
    'menu_category_id',
    'menu_category_name', 
    'description',
    'current_price', 
    'menu_image',
    'status', 
    'date', 
    'created_at', 
    'updated_at',
    ];
}
