<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class email_verification_model extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'email_verification';

    protected $fillable = [
        'email', 'otp', 'created_at', 
    ];
}
