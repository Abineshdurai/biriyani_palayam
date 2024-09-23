<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class time_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'time_slots';
    protected $fillable = ['franchise_id', 'timer_id', 'starting_time',
    'end_time',
    'pickup_time',
    //,'image',
   // 'token',
   'status','created_at', 'updated_at'];
}
