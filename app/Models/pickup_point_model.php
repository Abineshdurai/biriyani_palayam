<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pickup_point_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pickup_point';
    protected $fillable = ['franchise_id','owner_name','owner_number', 'pickup_id', 'pickup_location','googlemap_link', 'status', 'created_at'];
}
