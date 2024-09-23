<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'user';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'user_image',
        'device_token',
        'token',
        'referral_code',
        'wallet',
        'status',
        'created_at'
    ];
}
