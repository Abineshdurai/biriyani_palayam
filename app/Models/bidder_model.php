<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bidder_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'bidder';
    protected $fillable = ['bidder_id','timer_id', 'user_id', 'name','status', 'date', 'created_at', 'updated_at'
//    'end_time',
];
    //'mobile',
    //,'image',
   // 'token','status','created_at'];
}
