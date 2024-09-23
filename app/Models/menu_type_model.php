<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class menu_type_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $table = 'menu_type';
    
    protected $fillable = ['menu_type_id', 'menu_type', 'status', 'created_at', 'updated_at'];

}