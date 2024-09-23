<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu_model extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $table = 'menu';
    
    protected $fillable = [
        'menu_type',
        'menu_category_id', 
        'franchise_id', 
        'menu_category_name', 
        'description', 
        'base_price', 
        'current_price', 
        'menu_image', 
        'status', 
        'created_at', 
        'updated_at'];
}
