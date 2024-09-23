<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class phonepe_model extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'phonepe';
    protected $fillable = [
       'response',
       'status',
        'created_at'
    ];
}
