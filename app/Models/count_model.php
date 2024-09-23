<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class count_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'count';
    protected $fillable = ['count_id', 'bidder_count', 'winner_count',
    'date',
    //'pickup_time',
    //,'image',
   // 'token',
   'status','created_at', 'updated_at'];
}
