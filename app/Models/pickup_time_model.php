<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pickup_time_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pickup_time';
    protected $fillable = ['franchise_id', 'timer_id', 'pickup_id', 'time_slot', 'pickup_time', 'status', 'created_at'];
}
